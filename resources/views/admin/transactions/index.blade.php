@extends('admin.layouts.app')

@section('title', 'Transactions')

@section('content')
    @php
        $currentSort = (string) request()->query('sort', 'id');
        $currentDir = strtolower((string) request()->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $nextDir = function (string $col) use ($currentSort, $currentDir): string {
            if ($currentSort !== $col) return 'asc';
            return $currentDir === 'asc' ? 'desc' : 'asc';
        };

        $sortUrl = function (string $col) use ($nextDir): string {
            return request()->fullUrlWithQuery([
                'sort' => $col,
                'dir' => $nextDir($col),
                'page' => null,
            ]);
        };

        $sortIndicator = function (string $col) use ($currentSort, $currentDir): string {
            if ($currentSort !== $col) return '';
            return $currentDir === 'asc' ? '↑' : '↓';
        };
    @endphp

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-1">Transactions</h1>
            <div class="text-muted small">All users' transaction records</div>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-clockwise me-1"></i>Reset
        </a>
    </div>

    <div class="card card-soft rounded-4 mb-3">
        <div class="card-body p-3 p-lg-4">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-lg-4">
                    <label class="form-label mb-1">Search</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ request()->query('q') }}"
                        class="form-control"
                        placeholder="Transaction ID, user name, email, method..."
                    >
                </div>

                <div class="col-12 col-lg-3">
                    <label class="form-label mb-1">Filter by user</label>
                    <select name="user_id" class="form-select">
                        <option value="">All users</option>
                        @foreach ($userOptions as $u)
                            <option value="{{ $u->id }}" @selected((string) request()->query('user_id') === (string) $u->id)>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request()->query('date_from') }}" class="form-control">
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request()->query('date_to') }}" class="form-control">
                </div>

                <div class="col-12 col-lg-1 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-soft rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-admin align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">
                                <a class="text-decoration-none text-dark" href="{{ $sortUrl('id') }}">
                                    Transaction ID {{ $sortIndicator('id') }}
                                </a>
                            </th>
                            <th>
                                <a class="text-decoration-none text-dark" href="{{ $sortUrl('user') }}">
                                    User Name {{ $sortIndicator('user') }}
                                </a>
                            </th>
                            <th>
                                <a class="text-decoration-none text-dark" href="{{ $sortUrl('email') }}">
                                    User Email {{ $sortIndicator('email') }}
                                </a>
                            </th>
                            <th>
                                <a class="text-decoration-none text-dark" href="{{ $sortUrl('date') }}">
                                    Transaction Date {{ $sortIndicator('date') }}
                                </a>
                            </th>
                            <th class="text-end">
                                <a class="text-decoration-none text-dark" href="{{ $sortUrl('amount') }}">
                                    Amount {{ $sortIndicator('amount') }}
                                </a>
                            </th>
                            <th>
                                <a class="text-decoration-none text-dark" href="{{ $sortUrl('method') }}">
                                    Payment Method {{ $sortIndicator('method') }}
                                </a>
                            </th>
                            <th class="pe-4">
                                <a class="text-decoration-none text-dark" href="{{ $sortUrl('status') }}">
                                    Status {{ $sortIndicator('status') }}
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $t)
                            @php
                                $status = (string) ($t->bill_status ?? '');
                                $statusLabel = match ($status) {
                                    'F' => 'Final',
                                    'P' => 'Partial',
                                    'U' => 'Unpaid',
                                    default => $status === '' ? '-' : $status,
                                };

                                $statusBadge = match ($status) {
                                    'F' => 'text-bg-success',
                                    'P' => 'text-bg-warning',
                                    'U' => 'text-bg-secondary',
                                    default => 'text-bg-secondary',
                                };
                            @endphp
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $t->transaction_id }}</td>
                                <td class="fw-semibold">
                                    <div class="d-flex align-items-center gap-2">
                                        <span>{{ $t->user_name ?? '-' }}</span>

                                        @php
                                            $uid = (string) ($t->user_id ?? '');
                                            $items = $uid !== '' ? ($userTransactions[$uid] ?? []) : [];
                                        @endphp

                                        @if (!empty($items))
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Transactions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.transactions.index', ['user_id' => $uid]) }}">
                                                            View all for this user
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @foreach ($items as $it)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.transactions.index', ['q' => $it->transaction_id]) }}">
                                                                #{{ $it->transaction_id }} • {{ $it->transaction_date }} • {{ $it->amount_paid }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-muted">{{ $t->user_email ?? '-' }}</td>
                                <td class="text-muted">{{ $t->transaction_date }}</td>
                                <td class="text-end fw-semibold">{{ $t->amount_paid }}</td>
                                <td>{{ $t->payment_method }}</td>
                                <td class="pe-4">
                                    <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($transactions->hasPages())
            <div class="card-footer bg-white border-0 px-4 pb-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection
