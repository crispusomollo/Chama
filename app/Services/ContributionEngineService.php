<?php

namespace App\Services;

use App\Models\Borrower;
use App\Models\Contribution;
use App\Models\ContributionSchedule;
use Carbon\Carbon;

use App\Models\ShareLedger;
use App\Models\InsuranceLedger;

use App\Services\SettingService;

use App\Services\RuleService;

class ContributionEngineService
{
    

    /*
    |--------------------------------------------------------------------------
    | GENERATE YEAR CONTRIBUTION CALENDAR
    |--------------------------------------------------------------------------
    */

    public function generateMonthlySchedules()
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $members = Borrower::all();

        foreach ($members as $member) {

            for ($month = 1; $month <= $currentMonth; $month++) {

                $period = Carbon::create(
                    $currentYear,
                    $month,
                    1
                )->format('Y-m');

                $exists = ContributionSchedule::where(
                        'borrower_id',
                        $member->id
                    )
                    ->where(
                        'period',
                        $period
                    )
                    ->exists();

                if (! $exists) {

                    $this->ensureScheduleExists(
                        $member->id,
                        $period
                    );
                }
            }
        }
    }





    /*
    |--------------------------------------------------------------------------
    | APPLY CONTRIBUTION PAYMENT
    |--------------------------------------------------------------------------
    */

    public function applyContribution(
        $borrowerId,
        $amount,
        $startingPeriod
    ) {

        $remainingAmount = $amount;

        /*
        |--------------------------------------------------------------------------
        | ENSURE STARTING PERIOD EXISTS
        |--------------------------------------------------------------------------
        */

        $this->ensureScheduleExists(
            $borrowerId,
            $startingPeriod
        );

        /*
        |--------------------------------------------------------------------------
        | FETCH UNPAID SCHEDULES
        |--------------------------------------------------------------------------
        |
        | Oldest first:
        | overdue -> partial -> pending
        |
        */

        $schedules = ContributionSchedule::where(
                'borrower_id',
                $borrowerId
            )
            ->where('balance', '>', 0)
            ->orderBy('period')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | APPLY PAYMENT ACROSS PERIODS
        |--------------------------------------------------------------------------
        */

        foreach ($schedules as $schedule) {

            if ($remainingAmount <= 0) {
                break;
            }

            $amountNeeded = $schedule->balance;

            /*
            |--------------------------------------------------------------------------
            | FULLY PAY SCHEDULE
            |--------------------------------------------------------------------------
            */

            if ($remainingAmount >= $amountNeeded) {

                $schedule->paid_amount += $amountNeeded;

                $schedule->balance = 0;

                $schedule->status = 'paid';

                $remainingAmount -= $amountNeeded;

            } else {

                /*
                |--------------------------------------------------------------------------
                | PARTIAL PAYMENT
                |--------------------------------------------------------------------------
                */

                $schedule->paid_amount += $remainingAmount;

                $schedule->balance =
                    $schedule->expected_amount
                    - $schedule->paid_amount;

                $schedule->status = 'partial';

                $remainingAmount = 0;
            }

            $schedule->save();
        }

        /*
        |--------------------------------------------------------------------------
        | EXCESS PAYMENT -> FUTURE MONTHS
        |--------------------------------------------------------------------------
        */

        while ($remainingAmount > 0) {

            $lastSchedule = ContributionSchedule::where(
                    'borrower_id',
                    $borrowerId
                )
                ->latest('period')
                ->first();

            $nextPeriod = Carbon::parse(
                $lastSchedule->period . '-01'
            )
            ->addMonth()
            ->format('Y-m');

            /*
            |--------------------------------------------------------------------------
            | CREATE NEXT MONTH
            |--------------------------------------------------------------------------
            */

            $newSchedule = $this->ensureScheduleExists(
                $borrowerId,
                $nextPeriod
            );

            $amountNeeded = $newSchedule->balance;

            if ($remainingAmount >= $amountNeeded) {

                $newSchedule->paid_amount += $amountNeeded;

                $newSchedule->balance = 0;

                $newSchedule->status = 'paid';

                $remainingAmount -= $amountNeeded;

            } else {

                $newSchedule->paid_amount += $remainingAmount;

                $newSchedule->balance =
                    $newSchedule->expected_amount
                    - $newSchedule->paid_amount;

                $newSchedule->status = 'partial';

                $remainingAmount = 0;
            }

            $newSchedule->save();
        }
    }



    /*
    |--------------------------------------------------------------------------
    | ENSURE SCHEDULE EXISTS
    |--------------------------------------------------------------------------
    */

    private function ensureScheduleExists(
        $borrowerId,
        $period
    ) {
        $schedule = ContributionSchedule::where(
            'borrower_id',
            $borrowerId
        )
        ->where('period', $period)
        ->first();

        if ($schedule) {
            return $schedule;
        }

        $borrower = Borrower::findOrFail($borrowerId);

        $rule = RuleService::forPeriod($period);

        $expectedAmount =
            $rule?->monthly_contribution ?? 0;

        return ContributionSchedule::create([

            'borrower_id' => $borrowerId,

            'chama_id' => $borrower->chama_id,

            'rule_id' => $rule?->id,

            'period' => $period,

            'expected_amount' => $expectedAmount,

            'paid_amount' => 0,

            'balance' => $expectedAmount,

            'penalty' => 0,

            'due_date' => Carbon::parse(
                $period . '-01'
            )->endOfMonth(),

            'status' => 'pending',
        ]);
    }
        

    /*
    |--------------------------------------------------------------------------
    | GENERATE FUTURE MONTH SCHEDULE
    |--------------------------------------------------------------------------
    */

    private function generateFutureSchedule(
        $borrowerId,
        $period
    )
    {
        $borrower = Borrower::findOrFail($borrowerId);

        $rule = RuleService::forPeriod($period);

        $expectedAmount =
            $rule?->monthly_contribution ?? 0;

        return ContributionSchedule::firstOrCreate(

            [
                'borrower_id' => $borrowerId,
                'period' => $period,
            ],

            [
                'chama_id' => $borrower->chama_id,

                'rule_id' => $rule?->id,

                'expected_amount' => $expectedAmount,

                'paid_amount' => 0,

                'balance' => $expectedAmount,

                'penalty' => 0,

                'due_date' => Carbon::parse(
                    $period . '-01'
                )->endOfMonth(),

                'status' => 'pending',
            ]
        );
    }



    /*
    |--------------------------------------------------------------------------
    | RECALCULATE SCHEDULE STATUS
    |--------------------------------------------------------------------------
    */

    private function recalculateScheduleStatus($schedule)
    {
        /*
        |--------------------------------------------------------------------------
        | FULLY PAID
        |--------------------------------------------------------------------------
        */

        if ($schedule->balance <= 0) {

            $schedule->status = 'paid';

            $schedule->balance = 0;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PARTIAL PAYMENT
        |--------------------------------------------------------------------------
        */

        if ($schedule->paid_amount > 0) {

            $schedule->status = 'partial';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | OVERDUE
        |--------------------------------------------------------------------------
        */

        if (
            Carbon::parse($schedule->due_date)->isPast()
        ) {

            $schedule->status = 'overdue';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT
        |--------------------------------------------------------------------------
        */

        $schedule->status = 'pending';
    }



    /*
    |--------------------------------------------------------------------------
    | PROCESS ARREARS
    |--------------------------------------------------------------------------
    */

    public function processArrears()
    {
        $today = Carbon::today();

        $overdues = ContributionSchedule::with('rule')
            ->where(
                'due_date',
                '<',
                $today
            )
            ->where(
                'status',
                '!=',
                'paid'
            )
            ->get();

        foreach ($overdues as $schedule) {

            $schedule->status = 'overdue';

            $schedule->penalty +=
                $schedule->rule?->penalty_amount ?? 0;

            $schedule->save();
        }
    }



    /*
    |--------------------------------------------------------------------------
    | SHARES LEDGER
    |--------------------------------------------------------------------------
    */

    public function postSharesLedger($contribution)
    {
        $lastBalance = ShareLedger::where(
            'borrower_id',
            $contribution->borrower_id
        )
        ->latest()
        ->value('balance') ?? 0;

        $newBalance =
            $lastBalance
            + $contribution->shares_amount;

        ShareLedger::create([

            'borrower_id' =>
                $contribution->borrower_id,

            'contribution_id' =>
                $contribution->id,

            'transaction_type' =>
                'contribution',

            'description' =>
                'Monthly shares contribution',

            'credit' =>
                $contribution->shares_amount,

            'debit' => 0,

            'balance' =>
                $newBalance,

            'transaction_date' =>
                $contribution->contribution_date,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | POST INSURANCE LEDGER
    |--------------------------------------------------------------------------
    */

    public function postInsuranceLedger($contribution)
    {
        $lastBalance = InsuranceLedger::where(
            'borrower_id',
            $contribution->borrower_id
        )
        ->latest()
        ->value('balance') ?? 0;

        $newBalance =
            $lastBalance
            + $contribution->insurance_amount;

        InsuranceLedger::create([

            'borrower_id' =>
                $contribution->borrower_id,

            'contribution_id' =>
                $contribution->id,

            'transaction_type' =>
                'contribution',

            'description' =>
                'Insurance/Welfare contribution',

            'credit' =>
                $contribution->insurance_amount,

            'debit' => 0,

            'balance' =>
                $newBalance,

            'transaction_date' =>
                $contribution->contribution_date,
        ]);
    }

}
