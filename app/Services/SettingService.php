<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /*
    |--------------------------------------------------------------------------
    | CACHE TTL
    |--------------------------------------------------------------------------
    */

    protected const CACHE_TTL = 3600;

    /*
    |--------------------------------------------------------------------------
    | FALLBACK DEFAULTS
    |--------------------------------------------------------------------------
    |
    | These are ONLY used if the setting does not exist in DB.
    | Once admin changes values from Settings UI, DB values take over.
    |
    */

    protected const DEFAULTS = [

        // Contributions
        'monthly_contribution' => 1300,
        'shares_allocation' => 1000,
        'insurance_allocation' => 300,

        // Penalties
        'penalty_amount' => 100,
        'grace_days' => 5,

        // Loans
        'default_interest_rate' => 10,
        'max_loan_multiplier' => 3,
        'max_repayment_months' => 12,

        // Receipts
        'receipt_prefix' => 'RCPT',
        'next_receipt_number' => 1001,

        // Organization
        'organization_name' => 'My Chama',
        'organization_phone' => '0712345678',
    ];

    /*
    |--------------------------------------------------------------------------
    | GET SINGLE SETTING
    |--------------------------------------------------------------------------
    */

    public static function get(string $key, $default = null)
    {
        return Cache::remember(
            "setting_{$key}",
            self::CACHE_TTL,
            function () use ($key, $default) {

                $setting = Setting::where('key', $key)->first();

                if ($setting) {
                    return $setting->value;
                }

                return $default;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SET SINGLE SETTING
    |--------------------------------------------------------------------------
    */

    /*public static function set(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting_{$key}");
        Cache::forget('settings_all');
    }*/

    public static function set($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting_$key");

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
            self::CACHE_TTL,
            fn() => Setting::all()->keyBy('key')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTRIBUTIONS
    |--------------------------------------------------------------------------
    */

    public static function monthlyContribution(): float
    {
        return (float) self::get(
            'monthly_contribution',
            self::DEFAULTS['monthly_contribution']
        );
    }

    public static function sharesAllocation(): float
    {
        return (float) self::get(
            'shares_allocation',
            self::DEFAULTS['shares_allocation']
        );
    }

    public static function insuranceAllocation(): float
    {
        return (float) self::get(
            'insurance_allocation',
            self::DEFAULTS['insurance_allocation']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PENALTIES
    |--------------------------------------------------------------------------
    */

    public static function penaltyAmount(): float
    {
        return (float) self::get(
            'penalty_amount',
            self::DEFAULTS['penalty_amount']
        );
    }

    public static function graceDays(): int
    {
        return (int) self::get(
            'grace_days',
            self::DEFAULTS['grace_days']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOANS
    |--------------------------------------------------------------------------
    */

    public static function defaultInterestRate(): float
    {
        return (float) self::get(
            'default_interest_rate',
            self::DEFAULTS['default_interest_rate']
        );
    }

    public static function maxLoanMultiplier(): float
    {
        return (float) self::get(
            'max_loan_multiplier',
            self::DEFAULTS['max_loan_multiplier']
        );
    }

    public static function maxRepaymentMonths(): int
    {
        return (int) self::get(
            'max_repayment_months',
            self::DEFAULTS['max_repayment_months']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPTS
    |--------------------------------------------------------------------------
    */

    public static function receiptPrefix(): string
    {
        return (string) self::get(
            'receipt_prefix',
            self::DEFAULTS['receipt_prefix']
        );
    }

    public static function nextReceiptNumber(): int
    {
        return (int) self::get(
            'next_receipt_number',
            self::DEFAULTS['next_receipt_number']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ORGANIZATION
    |--------------------------------------------------------------------------
    */

    public static function organizationName(): string
    {
        return (string) self::get(
            'organization_name',
            self::DEFAULTS['organization_name']
        );
    }

    public static function organizationPhone(): string
    {
        return (string) self::get(
            'organization_phone',
            self::DEFAULTS['organization_phone']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPT NUMBER GENERATOR
    |--------------------------------------------------------------------------
    */

    public static function generateReceiptNumber(): string
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
    | CACHE
    |--------------------------------------------------------------------------
    */

    public static function clearCache(): void
    {
        Cache::flush();
    }
}