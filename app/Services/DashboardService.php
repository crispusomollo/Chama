<?php

namespace App\Services;

use App\Models\Borrower;
use App\Models\Contribution;
use App\Models\ContributionSchedule;
use App\Services\SettingService;

class DashboardService
{
    /*
    |--------------------------------------------------------------------------
    | KPI SUMMARY
    |--------------------------------------------------------------------------
    */

    public function kpis()
    {
        $currentMonth = now()->format('Y-m');

        $totalMembers = Borrower::count();

        $monthlyTotal = Contribution::where('period', $currentMonth)
            ->sum('amount');

        $totalContributions = Contribution::sum('amount');

        $paidMembers = Contribution::where('period', $currentMonth)
            ->pluck('borrower_id')
            ->unique()
            ->count();

        $pendingMembers = $totalMembers - $paidMembers;

        return [
            'total_contributions' => $totalContributions,
            'monthly_total' => $monthlyTotal,
            'total_members' => $totalMembers,
            'paid_members' => $paidMembers,
            'pending_members' => $pendingMembers,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTION PERFORMANCE
    |--------------------------------------------------------------------------
    */

    public function collectionStats()
    {
        $totalMembers = Borrower::count();

        $expected = $totalMembers * SettingService::get('monthly_contribution', SettingService::monthlyContribution());

        $actual = Contribution::where('period', now()->format('Y-m'))
            ->sum('amount');

        $rate = $expected > 0 ? ($actual / $expected) * 100 : 0;

        return [
            'expected' => $expected,
            'actual' => $actual,
            'rate' => round($rate, 2),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ARREARS INTELLIGENCE
    |--------------------------------------------------------------------------
    */

    public function arrears()
    {
        $arrearsQuery = ContributionSchedule::with('borrower')
            ->where('balance', '>', 0);

        $totalBalance = $arrearsQuery->sum('balance');

        $top = $arrearsQuery
            ->orderByDesc('balance')
            ->take(10)
            ->get();

        return [
            'total_balance' => $totalBalance,
            'top' => $top,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MONTHLY CHART DATA
    |--------------------------------------------------------------------------
    */

    public function monthlyTrends()
    {
        $data = Contribution::selectRaw('period, SUM(amount) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels' => $data->pluck('period'),
            'data' => $data->pluck('total'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS CHART DATA
    |--------------------------------------------------------------------------
    */

    public function statusBreakdown()
    {
        return [
            Contribution::where('status', 'paid')->count(),
            Contribution::where('status', 'partial')->count(),
            Contribution::where('status', 'pending')->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | TOP CONTRIBUTORS
    |--------------------------------------------------------------------------
    */

    public function topContributors()
    {
        return Contribution::with('borrower')
            ->selectRaw('borrower_id, SUM(amount) as total')
            ->groupBy('borrower_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
    }
}
