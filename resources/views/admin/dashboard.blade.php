@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h4 page-title mb-1">Dashboard</h1>
            <div class="text-muted">Welcome back, {{ Auth::guard('admin')->user()?->full_name ?? Auth::guard('admin')->user()?->username ?? 'Admin' }}.</div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.master-items.index') }}" class="text-decoration-none text-reset">
                <div class="card module-card rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="module-icon" aria-hidden="true">
                                <i class="bi bi-box-seam fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Item Master</div>
                                <div class="text-muted small">Manage items, status, and details</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Total</div>
                            <div class="fs-4 fw-bold" data-counter>{{ $itemsCount }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.user-details.index') }}" class="text-decoration-none text-reset">
                <div class="card module-card rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="module-icon" aria-hidden="true">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">User Details</div>
                                <div class="text-muted small">View public user records</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Users</div>
                            <div class="fs-4 fw-bold" data-counter>{{ $totalApplicationUsers }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3">
        <div class="col">
            <div class="stat-card grad-blue rounded-4 h-100">
                <div class="stat-body">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Total Application Users</div>
                            <div class="stat-value" data-counter>{{ $totalApplicationUsers }}</div>
                        </div>
                        <div class="stat-icon" aria-hidden="true"><i class="bi bi-people"></i></div>
                    </div>
                    <div class="stat-foot">All registered public users</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card grad-green rounded-4 h-100">
                <div class="stat-body">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Today's Active Users</div>
                            <div class="stat-value" data-counter>{{ $todaysActiveUsers }}</div>
                        </div>
                        <div class="stat-icon" aria-hidden="true"><i class="bi bi-activity"></i></div>
                    </div>
                    <div class="stat-foot">Based on today’s visits</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card grad-purple rounded-4 h-100">
                <div class="stat-body">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Devices Today (Mobile)</div>
                            <div class="stat-value" data-counter>{{ $deviceSummary['Mobile'] ?? 0 }}</div>
                        </div>
                        <div class="stat-icon" aria-hidden="true"><i class="bi bi-phone"></i></div>
                    </div>
                    <div class="stat-foot">Mobile sessions today</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card grad-orange rounded-4 h-100">
                <div class="stat-body">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Devices Today (Desktop)</div>
                            <div class="stat-value" data-counter>{{ $deviceSummary['Desktop'] ?? 0 }}</div>
                        </div>
                        <div class="stat-icon" aria-hidden="true"><i class="bi bi-pc-display"></i></div>
                    </div>
                    <div class="stat-foot">Desktop sessions today</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card grad-slate rounded-4 h-100">
                <div class="stat-body">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Total Master Items</div>
                            <div class="stat-value" data-counter>{{ $itemsCount }}</div>
                        </div>
                        <div class="stat-icon" aria-hidden="true"><i class="bi bi-box-seam"></i></div>
                    </div>
                    <div class="stat-foot">Items in item master</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card grad-blue rounded-4 h-100">
                <div class="stat-body">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Admin Accounts</div>
                            <div class="stat-value" data-counter>{{ $adminsCount }}</div>
                        </div>
                        <div class="stat-icon" aria-hidden="true"><i class="bi bi-shield-lock"></i></div>
                    </div>
                    <div class="stat-foot">Admins who can access panel</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12 col-xl-8">
            <div class="card dashboard-card rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="fw-semibold">Device Usage Summary (Today)</div>
                        <div class="text-muted small">@dmy(now())</div>
                    </div>

                    @php
                        $mobile = (int) ($deviceSummary['Mobile'] ?? 0);
                        $desktop = (int) ($deviceSummary['Desktop'] ?? 0);
                        $tablet = (int) ($deviceSummary['Tablet'] ?? 0);
                        $total = max(1, $mobile + $desktop + $tablet);
                    @endphp

                    <div class="mb-2 d-flex justify-content-between">
                        <div class="text-muted">Mobile</div>
                        <div class="fw-semibold">{{ $mobile }}</div>
                    </div>
                    <div class="progress mb-3" style="height:10px;">
                        <div class="progress-bar bg-primary" style="width: {{ round(($mobile / $total) * 100) }}%"></div>
                    </div>

                    <div class="mb-2 d-flex justify-content-between">
                        <div class="text-muted">Desktop</div>
                        <div class="fw-semibold">{{ $desktop }}</div>
                    </div>
                    <div class="progress mb-3" style="height:10px;">
                        <div class="progress-bar bg-warning" style="width: {{ round(($desktop / $total) * 100) }}%"></div>
                    </div>

                    <div class="mb-2 d-flex justify-content-between">
                        <div class="text-muted">Tablet</div>
                        <div class="fw-semibold">{{ $tablet }}</div>
                    </div>
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar bg-success" style="width: {{ round(($tablet / $total) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card dashboard-card rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="fw-semibold mb-2">Quick Actions</div>
                    <div class="text-muted small mb-3">Common admin tasks</div>

                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-primary" href="{{ route('admin.master-items.index') }}">
                            <i class="bi bi-plus-circle me-2"></i>Add / Manage Items
                        </a>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.user-details.index') }}">
                            <i class="bi bi-people me-2"></i>View Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
