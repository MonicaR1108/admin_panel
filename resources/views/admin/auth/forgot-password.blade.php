@extends('admin.layouts.auth')

@section('title', 'Admin Forgot Password')

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
                                Reset your admin password<br>
                                in a few clicks.
                            </h1>
                        </div>
                    </section>

                    <section class="login-right" aria-label="Admin forgot password">
                        <div class="login-card">
                            <div class="login-card-top">
                                <a class="btn btn-outline-secondary btn-sm login-back-btn" href="{{ route('login') }}" data-no-loader>
                                    <i class="bi bi-arrow-left me-1"></i>Back to Login
                                </a>
                            </div>

                            <div class="text-center mb-4">
                                <div class="fw-bold fs-4">Forgot Password</div>
                                <div class="text-muted">We will email you a reset link.</div>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif

                            <form method="POST" action="{{ route('admin.password.email') }}" novalidate>
                                @csrf

                                <div class="mb-3 auth-input">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            autocomplete="email"
                                            required
                                        >
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">We will email you a password reset link.</div>
                                </div>

                                <button class="btn btn-success w-100 py-2 login-submit" type="submit">
                                    <i class="bi bi-send me-2"></i>Send Reset Link
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
