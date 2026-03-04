<?php

namespace App\Http\Controllers;

use App\Mail\PublicUserPasswordResetMail;
use App\Models\PublicUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PublicPasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('public.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:190'],
        ]);

        $email = Str::lower(trim($validated['email']));

        $throttleKey = 'public-reset-link|'.$request->ip().'|'.$email;
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->with('status', 'Please wait a moment before trying again.');
        }
        RateLimiter::hit($throttleKey, 60);

        $user = PublicUser::query()->where('email', $email)->first();

        // Prevent user enumeration: always respond with a generic message.
        if (! $user) {
            return back()->with('status', 'If the email exists, a reset link has been sent.');
        }

        $tokenPlain = Str::random(64);
        $tokenHash = hash('sha256', $tokenPlain);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $tokenHash,
            'created_at' => now(),
        ]);

        $resetUrl = route('public.password.reset.form', ['token' => $tokenPlain, 'email' => $email]);

        try {
            Mail::to($email)->send(new PublicUserPasswordResetMail($resetUrl, $user->name));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('status', 'If the email exists, a reset link has been sent.');
    }

    public function resetForm(Request $request, string $token)
    {
        $email = (string) $request->query('email', '');

        return view('public.auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'min:10', 'max:255'],
            'email' => ['required', 'email', 'max:190'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = Str::lower(trim($validated['email']));
        $tokenHash = hash('sha256', (string) $validated['token']);

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $record || ! hash_equals((string) $record->token, $tokenHash)) {
            return back()->withErrors(['email' => 'Invalid reset link.'])->withInput();
        }

        $createdAt = null;
        try {
            $createdAt = $record->created_at ? Carbon::parse((string) $record->created_at) : null;
        } catch (\Throwable) {
            $createdAt = null;
        }

        if (! $createdAt || $createdAt->lt(now()->subMinutes(30))) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return back()->withErrors(['email' => 'Reset link expired. Please request a new one.'])->withInput();
        }

        $user = PublicUser::query()->where('email', $email)->first();
        if (! $user) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return back()->withErrors(['email' => 'Unable to reset password for this email.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'updated_on' => now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('public.password.request')->with('status', 'Password updated successfully. You can now login.');
    }
}
