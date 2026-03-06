@extends('admin.layouts.app')

@section('title', 'View User')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-1">User Details</h1>
            <div class="text-muted small">{{ $user->name }}</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.user-details.edit', $user) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </a>
            <a href="{{ route('admin.user-details.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Full Name</div>
                    <div class="fw-semibold">{{ $user->name }}</div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Status</div>
                    <div>
                        @php
                            $isPending = !empty($user->pending_status) && ((string) ($user->verified ?? '')) !== 'true' && empty($user->email_verified_at);
                            $status = strtolower((string) ($user->status ?? ''));
                            $statusLabel = $isPending ? 'Pending' : ($status === '' ? '-' : ucfirst($status));
                            $statusBadge = $isPending
                                ? 'text-bg-warning'
                                : match ($status) {
                                    'active' => 'text-bg-success',
                                    default => 'text-bg-secondary',
                                };
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Email</div>
                    <div class="fw-semibold">{{ $user->email }}</div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Mobile</div>
                    <div class="fw-semibold font-monospace">{{ $user->mobile }}</div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Business Name</div>
                    <div class="fw-semibold">{{ $user->BusinessName }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Address</div>
                    <div class="fw-semibold">{{ $user->address }}</div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Created Date</div>
                    <div class="fw-semibold">@dmy($user->created_on)</div>
                </div>
            </div>
        </div>
    </div>
@endsection
