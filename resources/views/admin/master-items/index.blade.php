@extends('admin.layouts.app')

@section('title', 'Master Item')

@section('content')
    @php
        $sort = $sort ?? request('sort', '');
        $dir = $dir ?? request('dir', 'desc');

        $toggleDir = fn (string $col) => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
        $arrow = function (string $col) use ($sort, $dir) {
            if ($sort !== $col) return '';
            return $dir === 'asc' ? ' ↑' : ' ↓';
        };
        $sortLink = fn () => route('admin.master-items.index', ['sort' => 'id', 'dir' => $toggleDir('id')]);
    @endphp

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h1 class="h4 mb-0">Master Items</h1>
        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#addMasterItem" aria-expanded="false" aria-controls="addMasterItem">
            <i class="bi bi-plus-circle me-2"></i>Add Item
        </button>
    </div>

    <div class="collapse mb-4" id="addMasterItem">
        <div class="card card-soft rounded-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.master-items.store') }}" class="row g-3 align-items-end" novalidate>
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Item Name</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Description</label>
                        <input
                            type="text"
                            name="description"
                            value="{{ old('description') }}"
                            class="form-control @error('description') is-invalid @enderror"
                        >
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                            <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check2-circle me-2"></i>Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-admin align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 text-center">
                                <a class="text-decoration-none text-reset" href="{{ $sortLink() }}">
                                    S.No{!! $arrow('id') !!}
                                </a>
                            </th>
                            <th>Item Name</th>
                            <th>Description</th>
                            @if (!empty($hasCategory))
                                <th>Category</th>
                            @endif
                            @if (!empty($hasPrice))
                                <th class="text-center">Price</th>
                            @endif
                            <th>Status</th>
                            <th>Created On</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            @php $token = \App\Models\ItemMaster::idToToken((int) $item->ID); @endphp
                            <tr>
                                <td class="ps-4 text-center">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold text-nowrap">{{ $item->ItemName }}</td>
                                <td class="text-muted">{{ $item->Description }}</td>
                                @if (!empty($hasCategory))
                                    <td>{{ $item->Category ?? $item->category }}</td>
                                @endif
                                @if (!empty($hasPrice))
                                    <td class="text-center">{{ $item->Price ?? $item->price }}</td>
                                @endif
                                <td>
                                    <form method="POST" action="{{ route('admin.master-items.status', ['token' => $token]) }}">
                                        @csrf
                                        @method('PUT')
                                        <select
                                            name="status"
                                            class="status-select form-select form-select-sm {{ $item->Status === 'Active' ? 'status-active' : 'status-inactive' }}"
                                            onchange="this.form.submit()"
                                        >
                                            <option value="Active" @selected($item->Status === 'Active')>Active</option>
                                            <option value="Inactive" @selected($item->Status === 'Inactive')>Inactive</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-muted">@dmy($item->Created_on)</td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-1" aria-label="Row actions">
                                        <a class="action-btn action-view" href="{{ route('admin.master-items.show', ['token' => $token]) }}" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a class="action-btn action-edit" href="{{ route('admin.master-items.edit', ['token' => $token]) }}" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.master-items.destroy', ['token' => $token]) }}" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn action-delete" type="submit" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                @php $colspan = 6 + (!empty($hasCategory) ? 1 : 0) + (!empty($hasPrice) ? 1 : 0); @endphp
                                <td colspan="{{ $colspan }}" class="text-center text-muted py-4">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($items->hasPages())
            <div class="card-footer bg-white border-0 px-4 pb-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>
@endsection
