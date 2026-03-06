<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Garage Bill')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/admin.css') }}?v={{ @filemtime(public_path('assets/admin.css')) }}" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-shell">
        @include('public.partials.sidebar')
        @include('public.partials.header')

        <main class="admin-content">
            <div class="container-fluid px-3 px-lg-4">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="py-3 py-lg-4">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <div class="sidebar-backdrop" data-sidebar-backdrop></div>

    <button class="back-to-top" type="button" data-back-to-top aria-label="Back to top" title="Back to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <div class="page-loader" data-page-loader aria-hidden="true">
        <div class="spinner-border text-success" role="status" aria-label="Loading"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/admin.js') }}"></script>
</body>
</html>
