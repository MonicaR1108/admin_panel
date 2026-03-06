<aside class="admin-sidebar" aria-label="Sidebar">
    <nav class="sidebar-nav">
        <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-house-door"></i>
            <span class="label">Dashboard</span>
        </a>

        <a class="sidebar-link {{ request()->routeIs('admin.master-items.*') ? 'active' : '' }}" href="{{ route('admin.master-items.index') }}">
            <i class="bi bi-box-seam"></i>
            <span class="label">Master Item</span>
        </a>

        <a class="sidebar-link {{ request()->routeIs('admin.user-details.*') ? 'active' : '' }}" href="{{ route('admin.user-details.index') }}">
            <i class="bi bi-people"></i>
            <span class="label">User Details</span>
        </a>

        <a class="sidebar-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
            <i class="bi bi-receipt"></i>
            <span class="label">Transactions</span>
        </a>
    </nav>
</aside>
