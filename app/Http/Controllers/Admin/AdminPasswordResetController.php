<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordResetMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminPasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:190'],
        ]);

        $email = Str::lower(trim($validated['email']));

        $throttleKey = 'admin-reset-link|'.$request->ip().'|'.$email;
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->with('status', 'Please wait a moment before trying again.');
        }
        RateLimiter::hit($throttleKey, 60);

        $admin = Admin::query()->where('email', $email)->first();

        // Prevent account enumeration.
        if (! $admin) {
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

        $resetUrl = route('admin.password.reset.form', ['token' => $tokenPlain, 'email' => $email]);

        try {
            Mail::to($email)->send(new AdminPasswordResetMail($resetUrl, $admin->full_name ?? $admin->username ?? 'Admin'));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('status', 'If the email exists, a reset link has been sent.');
    }

    public function resetForm(Request $request, string $token)
    {
        $email = (string) $request->query('email', '');

        return view('admin.auth.reset-password', [
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

        $admin = Admin::query()->where('email', $email)->first();
        if (! $admin) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return back()->withErrors(['email' => 'Unable to reset password for this email.'])->withInput();
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')->with('status', 'Password updated successfully. You can now login.');
    }
}
