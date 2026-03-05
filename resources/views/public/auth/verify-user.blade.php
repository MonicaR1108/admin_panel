<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Application') }} - Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5" style="max-width:720px;">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h5 mb-2">Account verification</h1>
                <div class="alert alert-{{ $statusType ?? 'success' }} mb-4">
                    {{ $status ?? 'Verification completed.' }}
                </div>

                <div class="d-flex gap-2">
                    <a class="btn btn-primary" href="{{ route('public.home') }}">Go to Home</a>
                    <a class="btn btn-outline-secondary" href="{{ route('public.password.request') }}">Forgot Password</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
