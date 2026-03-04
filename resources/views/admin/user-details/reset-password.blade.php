@extends('admin.layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-1">Reset Password</h1>
            <div class="text-muted small">
                User: <span class="fw-semibold">{{ $user->name }}</span> ({{ $user->email }})
            </div>
        </div>
        <a href="{{ route('admin.user-details.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-4 p-lg-5" style="max-width:720px;">
            <form method="POST" action="{{ route('admin.user-details.reset-password', $user) }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label d-block">Reset mode <span class="text-danger">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mode" id="modeAuto" value="auto" @checked(old('mode', 'auto') === 'auto')>
                        <label class="form-check-label" for="modeAuto">
                            Generate a temporary password automatically and email it to the user
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mode" id="modeManual" value="manual" @checked(old('mode') === 'manual')>
                        <label class="form-check-label" for="modeManual">
                            Set a new password manually and email it to the user
                        </label>
                    </div>
                    @error('mode')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">New Password (manual mode)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Minimum 8 characters.</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                @if ($errors->has('email'))
                    <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                @endif

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-key me-1"></i>Reset Password & Send Email
                </button>
            </form>
        </div>
    </div>
@endsection

