<?php

namespace App\Http\Controllers;

use App\Models\PublicUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class PublicUserVerificationController extends Controller
{
    public function verify(Request $request, PublicUser $user, string $hash)
    {
        $ignoreQuery = function (string $key): bool {
            return str_starts_with($key, 'utm_')
                || in_array($key, ['fbclid', 'gclid', 'mc_cid', 'mc_eid', 'igshid'], true);
        };

        Log::info('Public user verification link opened.', [
            'full_url' => $request->fullUrl(),
            'user_id' => $user->getKey(),
            'app_url' => (string) config('app.url', ''),
        ]);

        if (! URL::hasValidSignature($request, true, $ignoreQuery)) {
            Log::warning('Public user verification invalid signature.', [
                'full_url' => $request->fullUrl(),
                'user_id' => $user->getKey(),
            ]);

            return view('public.auth.verify-user', [
                'status' => 'Verification link is invalid or has expired. Please contact the admin to resend the verification link.',
                'statusType' => 'danger',
            ]);
        }

        if (! hash_equals(sha1((string) $user->email), (string) $hash)) {
            abort(403);
        }

        if ((string) $user->verified === 'true') {
            return view('public.auth.verify-user', [
                'status' => 'Your account is already verified.',
                'statusType' => 'success',
            ]);
        }

        $pendingStatus = 'active';
        if (Schema::hasColumn('users', 'pending_status')) {
            $pendingStatus = (string) ($user->pending_status ?? '');
            if (! in_array($pendingStatus, ['active', 'inactive'], true)) {
                $pendingStatus = 'active';
            }
        }

        $now = now()->format('Y-m-d H:i:s');

        $updates = [
            'verified' => 'true',
            ...(Schema::hasColumn('users', 'email_verified_at') ? ['email_verified_at' => $now] : []),
            'otp' => null,
            'otp_expiry' => '',
            'status' => $pendingStatus,
            'updated_on' => $now,
            'updated_by' => (string) ($user->created_by ?? $user->updated_by ?? ''),
        ];

        if (Schema::hasColumn('users', 'pending_status')) {
            $updates['pending_status'] = null;
        }

        $user->update($updates);

        Log::info('Public user verified successfully.', [
            'user_id' => $user->getKey(),
        ]);

        return view('public.auth.verify-user', [
            'status' => 'Your account has been successfully verified.',
            'statusType' => 'success',
        ]);
    }
}
