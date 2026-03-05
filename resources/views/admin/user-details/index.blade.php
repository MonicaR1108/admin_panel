@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex flex-column gap-2">
            <h1 class="h4 mb-0">User Details</h1>
            <div class="btn-group btn-group-sm" role="group" aria-label="Verification filter">
                <a class="btn {{ ($verifiedFilter ?? 'all') === 'all' ? 'btn-success' : 'btn-outline-success' }}"
                    href="{{ route('admin.user-details.index', ['verified' => 'all']) }}">
                    All
                </a>
                <a class="btn {{ ($verifiedFilter ?? 'all') === 'verified' ? 'btn-success' : 'btn-outline-success' }}"
                    href="{{ route('admin.user-details.index', ['verified' => 'verified']) }}">
                    Verified
                </a>
                <a class="btn {{ ($verifiedFilter ?? 'all') === 'pending' ? 'btn-success' : 'btn-outline-success' }}"
                    href="{{ route('admin.user-details.index', ['verified' => 'pending']) }}">
                    Pending Verification
                </a>
            </div>
        </div>
        <a href="{{ route('admin.user-details.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add User
        </a>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-admin align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 text-center">S.No</th>
                            <th>Full Name</th>
                            <th>Business Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Verification</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th class="pe-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                $isVerified = ((string) $user->verified === 'true') || (!empty($user->email_verified_at));
                            @endphp
                            <tr>
                                <td class="ps-4 text-center">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td class="text-muted cell-truncate" title="{{ $user->BusinessName }}">{{ $user->BusinessName }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="font-monospace">{{ $user->mobile }}</td>
                                <td>
                                    @if ($isVerified)
                                        <span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                    @else
                                        <span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Not Verified</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $user->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td class="text-muted">@dmy($user->created_on)</td>
                                <td class="pe-4 text-center">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        @if ($isVerified)
                                            <a class="action-btn action-view" href="{{ route('admin.user-details.show', $user) }}" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a class="action-btn action-edit" href="{{ route('admin.user-details.edit', $user) }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a class="action-btn action-edit" href="{{ route('admin.user-details.reset-password.form', $user) }}" title="Reset Password">
                                                <i class="bi bi-key"></i>
                                            </a>
                                        @else
                                            <form method="POST" action="{{ route('admin.user-details.resend-verification-link', $user) }}" class="d-inline">
                                                @csrf
                                                <button class="action-btn action-edit" type="submit" title="Resend verification link" aria-label="Resend verification link">
                                                    <i class="bi bi-envelope"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.user-details.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn action-delete" type="submit" title="Delete" aria-label="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
            <div class="card-footer bg-white border-0 px-4 pb-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
