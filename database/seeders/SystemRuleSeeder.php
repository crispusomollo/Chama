<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemRule;

class SystemRuleSeeder extends Seeder
{
    public function run(): void
    {
        SystemRule::updateOrCreate(

            [
                'effective_from' => '2026-01-01'
            ],

            [
                'monthly_contribution' => 1500,

                'shares_allocation' => 1000,

                'insurance_allocation' => 500,

                'penalty_amount' => 100,

                'grace_days' => 5,

                'interest_rate' => 10,

                'loan_multiplier' => 3,

                'max_repayment_months' => 12,

                'active' => true,

                'notes' =>
                    'Initial production rule',
            ]
        );
    }
}
