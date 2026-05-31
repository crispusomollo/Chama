<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
   protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'contact_no',
        'address',
        'email',
        'tax_id',
        'chama_id',
    ];

    public function getFullNameAttribute()
      {
        return trim("{$this->firstname} {$this->middlename} {$this->lastname}");
    }

    public function calculateCreditScore()
    {
    $score = 0;

    /*
    |--------------------------------------------------------------------------
    | CONTRIBUTION CONSISTENCY
    |--------------------------------------------------------------------------
    */

    $contributionMonths = $this->contributions()
        ->select('period')
        ->distinct()
        ->count();

    if ($contributionMonths >= 12) {
        $score += 40;
    } elseif ($contributionMonths >= 6) {
        $score += 25;
    } elseif ($contributionMonths >= 3) {
        $score += 10;
    }

    /*
    |--------------------------------------------------------------------------
    | REPAYMENT DISCIPLINE
    |--------------------------------------------------------------------------
    */

    $loans = $this->loans;

    $paidLoans = $loans
        ->where('status', 'paid')
        ->count();

    $overdueLoans = $loans
        ->where('status', 'overdue')
        ->count();

    $score += ($paidLoans * 10);

    if ($score > 40) {
        $score = 40;
    }

    /*
    |--------------------------------------------------------------------------
    | OVERDUE PENALTY
    |--------------------------------------------------------------------------
    */

    if ($overdueLoans > 0) {
        $score -= 20;
    }

    /*
    |--------------------------------------------------------------------------
    | CONTRIBUTION VOLUME
    |--------------------------------------------------------------------------
    */

    $totalContributions = $this->contributions()
        ->sum('amount');

    if ($totalContributions >= 100000) {
        $score += 20;
    } elseif ($totalContributions >= 50000) {
        $score += 10;
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE
    |--------------------------------------------------------------------------
    */

    if ($score < 0) {
        $score = 0;
    }

    if ($score > 100) {
        $score = 100;
    }

    return $score;
    }

    public function riskGrade()
    {
    $score = $this->calculateCreditScore();

    if ($score >= 80) {
        return 'LOW RISK';
    }

    if ($score >= 50) {
        return 'MEDIUM RISK';
    }

    return 'HIGH RISK';
    }


    /*
    |--------------------------------------------------------------------------
    | MEMBER STATUS
    |--------------------------------------------------------------------------
    */

    public function memberStatus()
    {
        /*
        |--------------------------------------------------------------------------
        | DELINQUENT
        |--------------------------------------------------------------------------
        */

        if (
            $this->loans()
                ->where('loan_status', 'overdue')
                ->exists()
        ) {

            return 'DELINQUENT';
        }

        /*
        |--------------------------------------------------------------------------
        | BORROWING
        |--------------------------------------------------------------------------
        */

        if (
            $this->loans()
                ->whereIn('loan_status', [

                    'active',
                    'ongoing',

                ])
                ->exists()
        ) {

            return 'BORROWING';
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVE
        |--------------------------------------------------------------------------
        */

        if (
            $this->contributions()->exists()
        ) {

            return 'ACTIVE';
        }

        /*
        |--------------------------------------------------------------------------
        | INACTIVE
        |--------------------------------------------------------------------------
        */

        return 'INACTIVE';
    }


    public function totalShares()
    {
        return $this->contributions()
            ->sum('shares_amount');
    }

    public function totalInsurance()
    {
        return $this->contributions()
            ->sum('insurance_amount');
    }

    public function totalContributionsAmount()
    {
        return $this->contributions()
            ->sum('amount');
    }

    public function totalArrears()
    {
        return $this->contributionSchedules()
            ->where('balance', '>', 0)
            ->sum('balance');
    }

    public function advanceBalance()
    {
        $advance = $this->contributionSchedules()
            ->where('balance', '<', 0)
            ->sum('balance');

        return abs($advance);
    }


    /*public function contributionStatus()
    {
        $hasOverdue = $this->contributionSchedules()
            ->whereIn('status', ['partial', 'overdue'])
            ->exists();

        if ($hasOverdue) {
            return 'ARREARS';
        }

        $hasAdvance = $this->contributionSchedules()
            ->where('balance', '<', 0)
            ->exists();

        if ($hasAdvance) {
            return 'ADVANCED';
        }

        return 'CURRENT';
    }
        */


    public function contributionStatus()
    {
        $schedules = $this->contributionSchedules;

        if (!$schedules || $schedules->isEmpty()) {
            return 'INACTIVE';
        }

        $hasArrears = $schedules->whereIn('status', ['partial', 'overdue'])->isNotEmpty();

        if ($hasArrears) {
            return 'ARREARS';
        }

        $hasAdvance = $schedules->sum('balance') < 0;

        if ($hasAdvance) {
            return 'ADVANCED';
        }

        return 'CURRENT';
    }


  
    public function loans()
    {
    return $this->hasMany(Loan::class);
    }

    public function chama()
    {
    return $this->belongsTo(Chama::class);
    }

    public function contributions()
    {
    return $this->hasMany(Contribution::class);
    }

    public function contributionSchedules()
    {
    return $this->hasMany(ContributionSchedule::class);
    }

      public function shareLedgers()
    {
        return $this->hasMany(ShareLedger::class);
    }
 
    public function insuranceLedgers()
    {
        return $this->hasMany(InsuranceLedger::class);
    }


}
