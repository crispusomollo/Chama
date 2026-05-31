<?php

namespace App\Http\Controllers;

use App\Models\ShareLedger;
use App\Models\InsuranceLedger;

class LedgerController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | SHARES SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalShares = ShareLedger::sum('amount');
        $shareTransactions = ShareLedger::count();

        /*
        |--------------------------------------------------------------------------
        | INSURANCE SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalInsurance = InsuranceLedger::sum('amount');
        $insuranceTransactions = InsuranceLedger::count();

        /*
        |--------------------------------------------------------------------------
        | OVERALL SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalLedgerValue = $totalShares + $totalInsurance;

        return view('ledger.index', compact(
            'totalShares',
            'shareTransactions',
            'totalInsurance',
            'insuranceTransactions',
            'totalLedgerValue'
        ));
    }
}
