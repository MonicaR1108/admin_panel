<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Mail\PublicUserVerificationMail;
use App\Mail\AdminUserPasswordResetMail;

class UserDetailsController extends Controller
{
    private function buildVerificationUrl(Request $request, PublicUser $user, \Illuminate\Support\Carbon $expiresAt): string
    {
        $rootUrl = (string) config('app.url', '');
        if ($rootUrl === '') {
            $rootUrl = $request->getSchemeAndHttpHost();
        }

        $url = url();
        $url->forceRootUrl($rootUrl);
        $url->forceScheme((string) (parse_url($rootUrl, PHP_URL_SCHEME) ?: $request->getScheme()));

        try {
            // Sign the *relative* URL so signature stays valid even if host/scheme changes.
            $relativeSigned = $url->temporarySignedRoute('public.user.verify', $expiresAt, [
                'user' => $user->getKey(),
                'hash' => sha1((string) $user->email),
            ], false);

            return rtrim($rootUrl, '/') . '/' . ltrim($relativeSigned, '/');
        } finally {
            $url->forceRootUrl(null);
            $url->forceScheme(null);
        }
    }

    public function index(Request $request)
    {
        $verifiedFilter = (string) $request->query('verified', 'all'); // all|verified|pending

        $query = PublicUser::query();

        $hasEmailVerifiedAt = Schema::hasColumn('users', 'email_verified_at');

        if ($verifiedFilter === 'verified') {
            $query->where(function ($q) use ($hasEmailVerifiedAt) {
                $q->where('verified', 'true');
                if ($hasEmailVerifiedAt) {
                    $q->orWhereNotNull('email_verified_at');
                }
            });
        } elseif ($verifiedFilter === 'pending') {
            $query->where(function ($q) use ($hasEmailVerifiedAt) {
                $q->whereNull('verified')->orWhere('verified', '!=', 'true');
                if ($hasEmailVerifiedAt) {
                    $q->whereNull('email_verified_at');
                }
            });
        }

        return view('admin.user-details.index', [
            'verifiedFilter' => $verifiedFilter,
            'users' => $query->orderByDesc('ID')->paginate(20)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.user-details.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:190'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'business_name' => ['required', 'string', 'max:190'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $intendedStatus = (string) $validated['status'];

        $admin = Auth::guard('admin')->user();
        $adminId = (string) ($admin?->id ?? '');
        $now = now();

        $user = PublicUser::query()->create([
            'name' => trim($validated['full_name']),
            'email' => strtolower(trim($validated['email'])),
            ...(Schema::hasColumn('users', 'email_verified_at') ? ['email_verified_at' => null] : []),
            'mobile' => trim((string) ($validated['mobile'] ?? '')),
            'address' => trim((string) ($validated['address'] ?? '')),
            'BusinessName' => trim($validated['business_name']),
            'username' => null,
            // Placeholder password until the user verifies and creates their own password.
            'password' => Hash::make(Str::random(48)),
            // Keep account inactive until user verifies + sets password.
            'status' => 'inactive',
            ...(Schema::hasColumn('users', 'pending_status') ? ['pending_status' => $intendedStatus] : []),
            // Columns required by existing `whyceffy_netautocare.users` table definition:
            'otp' => null,
            'otp_expiry' => '',
            'verified' => 'false',
            'access_token' => Str::random(180),
            'access_token_expiry' => $now->copy()->addDays(30)->format('Y-m-d H:i:s'),
            'refresh_token' => hash('sha256', Str::random(80)),
            'refresh_token_expiry' => 10368000,
            'created_on' => $now->format('Y-m-d H:i:s'),
            'created_by' => $adminId,
            'updated_on' => $now->format('Y-m-d H:i:s'),
            'updated_by' => $adminId,
        ]);

        try {
            $expiresAt = $now->copy()->addMinutes(60);
            $verificationUrl = $this->buildVerificationUrl($request, $user, $expiresAt);

            Mail::to($user->email)->send(new PublicUserVerificationMail($verificationUrl, $expiresAt->format('Y-m-d H:i:s'), (string) $user->name));
            Log::info('Verification link email sent.', [
                'to' => $user->email,
                'user_id' => $user->getKey(),
                'mailer' => (string) config('mail.default', ''),
            ]);
        } catch (\Throwable $e) {
            report($e);

            $message = 'Unable to send verification email. Please check SMTP settings (Gmail requires an App Password) and try again.';
            if ((bool) config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput()
                ->withErrors(['email' => $message]);
        }

        return redirect()
            ->route('admin.user-details.index')
            ->with('status', $this->verificationStatusMessage());
    }

    private function verificationStatusMessage(): string
    {
        $mailer = (string) config('mail.default', 'log');

        if (in_array($mailer, ['log', 'array'], true)) {
            return 'Verification link generated. Mail is configured as "' . $mailer . '" (not sent to inbox). Check storage/logs/laravel.log to copy the link.';
        }

        return 'Verification link sent to the user email. The account stays pending until the user verifies and creates a password.';
    }

    public function resendVerificationLink(Request $request, PublicUser $user)
    {
        if ((string) $user->verified === 'true') {
            return redirect()->route('admin.user-details.index')->with('status', 'User already verified.');
        }

        try {
            $expiresAt = now()->addMinutes(60);
            $verificationUrl = $this->buildVerificationUrl($request, $user, $expiresAt);

            Mail::to($user->email)->send(new PublicUserVerificationMail($verificationUrl, $expiresAt->format('Y-m-d H:i:s'), (string) $user->name));
        } catch (\Throwable $e) {
            report($e);

            $message = 'Unable to send verification email. Please check SMTP settings and try again.';
            if ((bool) config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()->withErrors(['email' => $message]);
        }

        return back()->with('status', $this->verificationStatusMessage());
    }

    public function pendingVerification(PublicUser $user)
    {
        if ((string) $user->verified === 'true') {
            return redirect()->route('admin.user-details.show', $user);
        }

        return view('admin.user-details.pending-verification', [
            'user' => $user,
        ]);
    }

    public function show(PublicUser $user)
    {
        if ((string) $user->verified !== 'true') {
            return redirect()->route('admin.user-details.pending-verification', $user);
        }

        return view('admin.user-details.show', [
            'user' => $user,
        ]);
    }

    public function edit(PublicUser $user)
    {
        if ((string) $user->verified !== 'true') {
            return redirect()->route('admin.user-details.pending-verification', $user);
        }

        return view('admin.user-details.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, PublicUser $user)
    {
        if ((string) $user->verified !== 'true') {
            return redirect()->route('admin.user-details.pending-verification', $user);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:190'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,' . $user->getKey() . ',ID'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'business_name' => ['required', 'string', 'max:190'],
            'address' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:190', 'unique:users,username,' . $user->getKey() . ',ID'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $admin = Auth::guard('admin')->user();
        $adminId = (string) ($admin?->id ?? '');
        $now = now();

        $username = trim((string) ($validated['username'] ?? ''));
        $username = $username === '' ? null : $username;

        $updates = [
            'name' => trim($validated['full_name']),
            'email' => strtolower(trim($validated['email'])),
            'mobile' => trim((string) ($validated['mobile'] ?? '')),
            'address' => trim((string) ($validated['address'] ?? '')),
            'BusinessName' => trim($validated['business_name']),
            'username' => $username,
            'status' => $validated['status'],
            'updated_on' => $now->format('Y-m-d H:i:s'),
            'updated_by' => $adminId,
        ];

        $user->update($updates);

        return redirect()->route('admin.user-details.edit', $user)->with('status', 'User updated.');
    }

    public function destroy(PublicUser $user)
    {
        $user->delete();

        return redirect()->route('admin.user-details.index')->with('status', 'User deleted.');
    }

    public function resetPasswordForm(PublicUser $user)
    {
        return view('admin.user-details.reset-password', [
            'user' => $user,
        ]);
    }

    public function resetPassword(Request $request, PublicUser $user)
    {
        $validated = $request->validate([
            'mode' => ['required', 'in:auto,manual'],
            'password' => ['required_if:mode,manual', 'nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = Auth::guard('admin')->user();
        $adminId = (string) ($admin?->id ?? '');
        $now = now();

        $newPassword = $validated['mode'] === 'manual'
            ? (string) ($validated['password'] ?? '')
            : Str::random(12);

        $user->update([
            'password' => Hash::make($newPassword),
            'updated_on' => $now->format('Y-m-d H:i:s'),
            'updated_by' => $adminId,
        ]);

        try {
            $login = (string) ($user->username ?: $user->email);
            Mail::to((string) $user->email)->send(new AdminUserPasswordResetMail($login, $newPassword, (string) $user->name));
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['email' => 'Password updated, but the notification email could not be sent.'])->withInput();
        }

        return redirect()->route('admin.user-details.index')->with('status', 'Password reset and email sent to user.');
    }
}
