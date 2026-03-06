@extends('admin.layouts.app')

@section('title', 'Add User')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Add User</h1>
        <a href="{{ route('admin.user-details.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="{{ route('admin.user-details.store') }}" novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror" required>
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" placeholder="Optional">
                        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Business Name <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}" class="form-control @error('business_name') is-invalid @enderror" required>
                        @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" placeholder="Optional">
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Applied after the user verifies and creates a password.</div>
                    </div>

                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            After you save, the system emails the user a verification link. Once verified, the user will create their own password and the account becomes active.
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.user-details.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check2-circle me-1"></i>Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
