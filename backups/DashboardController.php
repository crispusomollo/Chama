<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Borrower;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $currentMonth = Carbon::now()->format('Y-m');

    $totalContributions = Contribution::where('period', $currentMonth)
        ->sum('amount');

    $paidCount = Contribution::where('period', $currentMonth)
        ->where('status', 'paid')
        ->count();

    $expectedCount = Borrower::count();
    $pendingCount = $expectedCount - $paidCount;

    $paidMembers = Contribution::with('borrower')
        ->where('period', $currentMonth)
        ->where('status', 'paid')
	->get();
    $pendingMembers = Borrower::whereDoesntHave('contributions', function ($q) use ($currentMonth) {
        $q->where('period', $currentMonth)
          ->where('status', 'paid');
    })->get();

    return view('dashboard', compact(
        'totalContributions',
        'paidCount',
        'pendingCount',
        'paidMembers',
        'pendingMembers',
        'currentMonth'
    ));}
}
