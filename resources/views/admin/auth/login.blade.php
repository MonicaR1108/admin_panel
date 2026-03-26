@extends('admin.layouts.auth')

@section('title', 'Admin Login')

@section('content')
    <div class="auth-page admin-login-page">
        <div class="login-shell">
            <div class="container-fluid px-3 px-lg-5">
                <div class="login-grid">
                    <section class="login-left" aria-label="Overview">
                        <div class="login-hero">
                            <img
                                class="login-brand-logo"
                                src="{{ asset('assets/logo_netautocare1.png') }}?v={{ @filemtime(public_path('assets/logo_netautocare1.png')) }}"
                                alt="Garage Bill"
                            >

                            <h1 class="login-hero-title">
                                Streamline your garage's billing<br>
                                and operations.
                            </h1>
                        </div>
                    </section>

                    <section class="login-right" aria-label="Admin login">
                        <div class="login-card">
                            <div class="text-center mb-4">
                                <div class="fw-bold fs-4">Welcome</div>
                                <div class="text-muted">Log in to your admin account.</div>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif

                            <form method="POST" action="{{ route('admin.login') }}" novalidate>
                                @csrf

                                <div class="mb-3 auth-input">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input
                                            type="email"
                                            name="identifier"
                                            value="{{ old('identifier') }}"
                                            class="form-control @error('identifier') is-invalid @enderror"
                                            autocomplete="email"
                                            required
                                        >
                                    </div>
                                    @error('identifier')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 auth-input">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            autocomplete="current-password"
                                            required
                                        >
                                        <button
                                            id="togglePassword"
                                            class="input-group-text password-toggle"
                                            type="button"
                                            aria-label="Show password"
                                            aria-pressed="false"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="remember"
                                            id="remember"
                                            {{ old('remember') ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a class="text-decoration-none" href="{{ route('admin.password.request') }}">Forgot password?</a>
                                </div>

                                <button class="btn btn-success w-100 py-2 login-submit" type="submit">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const toggleIcon = toggleButton?.querySelector('i');

            if (!passwordInput || !toggleButton || !toggleIcon) return;

            const setVisible = (visible) => {
                passwordInput.type = visible ? 'text' : 'password';
                toggleIcon.classList.toggle('bi-eye', !visible);
                toggleIcon.classList.toggle('bi-eye-slash', visible);
                toggleButton.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
                toggleButton.setAttribute('aria-pressed', visible ? 'true' : 'false');
            };

            setVisible(false);

            toggleButton.addEventListener('click', () => {
                setVisible(passwordInput.type === 'password');
            });
        });
    </script>
@endsection
