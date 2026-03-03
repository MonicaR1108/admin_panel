@extends('admin.layouts.auth')

@section('title', 'Admin Login')

@section('content')
    <div class="auth-page">
        <div class="container flex-grow-1 d-flex align-items-center py-4">
            <div class="row w-100 align-items-center g-4 g-lg-5">
                <div class="col-12 col-lg-6">
                    <div class="auth-brand">
                        <img class="auth-logo" src="{{ asset('assets/garage-bill-logo.svg') }}" alt="Garage Bill">
                        <div>
                            <div class="auth-brand-name">Garage Bill</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5 offset-lg-1">
                    <div class="card auth-card mt-lg-3">
                        <div class="card-body p-4 p-lg-5">
                            <div class="text-center mb-4">
                                <div class="fw-bold fs-4">Welcome</div>
                                <div class="text-muted">Login to your account</div>
                            </div>

                            <form method="POST" action="{{ route('admin.login') }}" novalidate>
                                @csrf

                                <div class="mb-3 auth-input">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input
                                            type="text"
                                            name="identifier"
                                            value="{{ old('identifier') }}"
                                            class="form-control @error('identifier') is-invalid @enderror"
                                            autocomplete="username"
                                            required
                                        >
                                    </div>
                                    @error('identifier')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4 auth-input">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            autocomplete="current-password"
                                            required
                                        >
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button class="btn btn-success w-100 py-2" type="submit">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-footer text-center pb-3">
            Copyright &copy; {{ date('Y') }} Garage Bill.
        </div>
    </div>
@endsection
