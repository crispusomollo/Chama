<?php

namespace App\Http\Controllers;

use App\Models\ShareLedger;

class ShareLedgerController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | LEDGER ENTRIES
        |--------------------------------------------------------------------------
        */

        $entries = ShareLedger::with([
                'borrower',
                'contribution'
            ])
            ->latest()
            ->paginate(20);

        /*
        |--------------------------------------------------------------------------
        | KPI CARDS
        |--------------------------------------------------------------------------
        */

        $totalShares = ShareLedger::sum('amount');

        $totalMembers = ShareLedger::distinct('borrower_id')
            ->count('borrower_id');

        $totalTransactions = ShareLedger::count();

        return view(
            'shares.index',
            compact(
                'entries',
                'totalShares',
                'totalMembers',
                'totalTransactions'
            )
        );
    }
}
