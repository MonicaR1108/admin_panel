@extends('admin.layouts.app')

@section('title', 'Transactions')

@section('content')
    @php
        $isUserFiltered = (string) request()->query('user_id', '') !== '';

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
            <div class="text-muted small">
                @if (!empty($selectedUser))
                    Transactions for {{ $selectedUser->name }} ({{ $selectedUser->email }})
                @elseif ((string) request()->query('user_id', '') !== '')
                    Transactions for selected user
                @else
                    Users with transaction records
                @endif
            </div>
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
                @if ($isUserFiltered)
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
                                        @if (!empty($t->user_id))
                                            <a class="text-decoration-none text-dark" href="{{ route('admin.transactions.index', ['user_id' => $t->user_id]) }}">
                                                {{ $t->user_name ?? '-' }}
                                            </a>
                                        @else
                                            <span>{{ $t->user_name ?? '-' }}</span>
                                        @endif

                                        {{-- Dropdown removed (click user name to filter instead)
                                            $uid = (string) ($t->user_id ?? '');
                                            $items = [];
                                        @endphp

                                        @if (false)
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
                                        --}}
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
                @else
                    <table class="table table-hover table-sm table-admin align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 70px;">S.NO</th>
                                <th>User</th>
                                <th class="text-end" style="width: 140px;">Transactions</th>
                                <th style="width: 210px;">Last Transaction</th>
                                <th class="text-end" style="width: 160px;">Total Paid</th>
                                <th class="pe-4 text-end" style="width: 90px;">Expand</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $u)
                                @php
                                    $uid = (string) $u->id;
                                    $collapseId = 'userTxns-' . $uid;
                                    $items = $uid !== '' ? ($userTransactions[$uid] ?? []) : [];

                                    $rowNo = $loop->iteration;
                                    if (method_exists($users, 'currentPage')) {
                                        $rowNo = ($users->currentPage() - 1) * $users->perPage() + $loop->iteration;
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4">{{ $rowNo }}</td>
                                    <td class="fw-semibold">
                                        <a class="text-decoration-none text-dark" href="{{ route('admin.transactions.index', ['user_id' => $uid]) }}">
                                            {{ $u->name ?? '-' }}
                                        </a>
                                        <div class="small text-muted">{{ $u->email ?? '-' }}</div>
                                    </td>
                                    <td class="text-end fw-semibold">{{ $u->txn_count ?? 0 }}</td>
                                    <td class="text-muted">{{ $u->last_transaction_date ?? '-' }}</td>
                                    <td class="text-end fw-semibold">{{ $u->total_paid ?? '0.00' }}</td>
                                    <td class="pe-4 text-end">
                                        <button
                                            class="btn btn-sm btn-outline-secondary"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#{{ $collapseId }}"
                                            aria-expanded="false"
                                            aria-controls="{{ $collapseId }}"
                                        >
                                            <i class="bi bi-chevron-down"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="6" class="p-0 border-top-0">
                                        <div class="collapse" id="{{ $collapseId }}">
                                            <div class="p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="small text-muted">Latest transactions (max 10)</div>
                                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.transactions.index', ['user_id' => $uid]) }}">
                                                        View all
                                                    </a>
                                                </div>

                                                @if (empty($items))
                                                    <div class="text-muted small">No transactions found for this user (with current filters).</div>
                                                @else
                                                    <div class="table-responsive">
                                                        <table class="table table-sm align-middle mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 140px;">Transaction ID</th>
                                                                    <th style="width: 210px;">Date</th>
                                                                    <th class="text-end" style="width: 140px;">Amount</th>
                                                                    <th style="width: 200px;">Method</th>
                                                                    <th style="width: 120px;">Status</th>
                                                                    <th class="text-end" style="width: 90px;">Open</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($items as $it)
                                                                    @php
                                                                        $st = (string) ($it->bill_status ?? '');
                                                                        $stLabel = match ($st) {
                                                                            'F' => 'Final',
                                                                            'P' => 'Partial',
                                                                            'U' => 'Unpaid',
                                                                            default => $st === '' ? '-' : $st,
                                                                        };

                                                                        $stBadge = match ($st) {
                                                                            'F' => 'text-bg-success',
                                                                            'P' => 'text-bg-warning',
                                                                            'U' => 'text-bg-secondary',
                                                                            default => 'text-bg-secondary',
                                                                        };
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="fw-semibold">{{ $it->transaction_id }}</td>
                                                                        <td class="text-muted">{{ $it->transaction_date }}</td>
                                                                        <td class="text-end fw-semibold">{{ $it->amount_paid }}</td>
                                                                        <td>{{ $it->payment_method }}</td>
                                                                        <td><span class="badge {{ $stBadge }}">{{ $stLabel }}</span></td>
                                                                        <td class="text-end">
                                                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.transactions.index', ['user_id' => $uid, 'q' => $it->transaction_id]) }}">
                                                                                Open
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        @if ($isUserFiltered && $transactions?->hasPages())
            <div class="card-footer bg-white border-0 px-4 pb-4">
                {{ $transactions->links() }}
            </div>
        @endif

        @if (!$isUserFiltered && $users?->hasPages())
            <div class="card-footer bg-white border-0 px-4 pb-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
