<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserOtpMail;
use App\Mail\AdminUserPasswordResetMail;
use Illuminate\Support\Carbon;

class UserDetailsController extends Controller
{
    public function index(Request $request)
    {
        $verifiedFilter = (string) $request->query('verified', 'all'); // all|verified|pending

        $query = PublicUser::query();

        if ($verifiedFilter === 'verified') {
            $query->where('verified', 'true');
        } elseif ($verifiedFilter === 'pending') {
            $query->where(function ($q) {
                $q->whereNull('verified')->orWhere('verified', '!=', 'true');
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
            'mobile' => ['required', 'string', 'max:30'],
            'business_name' => ['required', 'string', 'max:190'],
            'address' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:190', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $intendedStatus = (string) $validated['status'];

        $admin = Auth::guard('admin')->user();
        $adminId = (string) ($admin?->id ?? '');
        $now = now();
        $otp = random_int(100000, 999999);
        $otpExpiry = $now->copy()->addMinutes(10)->format('Y-m-d H:i:s');

        $user = PublicUser::query()->create([
            'name' => trim($validated['full_name']),
            'email' => strtolower(trim($validated['email'])),
            'mobile' => trim($validated['mobile']),
            'address' => trim((string) ($validated['address'] ?? '')),
            'BusinessName' => trim($validated['business_name']),
            'username' => trim($validated['username']),
            'password' => Hash::make($validated['password']),
            // Keep accounts inactive until OTP is verified.
            'status' => 'inactive',
            // Columns required by existing `whyceffy_netautocare.users` table definition:
            'otp' => $otp,
            'otp_expiry' => $otpExpiry,
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

        $request->session()->put('pending_user_status.' . $user->getKey(), $intendedStatus);

        try {
            Mail::to($user->email)->send(new UserOtpMail($otp, $otpExpiry, $user->name));
            Log::info('OTP email sent.', [
                'to' => $user->email,
                'user_id' => $user->getKey(),
                'mailer' => (string) config('mail.default', ''),
            ]);
        } catch (\Throwable $e) {
            report($e);
            $user->delete();
            $request->session()->forget('pending_user_status.' . $user->getKey());

            return back()
                ->withInput()
                ->withErrors(['email' => 'Unable to send OTP email. Please check SMTP settings (Gmail requires an App Password) and try again.']);
        }

        return redirect()
            ->route('admin.user-details.verify-otp.form', $user)
            ->with('status', $this->otpStatusMessage());
    }

    private function otpStatusMessage(): string
    {
        $mailer = (string) config('mail.default', 'log');

        if (in_array($mailer, ['log', 'array'], true)) {
            return 'OTP generated. Mail is configured as "' . $mailer . '" (not sent to inbox). Check storage/logs/laravel.log and then verify OTP.';
        }

        return 'OTP sent to the user email. Verify OTP to create the profile.';
    }

    public function verifyOtpForm(PublicUser $user)
    {
        if ((string) $user->verified === 'true') {
            return redirect()->route('admin.user-details.index')->with('status', 'User already verified.');
        }

        return view('admin.user-details.verify-otp', [
            'user' => $user,
        ]);
    }

    public function verifyOtp(Request $request, PublicUser $user)
    {
        if ((string) $user->verified === 'true') {
            return redirect()->route('admin.user-details.index')->with('status', 'User already verified.');
        }

        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $expiresAt = (string) ($user->otp_expiry ?? '');
        $isExpired = false;
        if ($expiresAt !== '') {
            try {
                $isExpired = now()->greaterThan(Carbon::parse($expiresAt));
            } catch (\Throwable) {
                $isExpired = true;
            }
        }

        if ($isExpired) {
            return back()->withErrors(['otp' => 'OTP has expired. Please create the user again.']);
        }

        if ((string) $user->otp !== (string) $validated['otp']) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        $admin = Auth::guard('admin')->user();
        $adminId = (string) ($admin?->id ?? '');
        $now = now();

        $intendedStatus = (string) $request->session()->pull('pending_user_status.' . $user->getKey(), '');
        if (! in_array($intendedStatus, ['active', 'inactive'], true)) {
            $intendedStatus = '';
        }

        $user->update([
            'verified' => 'true',
            'otp' => null,
            'otp_expiry' => '',
            ...( $intendedStatus !== '' ? ['status' => $intendedStatus] : [] ),
            'updated_on' => $now->format('Y-m-d H:i:s'),
            'updated_by' => $adminId,
        ]);

        return redirect()->route('admin.user-details.index')->with('status', 'User verified and created successfully.');
    }

    public function show(PublicUser $user)
    {
        if ((string) $user->verified !== 'true') {
            return redirect()->route('admin.user-details.verify-otp.form', $user);
        }

        return view('admin.user-details.show', [
            'user' => $user,
        ]);
    }

    public function edit(PublicUser $user)
    {
        if ((string) $user->verified !== 'true') {
            return redirect()->route('admin.user-details.verify-otp.form', $user);
        }

        return view('admin.user-details.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, PublicUser $user)
    {
        if ((string) $user->verified !== 'true') {
            return redirect()->route('admin.user-details.verify-otp.form', $user);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:190'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,' . $user->getKey() . ',ID'],
            'mobile' => ['required', 'string', 'max:30'],
            'business_name' => ['required', 'string', 'max:190'],
            'address' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:190', 'unique:users,username,' . $user->getKey() . ',ID'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $admin = Auth::guard('admin')->user();
        $adminId = (string) ($admin?->id ?? '');
        $now = now();

        $updates = [
            'name' => trim($validated['full_name']),
            'email' => strtolower(trim($validated['email'])),
            'mobile' => trim($validated['mobile']),
            'address' => trim((string) ($validated['address'] ?? '')),
            'BusinessName' => trim($validated['business_name']),
            'username' => trim($validated['username']),
            'status' => $validated['status'],
            'updated_on' => $now->format('Y-m-d H:i:s'),
            'updated_by' => $adminId,
        ];

        if (! empty($validated['password'])) {
            $updates['password'] = Hash::make($validated['password']);
        }

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
