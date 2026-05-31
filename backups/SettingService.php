<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /*
    |--------------------------------------------------------------------------
    | SYSTEM DEFAULTS
    |--------------------------------------------------------------------------
    */

    protected static array $defaults = [

        'monthly_contribution' => 1300,

        'shares_allocation' => 1000,

        'insurance_allocation' => 300,

        'penalty_amount' => 100,

        'grace_days' => 5,

        'default_interest_rate' => 10,

        'max_loan_multiplier' => 3,

        'max_repayment_months' => 12,

        'receipt_prefix' => 'RCPT',

        'next_receipt_number' => 1001,

        'organization_name' => 'My Chama',

        'organization_phone' => '0712345678',
    ];

    /*
    |--------------------------------------------------------------------------
    | GET SETTING
    |--------------------------------------------------------------------------
    */

    public static function get($key, $default = null)
    {
        $default ??= self::$defaults[$key] ?? null;

        return Cache::remember(
            "setting_{$key}",
            3600,
            function () use ($key, $default) {

                return Setting::where('key', $key)
                    ->value('value')
                    ?? $default;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE SETTING
    |--------------------------------------------------------------------------
    */

    public static function set($key, $value)
    {
        Setting::updateOrCreate(

            ['key' => $key],

            ['value' => $value]
        );

        Cache::forget("setting_{$key}");

        Cache::forget('settings_all');
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL SETTINGS
    |--------------------------------------------------------------------------
    */

    public static function all()
    {
        return Cache::remember(
            'settings_all',
            3600,
            function () {

                return Setting::all()->keyBy('key');
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTRIBUTIONS
    |--------------------------------------------------------------------------
    */

    public static function monthlyContribution()
    {
        return (float) self::get('monthly_contribution');
    }

    public static function sharesAllocation()
    {
        return (float) self::get('shares_allocation');
    }

    public static function insuranceAllocation()
    {
        return (float) self::get('insurance_allocation');
    }

    /*
    |--------------------------------------------------------------------------
    | PENALTIES
    |--------------------------------------------------------------------------
    */

    public static function penaltyAmount()
    {
        return (float) self::get('penalty_amount');
    }

    public static function graceDays()
    {
        return (int) self::get('grace_days');
    }

    /*
    |--------------------------------------------------------------------------
    | LOANS
    |--------------------------------------------------------------------------
    */

    public static function defaultInterestRate()
    {
        return (float) self::get('default_interest_rate');
    }

    public static function maxLoanMultiplier()
    {
        return (float) self::get('max_loan_multiplier');
    }

    public static function maxRepaymentMonths()
    {
        return (int) self::get('max_repayment_months');
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPTS
    |--------------------------------------------------------------------------
    */

    public static function receiptPrefix()
    {
        return self::get('receipt_prefix');
    }

    public static function nextReceiptNumber()
    {
        return (int) self::get('next_receipt_number');
    }

    /*
    |--------------------------------------------------------------------------
    | ORGANIZATION
    |--------------------------------------------------------------------------
    */

    public static function organizationName()
    {
        return self::get('organization_name');
    }

    public static function organizationPhone()
    {
        return self::get('organization_phone');
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPT GENERATOR
    |--------------------------------------------------------------------------
    */

    public static function generateReceiptNumber()
    {
        $prefix = self::receiptPrefix();

        $number = self::nextReceiptNumber();

        self::set(
            'next_receipt_number',
            $number + 1
        );

        return $prefix . '-' . str_pad(
            $number,
            6,
            '0',
            STR_PAD_LEFT
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR CACHE
    |--------------------------------------------------------------------------
    */

    public static function clearCache()
    {
        Cache::flush();
    }
}