<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Borrower;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOAN APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
    $query = Loan::with('borrower');

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */
    if ($request->search) {
        $query->whereHas('borrower', function ($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MAIN LIST (optional general table if you still need it)
    |--------------------------------------------------------------------------
    */
    $loans = $query->latest()->paginate(10);

    /*
    |--------------------------------------------------------------------------
    | 1. APPLICATION QUEUE (SYSTEM OUTPUT)
    |--------------------------------------------------------------------------
    | Before treasurer decision
    */
    $applications = Loan::with('borrower')
        ->whereIn('application_status', [
            'pending',
            'under_review',
            'auto_approved',
            'auto_rejected',
        ])
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 2. REVIEW QUEUE (TREASURER STAGE)
    |--------------------------------------------------------------------------
    | After system decision but before disbursement
    */
    $reviewQueue = Loan::with('borrower')
        ->whereIn('application_status', [
            'approved',
            'rejected',
        ])
        ->where('loan_status', 'not_disbursed')
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 3. ACTIVE LOANS (DISBURSED)
    |--------------------------------------------------------------------------
    */
    $activeLoans = Loan::with('borrower')
        ->whereIn('loan_status', [
            'active',
            'ongoing',
            'overdue',
        ])
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 4. OVERDUE LOANS
    |--------------------------------------------------------------------------
    */
    $overdueLoans = Loan::with('borrower')
        ->where('loan_status', 'overdue')
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 5. COMPLETED LOANS
    |--------------------------------------------------------------------------
    */
    $completedLoans = Loan::with('borrower')
        ->where('loan_status', 'completed')
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */
    $totalLoans = Loan::count();

    $pendingApplications = Loan::whereIn('application_status', [
        'pending',
        'under_review',
        'auto_approved',
        'auto_rejected',
    ])->count();

    $reviewCount = Loan::whereIn('application_status', [
        'approved',
        'rejected',
    ])->where('loan_status', 'not_disbursed')->count();

    $activeLoansCount = Loan::whereIn('loan_status', [
        'active',
        'ongoing',
    ])->count();

    $overdueLoansCount = Loan::where('loan_status', 'overdue')->count();

    $completedLoansCount = Loan::where('loan_status', 'completed')->count();

    $totalDisbursed = Loan::whereNotNull('disbursement_date')->sum('amount');

    $borrowers = Borrower::orderBy('full_name')->get();

    return view('loans.index', compact(
        'loans',
        'applications',
        'reviewQueue',
        'activeLoans',
        'overdueLoans',
        'completedLoans',

        'borrowers',

        'totalLoans',
        'pendingApplications',
        'reviewCount',
        'activeLoansCount',
        'overdueLoansCount',
        'completedLoansCount',
        'totalDisbursed',
      ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE LOAN APPLICATION
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'borrower_id' => 'required|exists:borrowers,id',
            'amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FINANCIAL CALCULATIONS
        |--------------------------------------------------------------------------
        */

        $principal = $request->amount;

        $interestAmount =
            ($principal * $request->interest_rate) / 100;

        $totalPayable =
            $principal + $interestAmount;

        $monthlyInstallment =
            $totalPayable / $request->duration_months;

        /*
        |--------------------------------------------------------------------------
        | CREDIT ENGINE
        |--------------------------------------------------------------------------
        */

        $creditScore = $this->generateCreditScore(
            $request->borrower_id
        );

        /*
        |--------------------------------------------------------------------------
        | SYSTEM DECISION
        |--------------------------------------------------------------------------
        */

        $recommendation = 'under_review';

        if ($creditScore >= 80) {

            $recommendation = 'auto_approved';

        } elseif ($creditScore < 40) {

            $recommendation = 'auto_rejected';
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE APPLICATION
        |--------------------------------------------------------------------------
        */

        Loan::create([
              'borrower_id' => $request->borrower_id,
              'amount' => $principal,
              'principal_amount' => $principal,

              'interest_rate' => $request->interest_rate,
              'interest_amount' => $interestAmount,

              'duration_months' => $request->duration_months,
              'loan_date' => now(),
              //'due_date' => now()->addMonths($request->duration_months),
              'due_date' => now()->addMonths((int) $request->duration_months),

              'total_payable' => $totalPayable,
              'monthly_installment' => $monthlyInstallment,
              'balance' => $totalPayable,

              'credit_score' => $creditScore,
              'system_recommendation' => $recommendation,

            // lifecycle
              'application_status' => 'pending',
              'loan_status' => 'not_disbursed',
           ]);

        return redirect()
            ->route('loans.index')
            ->with(

                'success',
                'Loan application submitted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TREASURER APPROVAL
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);

        $loan->update([

            'application_status' => 'approved',
        ]);

        return back()->with(

            'success',
            'Loan approved successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TREASURER REJECTION
    |--------------------------------------------------------------------------
    */

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);

        $loan->update([

            'application_status' => 'rejected',
        ]);

        return back()->with(

            'success',
            'Loan rejected successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISBURSE LOAN
    |--------------------------------------------------------------------------
    */

    public function disburse($id)
    {
        $loan = Loan::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | ONLY APPROVED LOANS
        |--------------------------------------------------------------------------
        */

        if ($loan->application_status !== 'approved') {

            return back()->with(

                'error',
                'Loan must be approved first.'
            );
        }

        $loan->update([

            'loan_status' => 'active',

            'disbursement_date' => now(),
        ]);

        return back()->with(

            'success',
            'Loan disbursed successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE LOAN
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        $loan->delete();

        return back()->with(

            'success',
            'Loan deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPLE CREDIT ENGINE
    |--------------------------------------------------------------------------
    */

    private function generateCreditScore($borrowerId)
    {
        $borrower = Borrower::findOrFail($borrowerId);

        /*
        |--------------------------------------------------------------------------
        | NEW BORROWERS
        |--------------------------------------------------------------------------
        */

        $previousLoans = Loan::where(

            'borrower_id',
            $borrowerId

        )->count();

        if ($previousLoans == 0) {

            return rand(50, 75);
        }

        /*
        |--------------------------------------------------------------------------
        | EXISTING BORROWERS
        |--------------------------------------------------------------------------
        */

        $completedLoans = Loan::where(

            'borrower_id',
            $borrowerId

        )
        ->where('loan_status', 'completed')
        ->count();

        $overdueLoans = Loan::where(

            'borrower_id',
            $borrowerId

        )
        ->where('loan_status', 'overdue')
        ->count();

        $score = 60;

        $score += ($completedLoans * 10);

        $score -= ($overdueLoans * 15);

        /*
        |--------------------------------------------------------------------------
        | LIMITS
        |--------------------------------------------------------------------------
        */

        return max(1, min(100, $score));
    }
}