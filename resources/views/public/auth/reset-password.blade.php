<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - {{ config('app.name', 'Application') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/admin.css') }}?v={{ @filemtime(public_path('assets/admin.css')) }}" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="container py-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0">Reset Password</h1>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('public.password.request') }}">Back</a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5" style="max-width:520px;">
                <form method="POST" action="{{ route('public.password.reset') }}" novalidate>
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    @error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror

                    <button type="submit" class="btn btn-success w-100">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
