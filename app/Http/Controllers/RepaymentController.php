<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Http\Request;

class RepaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREATE REPAYMENT FORM
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $loan = Loan::with('borrower')
            ->findOrFail($request->loan_id);

        /*
        |--------------------------------------------------------------------------
        | ONLY REPAYABLE LOANS
        |--------------------------------------------------------------------------
        */

        if (!$loan->canRepay()) {

            return redirect()
                ->route('loans.index')
                ->with(
                    'error',
                    'This loan cannot receive repayments.'
                );
        }

        return view('repayments.create', compact('loan'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE REPAYMENT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'loan_id' => 'required|exists:loans,id',

            'amount' => 'required|numeric|min:1',

            'payment_method' => 'required|string',

            'payment_date' => 'required|date',

            'notes' => 'nullable|string',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        /*
        |--------------------------------------------------------------------------
        | VALIDATE LOAN STATUS
        |--------------------------------------------------------------------------
        */

        if (!$loan->canRepay()) {

            return back()->with(
                'error',
                'This loan is not eligible for repayment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PREVENT OVERPAYMENT
        |--------------------------------------------------------------------------
        */

        if ($request->amount > $loan->balance) {

            return back()->with(
                'error',
                'Repayment amount exceeds outstanding loan balance.'
            );
        }


       if ($loan->isCompleted()) {

            return back()->with(

            'error',
            'This loan has already been fully repaid.'

            );
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE REPAYMENT
        |--------------------------------------------------------------------------
        */

        //Repayment::create([

        //    'loan_id' => $loan->id,

        //    'amount' => $request->amount,

        //    'payment_date' => $request->payment_date,

        //    'notes' => $request->notes,
        //]);

        $repayment = Repayment::create([

        'loan_id' => $loan->id,

        'amount' => $request->amount,

        'payment_method' => $request->payment_method,

        'transaction_code' => $request->transaction_code,

        //'status' => 'completed',
        'status' => 'pending',

        //'payment_date' => $request->payment_date,
        'payment_date' => $request->payment_date,

        'notes' => $request->notes,

        'received_by' => auth()->id(),
        ]);


        $repayment->update([

        'status' => 'completed',

        ]);

        /*
        |--------------------------------------------------------------------------
        | AUTOMATIC RECONCILIATION
        |--------------------------------------------------------------------------
        */

        $loan->recalculateLoan();

        return redirect()
            ->route('repayments.show', $loan->id)
            ->with(
                'success',
                'Repayment recorded successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | REPAYMENT LEDGER
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $loan = Loan::with([

            'borrower',
            'repayments',

        ])->findOrFail($id);

        $repayments = Repayment::where(
                'loan_id',
                $loan->id
            )
            ->latest()
            ->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | FINANCIALS
        |--------------------------------------------------------------------------
        */

        //$totalPaid = $loan->totalRepaid();

        //$outstanding = $loan->balance;

        $totalPaid = $loan->totalRepaid();

        $outstanding = $loan->balance;

        /*
        |--------------------------------------------------------------------------
        | REPAYMENT ANALYTICS
        |--------------------------------------------------------------------------
        */

        $totalTransactions = $repayments->total();

        $latestPayment = Repayment::where(
            'loan_id',
        $loan->id
        )
        ->latest('payment_date')
        ->first();

        $completionRate = 0;

        if ($loan->total_payable > 0) {

        $completionRate =
            ($totalPaid / $loan->total_payable) * 100;
        }

        return view('repayments.show', compact(

            'loan',
            'repayments',
            'totalPaid',
            'outstanding',
            'totalTransactions',
            'latestPayment',
            'completionRate'
        ));
    }

    public function receipt($id)
    {
        $repayment = Repayment::with([

            'loan.borrower',

        ])->findOrFail($id);

        return view(

            'repayments.receipt',

        compact('repayment')
      );
   }
}