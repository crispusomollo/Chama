<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContributionSchedule;
use App\Services\RuleService;

class FixLegacyContributionSchedules extends Command
{
    protected $signature = 'fix:schedules';
    protected $description = 'Fix legacy contribution schedules with rule-based values';

    public function handle()
    {
        $this->info("Starting schedule repair...");

        $schedules = ContributionSchedule::whereNull('rule_id')
            ->orWhere('rule_id', 0)
            ->get();

        $count = 0;

        foreach ($schedules as $schedule) {

            $rule = RuleService::forPeriod($schedule->period);

            if (!$rule) {
                $this->warn("No rule found for period {$schedule->period}");
                continue;
            }

            $schedule->rule_id = $rule->id;

            $schedule->expected_amount = $rule->monthly_contribution;

            $schedule->balance =
                $rule->monthly_contribution - $schedule->paid_amount;

            if ($schedule->balance <= 0) {
                $schedule->status = 'paid';
                $schedule->balance = 0;
            } elseif ($schedule->paid_amount > 0) {
                $schedule->status = 'partial';
            } else {
                $schedule->status = 'pending';
            }

            $schedule->save();

            $count++;
        }

        $this->info("Updated {$count} schedules successfully.");
    }
}
