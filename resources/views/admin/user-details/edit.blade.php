@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-1">Edit User</h1>
            <div class="text-muted small">{{ $user->name }}</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.user-details.show', $user) }}" class="btn btn-sm btn-success">
                <i class="bi bi-eye me-1"></i>View
            </a>
            <a href="{{ route('admin.user-details.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="{{ route('admin.user-details.update', $user) }}" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->name) }}" class="form-control @error('full_name') is-invalid @enderror" required>
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" class="form-control @error('mobile') is-invalid @enderror" required>
                        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Business Name <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" value="{{ old('business_name', $user->BusinessName) }}" class="form-control @error('business_name') is-invalid @enderror" required>
                        @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror" placeholder="Optional">
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control @error('username') is-invalid @enderror" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank to keep current password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter new password">
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.user-details.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check2-circle me-1"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
