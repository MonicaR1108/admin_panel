@extends('admin.layouts.app')

@section('title', 'Pending Verification')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-1">Pending Verification</h1>
            <div class="text-muted small">Verification link will be sent to: <span class="fw-semibold">{{ $user->email }}</span></div>
        </div>
        <a href="{{ route('admin.user-details.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card card-soft rounded-4">
        <div class="card-body p-4 p-lg-5">
            <div class="mb-3">
                <div class="fw-semibold">User</div>
                <div class="text-muted">{{ $user->name }}</div>
            </div>

            <p class="text-muted mb-4">
                The account is created and is currently pending. The user must click the verification link and then create a password to activate the account.
                You can resend the verification link if needed.
            </p>

            <form method="POST" action="{{ route('admin.user-details.resend-verification-link', $user) }}" class="d-inline">
                @csrf
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-envelope me-2"></i>Resend Verification Link
                </button>
            </form>

            <form method="POST" action="{{ route('admin.user-details.destroy', $user) }}" class="d-inline ms-2" onsubmit="return confirm('Are you sure you want to delete this user?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger" type="submit">
                    <i class="bi bi-trash me-2"></i>Delete User
                </button>
            </form>
        </div>
    </div>
@endsection
