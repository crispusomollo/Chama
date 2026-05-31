<?php

namespace App\Services;

use App\Models\SystemRule;
use Carbon\Carbon;

class RuleService
{
    public static function current()
    {
        return SystemRule::where(
                'effective_from',
                '<=',
                now()->toDateString()
            )
            ->where('active', true)
            ->orderByDesc('effective_from')
            ->first();
    }

    /*public static function forPeriod(
        string $period
    ) {
        $date = Carbon::parse(
            $period . '-01'
        );

        return SystemRule::where(
                'effective_from',
                '<=',
                $date->toDateString()
            )
            ->where('active', true)
            ->orderByDesc('effective_from')
            ->first();
    }*/

    public static function forPeriod(
    string $period
    )
    {
        $date = Carbon::parse(
            $period . '-01'
        );

        $rule = SystemRule::where(
                'effective_from',
                '<=',
                $date->toDateString()
            )
            ->where('active', true)
            ->orderByDesc('effective_from')
            ->first();

        if ($rule) {
            return $rule;
        }

        return SystemRule::where('active', true)
            ->orderBy('effective_from')
            ->first();
    }

    /*public static function ruleForContributionDate(
        string $date
    )
    {
        $period = Carbon::parse($date)
            ->format('Y-m');

        return self::forPeriod($period);
    }*/

    public static function ruleForContributionDate(
        string $date
    ) {
        return SystemRule::where(
            'effective_from',
            '<=',
            $date
        )
            ->where('active', true)
            ->orderByDesc('effective_from')
            ->first();
    }

    public static function monthlyContribution(
        string $period = null
    ) {
        $rule = $period
            ? self::forPeriod($period)
            : self::current();

        return $rule?->monthly_contribution ?? 0;
    }


    public static function sharesAllocation(
        string $period = null
    ) {
        $rule = $period
            ? self::forPeriod($period)
            : self::current();

        return $rule?->shares_allocation ?? 0;
    }

    public static function insuranceAllocation(
        string $period = null
    ) {
        $rule = $period
            ? self::forPeriod($period)
            : self::current();

        return $rule?->insurance_allocation ?? 0;
    }

    public static function penaltyAmount(
        string $period = null
    ) {
        $rule = $period
            ? self::forPeriod($period)
            : self::current();

        return $rule?->penalty_amount ?? 100;
    }

    public static function interestRate(
        string $period = null
    ) {
        $rule = $period
            ? self::forPeriod($period)
            : self::current();

        return $rule?->interest_rate ?? 10;
    }
}
