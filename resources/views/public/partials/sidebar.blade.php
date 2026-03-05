<aside class="admin-sidebar" aria-label="Sidebar">
    <nav class="sidebar-nav">
        <a class="sidebar-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">
            <i class="bi bi-house-door"></i>
            <span class="label">Home</span>
        </a>
    </nav>
</aside>
