<?php

namespace App\Http\Controllers;

use App\Models\ServiceCheck;
use App\Models\TenantAccount;
use Illuminate\Contracts\View\View;

class TenantOpsDashboardController extends Controller
{
    public function index(): View
    {
        $tenants = TenantAccount::query()
            ->with(['serviceChecks' => fn ($query) => $query->limit(3)])
            ->orderByRaw("case when status = 'attention' then 0 when status = 'paused' then 1 else 2 end")
            ->orderBy('renewal_at')
            ->get();

        $tenantStatusCounts = TenantAccount::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $checkStatusCounts = ServiceCheck::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $alerts = ServiceCheck::query()
            ->whereIn('status', [ServiceCheck::STATUS_WARN, ServiceCheck::STATUS_FAIL])
            ->with('tenant')
            ->orderByDesc('checked_at')
            ->limit(6)
            ->get();

        return view('dashboard', [
            'tenants' => $tenants,
            'tenantStatuses' => TenantAccount::statuses(),
            'tenantStatusCounts' => $tenantStatusCounts,
            'checkStatusCounts' => $checkStatusCounts,
            'alerts' => $alerts,
        ]);
    }
}
