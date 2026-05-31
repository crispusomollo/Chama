<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

use App\Services\SettingService;

class SettingsController extends Controller
{
    public function index()
    {
        //$settings = Setting::all()->keyBy('key');
        $settings = Setting::pluck('value', 'key');

        return view('settings.index', compact('settings'));
    }

    /*public function update(Request $request)
    {
        $data = $request->validate([
            'monthly_contribution' => 'required|numeric|min:0',
            'shares_allocation'    => 'required|numeric|min:0',
            'insurance_allocation' => 'required|numeric|min:0',
            'penalty_amount'       => 'required|numeric|min:0',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully');
    }*/

        public function update(Request $request)
        {
            $data = $request->validate([

            // Contribution Rules
            'monthly_contribution' => 'required|numeric|min:0',
            'shares_allocation' => 'required|numeric|min:0',
            'insurance_allocation' => 'required|numeric|min:0',
            'penalty_amount' => 'required|numeric|min:0',
            'grace_days' => 'required|integer|min:0',

            // Loan Rules
            'default_interest_rate' => 'required|numeric|min:0',
            'max_loan_multiplier' => 'required|numeric|min:1',
            'max_repayment_months' => 'required|integer|min:1',

            // Receipts
            'receipt_prefix' => 'required|string|max:20',
            'next_receipt_number' => 'required|integer|min:1',

            // Organization
            'organization_name' => 'required|string|max:255',
            'organization_phone' => 'nullable|string|max:50',
        ]);

        /*foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }*/

        foreach ($data as $key => $value) {

            SettingService::set(
                $key,
                $value
            );
        }

        return back()->with(
            'success',
            'Settings updated successfully.'
        );
    }
}
