@extends('layouts.erp')

@section('title', 'Settings')

@section('content')

<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            System Settings
        </h2>

        <p class="text-sm text-gray-500">
            Configure contribution, loan and organization rules.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('settings.update') }}"
          class="space-y-6">

        @csrf

        <!-- CONTRIBUTION RULES -->
        <div class="bg-white rounded-xl shadow p-6">

            <h3 class="text-lg font-bold mb-4">
                Contribution Rules
            </h3>

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label>Monthly Contribution</label>
                    <input type="number"
                           name="monthly_contribution"
                           value="{{ $settings['monthly_contribution'] ?? 1300 }}"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Shares Allocation</label>
                    <input type="number"
                           name="shares_allocation"
                           value="{{ $settings['shares_allocation'] ?? 1000 }}"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Insurance Allocation</label>
                    <input type="number"
                           name="insurance_allocation"
                           value="{{ $settings['insurance_allocation'] ?? 300 }}"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Penalty Amount</label>
                    <input type="number"
                           name="penalty_amount"
                           value="{{ $settings['penalty_amount'] ?? 100 }}"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Grace Days</label>
                    <input type="number"
                           name="grace_days"
                           value="{{ $settings['grace_days'] ?? 5 }}"
                           class="w-full border rounded-lg p-2">
                </div>

            </div>

        </div>

        <!-- LOAN RULES -->
        <div class="bg-white rounded-xl shadow p-6">

            <h3 class="text-lg font-bold mb-4">
                Loan Rules
            </h3>

            <div class="grid md:grid-cols-3 gap-4">

                <div>
                    <label>Interest Rate (%)</label>
                    <input type="number"
                           name="default_interest_rate"
                           value="{{ $settings['default_interest_rate'] ?? 10 }}"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Loan Multiplier</label>
                    <input type="number"
                           name="max_loan_multiplier"
                           value="{{ $settings['max_loan_multiplier'] ?? 3 }}"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Repayment Months</label>
                    <input type="number"
                           name="max_repayment_months"
                           value="{{ $settings['max_repayment_months'] ?? 12 }}"
                           class="w-full border rounded-lg p-2">
                </div>

            </div>

        </div>

        <!-- RECEIPTS -->
        <div class="bg-white rounded-xl shadow p-6">

            <h3 class="text-lg font-bold mb-4">
                Receipt Settings
            </h3>

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label>Receipt Prefix</label>
                    <input type="text"
                           name="receipt_prefix"
                           value="{{ $settings['receipt_prefix'] ?? 'RCPT' }}"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Next Receipt Number</label>
                    <input type="number"
                           name="next_receipt_number"
                           value="{{ $settings['next_receipt_number'] ?? 1001 }}"
                           class="w-full border rounded-lg p-2">
                </div>

            </div>

        </div>

        <!-- ORGANIZATION -->
        <div class="bg-white rounded-xl shadow p-6">

            <h3 class="text-lg font-bold mb-4">
                Organization Profile
            </h3>

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label>Name</label>
                    <input type="text"
                           name="organization_name"
                           value="{{ $settings['organization_name'] ?? '' }}"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Phone</label>
                    <input type="text"
                           name="organization_phone"
                           value="{{ $settings['organization_phone'] ?? '' }}"
                           class="w-full border rounded-lg p-2">
                </div>

            </div>

        </div>

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

            Save Settings

        </button>

    </form>

</div>

@endsection
