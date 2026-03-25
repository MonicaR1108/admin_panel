@extends('admin.layouts.auth')

@section('title', 'Admin Reset Password')

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
                                Set a new admin password<br>
                                and get back to work.
                            </h1>
                        </div>
                    </section>

                    <section class="login-right" aria-label="Admin reset password">
                        <div class="login-card">
                            <div class="login-card-top">
                                <a class="btn btn-outline-secondary btn-sm login-back-btn" href="{{ route('admin.password.request') }}" data-no-loader>
                                    <i class="bi bi-arrow-left me-1"></i>Back to Request
                                </a>
                            </div>

                            <div class="text-center mb-4">
                                <div class="fw-bold fs-4">Reset Password</div>
                                <div class="text-muted">Choose a new password for your admin account.</div>
                            </div>

                            <form method="POST" action="{{ route('admin.password.reset') }}" novalidate>
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ old('email', $email) }}">

                                <div class="mb-3 auth-input">
                                    <label class="form-label">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    </div>
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3 auth-input">
                                    <label class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>

                                @error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror

                                <button class="btn btn-success w-100 py-2 login-submit" type="submit">
                                    <i class="bi bi-check2-circle me-2"></i>Update Password
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
