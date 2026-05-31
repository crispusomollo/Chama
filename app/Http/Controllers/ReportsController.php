<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Borrower;
use App\Models\Loan;

class ReportsController extends Controller
{
    public function index()
    {
        // MEMBERS
    $totalMembers = Borrower::count();

    // LOANS
    $totalLoans = Loan::count();
    $activeLoans = Loan::where('status', 'active')->count();
    $paidLoans = Loan::where('status', 'paid')->count();
    $totalLoanValue = Loan::sum('amount');

    // 💸 CONTRIBUTIONS (NEW CORE METRICS)
    $totalContributions = Contribution::sum('amount');

    $thisMonthContributions = Contribution::whereMonth(
        'contribution_date',
        now()->month
    )->sum('amount');

    $lastMonthContributions = Contribution::whereMonth(
        'contribution_date',
        now()->subMonth()->month
    )->sum('amount');

    $averageContribution = Contribution::avg('amount');

    // 👇 CHAMA FINANCIAL HEALTH
    $loanToSavingsRatio = $totalContributions > 0
        ? $totalLoanValue / $totalContributions
        : 0;

    return view('reports.index', compact(
        'totalMembers',
        'totalLoans',
        'activeLoans',
        'paidLoans',
        'totalLoanValue',
        'totalContributions',
        'thisMonthContributions',
        'lastMonthContributions',
        'averageContribution',
        'loanToSavingsRatio'
    ));

    }
}
