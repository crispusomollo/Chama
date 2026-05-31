@extends('layouts.erp')

@section('title', 'Edit Policy')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">
            Edit Policy
        </h1>
    </div>

    <form
        method="POST"
        action="{{ route('rules.update', $rule) }}">

        @csrf
        @method('PUT')

        <div class="bg-white shadow rounded-xl p-6">

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label>Effective From</label>
                    <input
                        type="date"
                        name="effective_from"
                        value="{{ $rule->effective_from->format('Y-m-d') }}"
                        class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Monthly Contribution</label>
                    <input
                        type="number"
                        name="monthly_contribution"
                        value="{{ $rule->monthly_contribution }}"
                        class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Shares Allocation</label>
                    <input
                        type="number"
                        name="shares_allocation"
                        value="{{ $rule->shares_allocation }}"
                        class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Insurance Allocation</label>
                    <input
                        type="number"
                        name="insurance_allocation"
                        value="{{ $rule->insurance_allocation }}"
                        class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Penalty Amount</label>
                    <input
                        type="number"
                        name="penalty_amount"
                        value="{{ $rule->penalty_amount }}"
                        class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Grace Days</label>
                    <input
                        type="number"
                        name="grace_days"
                        value="{{ $rule->grace_days }}"
                        class="w-full border rounded-lg p-2">
                </div>

            </div>

            <div class="mt-4">

                <label>Notes</label>

                <textarea
                    name="notes"
                    rows="4"
                    class="w-full border rounded-lg p-2">{{ $rule->notes }}</textarea>

            </div>

            <div class="mt-4">

                <label class="flex items-center gap-2">

                    <input
                        type="checkbox"
                        name="active"
                        value="1"
                        {{ $rule->active ? 'checked' : '' }}>

                    Active Policy

                </label>

            </div>

            <div class="mt-6">

                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                    Update Policy

                </button>

            </div>

        </div>

    </form>

</div>

@endsection
