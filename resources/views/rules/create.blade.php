@extends('layouts.erp')

@section('title', 'New Policy')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">
            Create New Contribution Policy
        </h1>

        <p class="text-gray-500">
            Define a new policy that becomes effective from a specific date.
        </p>
    </div>

    <form method="POST" action="{{ route('rules.store') }}">

        @csrf

        <div class="bg-white shadow rounded-xl p-6">

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label>Effective From</label>
                    <input
                        type="date"
                        name="effective_from"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label>Monthly Contribution</label>
                    <input
                        type="number"
                        name="monthly_contribution"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label>Shares Allocation</label>
                    <input
                        type="number"
                        name="shares_allocation"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label>Insurance Allocation</label>
                    <input
                        type="number"
                        name="insurance_allocation"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label>Penalty Amount</label>
                    <input
                        type="number"
                        name="penalty_amount"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label>Grace Days</label>
                    <input
                        type="number"
                        name="grace_days"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label>Interest Rate (%)</label>
                    <input
                        type="number"
                        name="interest_rate"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label>Loan Multiplier</label>
                    <input
                        type="number"
                        name="loan_multiplier"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label>Repayment Months</label>
                    <input
                        type="number"
                        name="max_repayment_months"
                        class="w-full border rounded-lg p-2"
                        required>
                </div>

            </div>

            <div class="mt-4">

                <label>Notes</label>

                <textarea
                    name="notes"
                    rows="4"
                    class="w-full border rounded-lg p-2"></textarea>

            </div>

            <div class="mt-6">

                <button
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

                    Save Policy

                </button>

            </div>

        </div>

    </form>

</div>

@endsection
