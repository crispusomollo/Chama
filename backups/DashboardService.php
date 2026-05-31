<?php

namespace App\Services;

use App\Models\Borrower;
use App\Models\Contribution;
use App\Models\ContributionSchedule;
use Carbon\Carbon;

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

        $monthlyContributions = Contribution::where('period', $currentMonth);

        $monthlyTotal = $monthlyContributions->sum('amount');

        $paidMembers = $monthlyContributions->pluck('borrower_id')->unique()->count();

        $pendingMembers = $totalMembers - $paidMembers;

        $totalContributions = Contribution::sum('amount');

        return [
            'totalMembers' => $totalMembers,
            'monthlyTotal' => $monthlyTotal,
            'paidMembers' => $paidMembers,
            'pendingMembers' => $pendingMembers,
            'totalContributions' => $totalContributions,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTION EFFICIENCY
    |--------------------------------------------------------------------------
    */

    public function collectionStats()
    {
        $totalMembers = Borrower::count();

        $expected = $totalMembers * SettingService::get('monthly_contribution', 1300);

        $actual = Contribution::where('period', now()->format('Y-m'))->sum('amount');

        $rate = $expected > 0
            ? ($actual / $expected) * 100
            : 0;

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
        $arrears = ContributionSchedule::with('borrower')
            ->where('balance', '>', 0)
            ->get();

        return [
            'totalArrears' => $arrears->sum('balance'),
            'overdueMembers' => $arrears->pluck('borrower_id')->unique()->count(),
            'overdueList' => $arrears->take(10),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CHART DATA
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
            'values' => $data->pluck('total'),
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
