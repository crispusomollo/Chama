<?php

namespace App\Http\Controllers;

use App\Models\InsuranceLedger;

class InsuranceLedgerController extends Controller
{
    public function index()
    {
        $ledger = InsuranceLedger::with([
                'borrower',
                'contribution'
            ])
            ->latest()
            ->paginate(20);

        /*
        |------------------------------------------------------------------
        | KPI
        |------------------------------------------------------------------
        */

        $totalInsurance = InsuranceLedger::sum('amount');

        $totalTransactions = InsuranceLedger::count();

        $totalMembers = InsuranceLedger::distinct(
            'borrower_id'
        )->count();

        return view(
            'insurance.index',
            compact(
                'ledger',
                'totalInsurance',
                'totalTransactions',
                'totalMembers'
            )
        );
    }
}
