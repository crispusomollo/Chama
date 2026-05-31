<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            /*
            |--------------------------------------------------------------------------
            | CONTRIBUTIONS
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'monthly_contribution',
                'value' => 1300
            ],

            [
                'key' => 'shares_allocation',
                'value' => 1000
            ],

            [
                'key' => 'insurance_allocation',
                'value' => 300
            ],

            /*
            |--------------------------------------------------------------------------
            | PENALTIES
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'penalty_amount',
                'value' => 100
            ],

            [
                'key' => 'grace_days',
                'value' => 5
            ],

            /*
            |--------------------------------------------------------------------------
            | LOANS
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'default_interest_rate',
                'value' => 10
            ],

            [
                'key' => 'max_loan_multiplier',
                'value' => 3
            ],

            [
                'key' => 'max_repayment_months',
                'value' => 12
            ],

            /*
            |--------------------------------------------------------------------------
            | RECEIPTS
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'receipt_prefix',
                'value' => 'RCPT'
            ],

            [
                'key' => 'next_receipt_number',
                'value' => 1001
            ],

            /*
            |--------------------------------------------------------------------------
            | ORGANIZATION
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'organization_name',
                'value' => 'My Chama'
            ],

            [
                'key' => 'organization_phone',
                'value' => '0712345678'
            ],
        ];

        foreach ($settings as $setting) {

            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
