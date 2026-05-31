<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | RELATIONSHIPS
        |--------------------------------------------------------------------------
        */

        'borrower_id',

        /*
        |--------------------------------------------------------------------------
        | LOAN DETAILS
        |--------------------------------------------------------------------------
        */

        'amount',
        'principal_amount',

        'interest_rate',
        'interest_amount',

        'duration_months',

        'loan_date',
        'due_date',
        'disbursed_at',
        'disbursement_date',

        /*
        |--------------------------------------------------------------------------
        | FINANCIALS
        |--------------------------------------------------------------------------
        */

        'total_payable',
        'monthly_installment',
        'balance',

        /*
        |--------------------------------------------------------------------------
        | CREDIT ENGINE
        |--------------------------------------------------------------------------
        */

        'credit_score',
        'system_recommendation',

        /*
        |--------------------------------------------------------------------------
        | LIFECYCLE
        |--------------------------------------------------------------------------
        */

        'application_status',
        'loan_status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeApplications($query)
    {
        return $query->whereIn('application_status', [

            'pending',
            'under_review',
            'auto_approved',
            'auto_rejected',

        ]);
    }

    public function scopeReviewQueue($query)
    {
        return $query
            ->whereIn('application_status', [

                'approved',
                'rejected',

            ])
            ->where('loan_status', 'not_disbursed');
    }

    public function scopeActiveLoans($query)
    {
        return $query->whereIn('loan_status', [

            'active',
            'ongoing',

        ]);
    }

    public function scopeOverdueLoans($query)
    {
        return $query->where('loan_status', 'overdue');
    }

    public function scopeCompletedLoans($query)
    {
        return $query->where('loan_status', 'completed');
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL REPAID
    |--------------------------------------------------------------------------
    */

    public function totalRepaid()
    {
        return $this->repayments()->sum('amount');
    }


    /*
    |--------------------------------------------------------------------------
    | LAST REPAYMENT
    |--------------------------------------------------------------------------
    */

    public function lastRepayment()
    {
        return $this->repayments()
            ->latest('payment_date')
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | RECONCILIATION ENGINE
    |--------------------------------------------------------------------------
    */

    public function recalculateLoan()
    {
        $paid = $this->totalRepaid();

        /*
        |--------------------------------------------------------------------------
        | BALANCE
        |--------------------------------------------------------------------------
        */

        $this->balance = $this->total_payable - $paid;

        if ($this->balance < 0) {

            $this->balance = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS ENGINE
        |--------------------------------------------------------------------------
        */

        if ($this->balance <= 0) {

            $this->loan_status = 'completed';
        }

        elseif ($paid > 0) {

            $this->loan_status = 'ongoing';
        }

        elseif ($this->disbursement_date) {

            $this->loan_status = 'active';
        }

        /*
        |--------------------------------------------------------------------------
        | OVERDUE CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $this->due_date &&
            now()->greaterThan($this->due_date) &&
            $this->balance > 0
        ) {

            $this->loan_status = 'overdue';
        }

        $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | STATE HELPERS
    |--------------------------------------------------------------------------
    */

    public function canRepay()
    {
        return in_array($this->loan_status, [

            'active',
            'ongoing',
            'overdue',

        ]);
    }

    public function canDisburse()
    {
        return
            $this->application_status === 'approved' &&
            $this->loan_status === 'not_disbursed';
    }

    //public function isCompleted()
    //{
    //    return $this->loan_status === 'completed';
    //}
    public function isCompleted()
    {
    return $this->loan_status === 'completed'
        || $this->balance <= 0;
    }

    public function isOverdue()
    {
        return $this->loan_status === 'overdue';
    }

    public function schedules()
    {
    return $this->hasMany(
        LoanSchedule::class
    );
    }

    //Generate Schedule Automatically

    public function generateSchedule()
    {
        /*
        |--------------------------------------------------------------------------
        | CLEAR OLD SCHEDULE
        |--------------------------------------------------------------------------
        */

        $this->schedules()->delete();

        /*
        |--------------------------------------------------------------------------
        | LOAN VALUES
        |--------------------------------------------------------------------------
        */

        $balance = $this->total_payable;

        $months = $this->duration_months;

        $monthlyPrincipal =
            $this->amount / $months;

        $monthlyInterest =
            $this->interest_amount / $months;

        $monthlyInstallment =
            $this->monthly_installment;

        /*
        |--------------------------------------------------------------------------
        | BUILD SCHEDULE
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= $months; $i++) {

            $balance -= $monthlyInstallment;

            $this->schedules()->create([

                'installment_number' => $i,

                'due_date' => now()->addMonths($i),

                'principal_amount' => $monthlyPrincipal,

                'interest_amount' => $monthlyInterest,

                'installment_amount' => $monthlyInstallment,

                'balance_after' => max($balance, 0),

                'status' => 'pending',
            ]);
          }
        }


    //Auto-Calculate Overdue Loans

    //public function isOverdue() {
    //    return now()->gt($this->due_date)
    //    && $this->balance > 0;
    //}

    public function applyPenalty()
    {
        /*
        |--------------------------------------------------------------------------
        | PREVENT DOUBLE PENALTY
        |--------------------------------------------------------------------------
        */

        if ($this->penalty_applied) {
        return;
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPLE 5% PENALTY
        |--------------------------------------------------------------------------
        */

        $penalty =
            $this->balance * 0.05;

        /*
        |--------------------------------------------------------------------------
        | UPDATE LOAN
        |--------------------------------------------------------------------------
        */

        $this->update([

            'loan_status' => 'overdue',

            'penalty_amount' => $penalty,

            'penalty_applied' => true,

            'balance' => $this->balance + $penalty,
        ]);
    }

    public static function processOverdues()
    {
        $loans = self::whereIn('loan_status', [

            'active',
            'ongoing',

        ])->get();

        foreach ($loans as $loan) {

            if ($loan->isOverdue()) {

                $loan->applyPenalty();
            }
        }
    }

}