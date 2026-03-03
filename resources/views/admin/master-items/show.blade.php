@extends('admin.layouts.app')

@section('title', 'View Item')

@section('content')
    @php $token = \App\Models\ItemMaster::idToToken((int) $item->ID); @endphp

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h4 mb-0">View Item</h1>
            <div class="text-muted small">Item Master</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.master-items.index') }}">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.master-items.edit', ['token' => $token]) }}">
                <i class="bi bi-pencil-square me-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="text-muted small">Item Name</div>
                    <div class="fw-semibold">{{ $item->ItemName }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Status</div>
                    <div>
                        <span class="badge {{ $item->Status === 'Active' ? 'text-bg-success' : 'text-bg-danger' }}">
                            {{ $item->Status }}
                        </span>
                    </div>
                </div>

                <div class="col-12">
                    <div class="text-muted small">Description</div>
                    <div class="fw-semibold">{{ $item->Description ?: '—' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Created On</div>
                    <div class="fw-semibold">@dmy($item->Created_on) <span class="text-muted">(@timehm($item->Created_on))</span></div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Updated On</div>
                    <div class="fw-semibold">@dmy($item->updated_on) <span class="text-muted">(@timehm($item->updated_on))</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
