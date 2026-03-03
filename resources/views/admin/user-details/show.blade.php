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
                        <span class="badge {{ $user->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                            {{ $user->status }}
                        </span>
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
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Username</div>
                    <div class="fw-semibold">{{ $user->username }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Address</div>
                    <div class="fw-semibold">{{ $user->address }}</div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Created Date</div>
                    <div class="fw-semibold">@dmy($user->created_on)</div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="text-muted small">Created By</div>
                    <div class="fw-semibold">{{ $user->created_by }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
