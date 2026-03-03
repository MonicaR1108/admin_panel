@extends('admin.layouts.app')

@section('title', 'Verify OTP')

@section('content')
    @if (in_array(config('mail.default'), ['log', 'array'], true))
        <div class="alert alert-warning mb-3">
            Mail is currently set to <strong>{{ config('mail.default') }}</strong>, so OTP emails won’t reach the inbox.
            Check <code>storage/logs/laravel.log</code> or configure SMTP in <code>.env</code> (set <code>MAIL_MAILER=smtp</code>).
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-1">Verify OTP</h1>
            <div class="text-muted small">OTP sent to: <span class="fw-semibold">{{ $user->email }}</span></div>
        </div>
        <a href="{{ route('admin.user-details.create') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-4 p-lg-5" style="max-width:520px;">
            <form method="POST" action="{{ route('admin.user-details.verify-otp', $user) }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Enter OTP <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="otp"
                        value="{{ old('otp') }}"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        class="form-control form-control-lg text-center letter-spacing @error('otp') is-invalid @enderror"
                        maxlength="6"
                        placeholder="******"
                        required
                    >
                    @error('otp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">OTP expires at {{ $user->otp_expiry }}.</div>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">
                    <i class="bi bi-shield-check me-1"></i>Verify & Create Profile
                </button>
            </form>
        </div>
    </div>

    <style>
        .letter-spacing{ letter-spacing: .35em; }
    </style>
@endsection
