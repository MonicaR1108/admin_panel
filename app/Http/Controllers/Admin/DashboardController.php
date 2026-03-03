<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ApplicationUser;
use App\Models\ItemMaster;
use App\Models\PublicUser;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $deviceSummary = ApplicationUser::query()
            ->where('visit_date', $today)
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->pluck('total', 'device_type');

        $todaysActiveUsers = (int) (ApplicationUser::query()
            ->where('visit_date', $today)
            ->selectRaw("COUNT(DISTINCT CONCAT(ip_address, '|', user_agent)) as c")
            ->value('c') ?? 0);

        return view('admin.dashboard', [
            'itemsCount' => ItemMaster::query()->count(),
            'adminsCount' => Admin::query()->count(),
            'totalApplicationUsers' => PublicUser::query()->count(),
            'todaysActiveUsers' => $todaysActiveUsers,
            'deviceSummary' => $deviceSummary,
        ]);
    }
}
