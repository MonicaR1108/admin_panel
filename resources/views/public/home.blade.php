<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Application') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h4 mb-0">{{ config('app.name', 'Application') }}</h1>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Admin Login</a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <p class="text-muted mb-3">
                    Public users can access this application without entering the admin panel.
                    Your visits are tracked automatically.
                </p>

                <div class="mb-3">
                    <div class="text-muted small">Your ID</div>
                    <div class="fw-semibold">{{ $publicUserName ?? $publicGuestId ?? 'Guest' }}</div>
                </div>

                <form method="POST" action="{{ route('public.set-name') }}" class="row g-2">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label">User Name (optional)</label>
                        <input type="text" name="user_name" class="form-control" placeholder="Enter your name">
                        <div class="form-text">Leave empty to use a guest ID.</div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary w-100" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

