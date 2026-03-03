<?php

namespace App\Http\Middleware;

use App\Models\ApplicationUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackApplicationUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        if (! $request->session()->has('public_guest_id')) {
            $request->session()->put('public_guest_id', 'Guest-'.Str::upper(Str::random(8)));
        }

        $userName = $request->session()->get('public_user_name')
            ?: $request->session()->get('public_guest_id');

        ApplicationUser::query()->create([
            'user_name' => $userName,
            'visit_date' => now()->toDateString(),
            'visit_time' => now()->format('H:i:s'),
            'device_type' => $this->detectDeviceType((string) $request->userAgent()),
            'ip_address' => (string) $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return $next($request);
    }

    private function detectDeviceType(string $userAgent): string
    {
        $ua = Str::lower($userAgent);

        if (Str::contains($ua, ['ipad', 'tablet', 'kindle', 'silk', 'playbook'])) {
            return 'Tablet';
        }

        if (Str::contains($ua, ['mobi', 'iphone', 'ipod', 'android'])) {
            return 'Mobile';
        }

        return 'Desktop';
    }
}
