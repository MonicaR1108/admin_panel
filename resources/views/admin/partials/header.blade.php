@php
    $admin = Auth::guard('admin')->user();
@endphp

<header class="admin-topbar" aria-label="Top bar">
    <div class="topbar-left">
        <img class="topbar-logo" src="{{ asset('assets/garage-bill-logo.svg') }}" alt="Company Logo">

        <a class="topbar-title" href="{{ route('dashboard') }}">
            {{ config('app.name', 'Garage Bill') }}
        </a>

        <button class="icon-btn" type="button" data-sidebar-toggle aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="topbar-right">
        <div class="d-none d-md-flex align-items-center gap-2">
            <span class="topbar-user-pill">
                {{ $admin?->full_name ?? $admin?->username ?? 'Admin' }}
            </span>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-danger px-3 text-nowrap" type="submit">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    </div>
</header>
