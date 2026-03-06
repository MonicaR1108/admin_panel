<?php

namespace App\Http\Controllers;

use App\Models\PublicUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PublicPasswordSetupController extends Controller
{
    public function store(Request $request)
    {
        $userId = $request->session()->get('password_setup_user_id');
        if (! $userId) {
            return redirect()->route('public.home')->with('status', 'Password setup session expired. Please use the verification link again.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = PublicUser::query()->whereKey($userId)->first();
        if (! $user) {
            $request->session()->forget('password_setup_user_id');

            return redirect()->route('public.home')->with('status', 'Unable to activate this account. Please contact the admin.');
        }

        $isVerified = (string) ($user->verified ?? '') === 'true' || ! empty($user->email_verified_at);
        if (! $isVerified) {
            return redirect()->route('public.home')->with('status', 'Please verify your email first.');
        }

        $requiresPasswordSetup = Schema::hasColumn('users', 'pending_status') && ! empty($user->pending_status);
        if (! $requiresPasswordSetup) {
            $request->session()->forget('password_setup_user_id');

            return redirect()->route('public.home')->with('status', 'Your account is already active.');
        }

        $now = now()->format('Y-m-d H:i:s');

        $finalStatus = 'active';
        $pendingStatus = (string) ($user->pending_status ?? '');
        if (in_array($pendingStatus, ['active', 'inactive'], true)) {
            $finalStatus = $pendingStatus;
        }

        $updates = [
            'password' => Hash::make($validated['password']),
            'status' => $finalStatus,
            'updated_on' => $now,
        ];

        if (Schema::hasColumn('users', 'pending_status')) {
            $updates['pending_status'] = null;
        }

        $user->update($updates);

        $request->session()->forget('password_setup_user_id');

        return redirect()->route('public.home')->with('status', 'Password created successfully. Your account is now active.');
    }
}
