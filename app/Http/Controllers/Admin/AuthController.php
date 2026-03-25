<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create()
    {
        return view('admin.auth.login');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'email', 'max:190'],
            'password' => ['required', 'string', 'max:190'],
        ]);

        $remember = $request->boolean('remember') && Schema::hasColumn('admins', 'remember_token');

        $identifier = Str::lower(trim($validated['identifier']));
        $throttleKey = 'admin-login|'.$request->ip().'|'.$identifier;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'identifier' => ['Too many login attempts. Please try again later.'],
            ]);
        }

        $admin = Admin::query()
            ->where('email', $identifier)
            ->first();

        if (! $admin || ! Hash::check($validated['password'], (string) $admin->password)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'identifier' => ['Invalid credentials.'],
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::guard('admin')->login($admin, $remember);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
