<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $userId = trim((string) $request->query('user_id', ''));
        $dateFrom = trim((string) $request->query('date_from', ''));
        $dateTo = trim((string) $request->query('date_to', ''));

        $sort = (string) $request->query('sort', 'id');
        $dir = strtolower((string) $request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $sortMap = [
            'id' => 'p.ID',
            'user' => 'u.name',
            'email' => 'u.email',
            'date' => 'p.TransactionDate',
            'method' => 'p.ModeOfPayment',
            'status' => 'b.bill_status',
        ];

        $sortColumn = $sortMap[$sort] ?? $sortMap['id'];

        $query = DB::table('payment as p')
            ->leftJoin('bills as b', 'b.ID', '=', 'p.bill_id')
            ->leftJoin('users as u', 'u.ID', '=', 'b.UserID')
            ->select([
                'p.ID as transaction_id',
                'p.bill_id',
                'p.AmountPaid as amount_paid',
                'p.TransactionDate as transaction_date',
                'p.ModeOfPayment as payment_method',
                'b.bill_status',
                'u.ID as user_id',
                'u.name as user_name',
                'u.email as user_email',
            ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                if (ctype_digit($search)) {
                    $q->orWhere('p.ID', (int) $search);
                    $q->orWhere('p.bill_id', (int) $search);
                }

                $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search) . '%';

                $q->orWhere('u.name', 'like', $like)
                    ->orWhere('u.email', 'like', $like)
                    ->orWhere('p.ModeOfPayment', 'like', $like)
                    ->orWhere('p.TransactionDate', 'like', $like);
            });
        }

        if ($userId !== '') {
            $query->where('u.ID', $userId);
        }

        if ($dateFrom !== '') {
            $query->where('p.TransactionDate', '>=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo !== '') {
            $query->where('p.TransactionDate', '<=', $dateTo . ' 23:59:59');
        }

        if ($sort === 'amount') {
            $query->orderByRaw('CAST(p.AmountPaid AS DECIMAL(12,2)) ' . $dir)->orderByDesc('p.ID');
        } else {
            $query->orderBy($sortColumn, $dir)->orderByDesc('p.ID');
        }

        $transactions = $query->paginate(20)->withQueryString();

        $userTransactions = [];
        $pageUserIds = collect($transactions->items())
            ->pluck('user_id')
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->unique()
            ->values()
            ->all();

        if (! empty($pageUserIds)) {
            $rows = DB::table('payment as p')
                ->leftJoin('bills as b', 'b.ID', '=', 'p.bill_id')
                ->select([
                    'p.ID as transaction_id',
                    'p.AmountPaid as amount_paid',
                    'p.TransactionDate as transaction_date',
                    'p.ModeOfPayment as payment_method',
                    'b.bill_status',
                    'b.UserID as user_id',
                ])
                ->whereIn('b.UserID', $pageUserIds)
                ->orderByDesc('p.ID')
                ->get();

            foreach ($rows as $row) {
                $uid = (string) ($row->user_id ?? '');
                if ($uid === '') continue;

                $userTransactions[$uid] ??= [];
                if (count($userTransactions[$uid]) >= 10) continue;

                $userTransactions[$uid][] = $row;
            }
        }

        $userOptions = DB::table('payment as p')
            ->leftJoin('bills as b', 'b.ID', '=', 'p.bill_id')
            ->leftJoin('users as u', 'u.ID', '=', 'b.UserID')
            ->select(['u.ID as id', 'u.name', 'u.email'])
            ->whereNotNull('u.ID')
            ->distinct()
            ->orderBy('u.name')
            ->limit(500)
            ->get();

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'userOptions' => $userOptions,
            'userTransactions' => $userTransactions,
        ]);
    }
}
