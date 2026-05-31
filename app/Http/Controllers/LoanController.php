<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Borrower;
use Illuminate\Http\Request;
use App\Services\SettingService;

class LoanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOANS DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        Loan::processOverdues();

        /*
        |--------------------------------------------------------------------------
        | SEARCHABLE BASE QUERY
        |--------------------------------------------------------------------------
        */

        $baseQuery = Loan::with('borrower');

        if ($request->search) {

            $baseQuery->whereHas('borrower', function ($q) use ($request) {

                $q->where(
                    'full_name',
                    'like',
                    '%' . $request->search . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | DATASETS
        |--------------------------------------------------------------------------
        */

        $applications = (clone $baseQuery)
            ->applications()
            ->latest()
            ->get();

        $reviewQueue = (clone $baseQuery)
            ->reviewQueue()
            ->latest()
            ->get();

        $activeLoans = (clone $baseQuery)
            ->activeLoans()
            ->latest()
            ->get();

        $overdueLoans = (clone $baseQuery)
            ->overdueLoans()
            ->latest()
            ->get();

        $completedLoans = (clone $baseQuery)
            ->completedLoans()
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | KPIs
        |--------------------------------------------------------------------------
        */

        $totalLoans = Loan::count();

        $pendingApplications = Loan::applications()->count();

        $reviewCount = Loan::reviewQueue()->count();

        $activeLoansCount = Loan::activeLoans()->count();

        $overdueLoansCount = Loan::overdueLoans()->count();

        $completedLoansCount = Loan::completedLoans()->count();

        $totalDisbursed = Loan::whereNotNull(
            'disbursement_date'
        )->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | BORROWERS
        |--------------------------------------------------------------------------
        */

        $borrowers = Borrower::orderBy('full_name')->get();

        /*
        |-------------------------------------------------------------------------
        | LOAN AGING KPIs
        |-------------------------------------------------------------------------
        */
        $performingLoans = Loan::whereIn('loan_status', [

        'active',
        'ongoing',

        ])->count();

        $nonPerformingLoans = Loan::where(

        'loan_status',
        'overdue'

        )->count();

        $totalPenalties = Loan::sum('penalty_amount');

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('loans.index', compact(

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

            'performingLoans',
            'nonPerformingLoans',

            'totalPenalties',
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
        | FINANCIALS
        |--------------------------------------------------------------------------
        */

        $principal = (float) $request->amount;

        //$interestRate = (float) $request->interest_rate;

        $interestRate =
            SettingService::defaultInterestRate();

        //$rule =
        //    RuleService::current();

        //$interestRate =
        //    $rule->interest_rate;

        //$rule =
        //    RuleService::forPeriod(
        //        now()->format('Y-m')
        //   );

        $durationMonths = (int) $request->duration_months;

        $interestAmount =
            ($principal * $interestRate) / 100;

        $totalPayable =
            $principal + $interestAmount;

        $monthlyInstallment =
            $totalPayable / $durationMonths;

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
        | SYSTEM RECOMMENDATION
        |--------------------------------------------------------------------------
        */

        $recommendation = 'under_review';

        if ($creditScore >= 80) {

            $recommendation = 'auto_approved';
        }

        elseif ($creditScore < 40) {

            $recommendation = 'auto_rejected';
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE LOAN APPLICATION
        |--------------------------------------------------------------------------
        */

        Loan::create([

            'borrower_id' => $request->borrower_id,

            /*
            |--------------------------------------------------------------------------
            | LOAN DETAILS
            |--------------------------------------------------------------------------
            */

            'amount' => $principal,

            'principal_amount' => $principal,

            'interest_rate' => $interestRate,

            'interest_amount' => $interestAmount,

            'duration_months' => $durationMonths,

            'loan_date' => now(),

            'due_date' => now()
                ->addMonths($durationMonths),

            /*
            |--------------------------------------------------------------------------
            | FINANCIALS
            |--------------------------------------------------------------------------
            */

            'total_payable' => $totalPayable,

            'monthly_installment' => $monthlyInstallment,

            'balance' => $totalPayable,

            /*
            |--------------------------------------------------------------------------
            | CREDIT ENGINE
            |--------------------------------------------------------------------------
            */

            'credit_score' => $creditScore,

            'system_recommendation' => $recommendation,

            /*
            |--------------------------------------------------------------------------
            | LIFECYCLE
            |--------------------------------------------------------------------------
            */

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
    | APPROVE APPLICATION
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
    | REJECT APPLICATION
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

    /*public function disburse($id)
    {
        $loan = Loan::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
/*
        if (! $loan->canDisburse()) {

            return back()->with(
                'error',
                'Loan cannot be disbursed.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DISBURSE
        |--------------------------------------------------------------------------
        */
/*
        $loan->update([

            'loan_status' => 'active',

            'disbursement_date' => now(),
        ]);

        //Generate Schedule During Disbursement
        $loan->generateSchedule();

        return back()->with(
            'success',
            'Loan disbursed successfully.'
        );
    }
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
                'Only approved loans can be disbursed.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DISBURSE
        |--------------------------------------------------------------------------
        */

        $loan->update([

            'loan_status' => 'active',

            'disbursed_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | GENERATE SCHEDULE
        |--------------------------------------------------------------------------
        */

        $loan->generateSchedule();

        return redirect()
            ->route('loans.schedule', $loan->id)
            ->with(
                'success',
                'Loan disbursed and schedule generated successfully.'
        );
    }

    // Add Schedule Controller Method

    public function schedule($id)
    {
        $loan = Loan::with([

            'borrower',
            'schedules',

        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | AUTO GENERATE PREVIEW SCHEDULE
        |--------------------------------------------------------------------------
        */

        if ($loan->schedules->count() == 0) {

            $loan->generateSchedule();

            $loan->load('schedules');
        }

        return view('loans.schedule', compact('loan'));
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
    | CREDIT ENGINE
    |--------------------------------------------------------------------------
    */

    private function generateCreditScore($borrowerId)
    {
        /*
        |--------------------------------------------------------------------------
        | PREVIOUS LOANS
        |--------------------------------------------------------------------------
        */

        $previousLoans = Loan::where(
            'borrower_id',
            $borrowerId
        )->count();

        /*
        |--------------------------------------------------------------------------
        | NEW BORROWERS
        |--------------------------------------------------------------------------
        */

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
        ->completedLoans()
        ->count();

        $overdueLoans = Loan::where(
            'borrower_id',
            $borrowerId
        )
        ->overdueLoans()
        ->count();

        /*
        |--------------------------------------------------------------------------
        | SCORING
        |--------------------------------------------------------------------------
        */

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
