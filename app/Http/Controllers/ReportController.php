<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Loan;
use App\Models\Contribution;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | MEMBER KPIs
        |--------------------------------------------------------------------------
        */
        $totalMembers = Borrower::count();

        /*
        |--------------------------------------------------------------------------
        | LOAN KPIs
        |--------------------------------------------------------------------------
        */
        $totalLoans = Loan::count();

        $activeLoans = Loan::where('status', 'active')->count();

        $paidLoans = Loan::where('status', 'paid')->count();

        $overdueLoans = Loan::where('status', 'overdue')->count();

        $totalLoanAmount = Loan::sum('amount');

        /*
        |--------------------------------------------------------------------------
        | CONTRIBUTION KPIs
        |--------------------------------------------------------------------------
        */
        $totalContributions = Contribution::sum('amount');

        $thisMonthContributions = Contribution::whereMonth(
            'created_at',
            Carbon::now()->month
        )->sum('amount');

        $lastMonthContributions = Contribution::whereMonth(
            'created_at',
            Carbon::now()->subMonth()->month
        )->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | RECENT CONTRIBUTIONS
        |--------------------------------------------------------------------------
        */
        $recentContributions = Contribution::latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('reports.index', compact(
            'totalMembers',
            'totalLoans',
            'activeLoans',
            'paidLoans',
            'overdueLoans',
            'totalLoanAmount',
            'totalContributions',
            'thisMonthContributions',
            'lastMonthContributions',
            'recentContributions'
        ));
    }
}
