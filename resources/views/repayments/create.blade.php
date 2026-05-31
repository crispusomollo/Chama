@extends('layouts.erp')

@section('title', 'Loan Repayment')

@section('content')

@if(session('success'))

    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">

        {{ session('success') }}

    </div>

@endif

@if(session('error'))

    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">

        {{ session('error') }}

    </div>

@endif

<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-xl shadow p-6">

        <div class="mb-6">

            <h2 class="text-2xl font-bold text-gray-800">
                Loan Repayment
            </h2>

            <p class="text-gray-500 mt-1">
                Record loan repayment transaction
            </p>

        </div>

        <!-- LOAN SUMMARY -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2">

            <div class="flex justify-between">

                <span class="text-gray-600">
                    Borrower
                </span>

                <span class="font-semibold">
                    {{ $loan->borrower->full_name }}
                </span>

            </div>

            <div class="flex justify-between">

                <span class="text-gray-600">
                    Loan Amount
                </span>

                <span class="font-semibold">
                    KES {{ number_format($loan->amount) }}
                </span>

            </div>

            <div class="flex justify-between">

                <span class="text-gray-600">
                    Outstanding Balance
                </span>

                <span class="font-semibold text-red-600">
                    KES {{ number_format($loan->balance) }}
                </span>

            </div>

        </div>

        <!-- FORM -->
        <form
            action="{{ route('repayments.store') }}"
            method="POST"
            class="space-y-4"
        >

            @csrf

            <input
                type="hidden"
                name="loan_id"
                value="{{ $loan->id }}"
            >

            <div>

                <label class="block mb-1 font-medium">
                    Repayment Amount
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="amount"
                    required
                    class="w-full border rounded-lg p-3"
                >

            </div>


            <div>

                <label class="block mb-1 font-medium">
                    Payment Method
                </label>

                <select
                    name="payment_method"
                    class="w-full border rounded-lg p-3"
                    required
                >

                <option value="cash">Cash</option>

                <option value="mpesa">M-PESA</option>

                <option value="bank">Bank Transfer</option>

                </select>

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Transaction Code
                </label>

                <input
                    type="text"
                    name="transaction_code"
                    placeholder="Optional M-PESA / Bank Reference"
                    class="w-full border rounded-lg p-3"
                >

            </div>


            <div>

                <label class="block mb-1 font-medium">
                    Payment Date
                </label>

                <input
                    type="date"
                    name="payment_date"
                    value="{{ now()->toDateString() }}"
                    required
                    class="w-full border rounded-lg p-3"
                >

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Notes
                </label>

                <textarea
                    name="notes"
                    rows="4"
                    class="w-full border rounded-lg p-3"
                ></textarea>

            </div>

            <div class="flex justify-end gap-2 pt-4">

                <a
                    href="{{ route('loans.index') }}"
                    class="border px-4 py-2 rounded-lg"
                >
                    Cancel
                </a>

                <button
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg"
                >
                    Save Repayment
                </button>

            </div>

        </form>

        
        <!-- REPAYMENT HISTORY -->
<div class="mt-8">

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b">

            <h3 class="font-semibold text-gray-700">
                Previous Repayments
            </h3>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Date
                        </th>

                        <th class="px-4 py-3 text-left">
                            Amount
                        </th>

                        <th class="px-4 py-3 text-left">
                            Method
                        </th>

                        <th class="px-4 py-3 text-left">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($loan->repayments as $repayment)

                    <tr class="border-b">

                        <td class="px-4 py-3">
                            {{ $repayment->payment_date }}
                        </td>

                        <td class="px-4 py-3 text-green-600 font-semibold">
                            KES {{ number_format($repayment->amount) }}
                        </td>

                        <td class="px-4 py-3 uppercase">
                            {{ $repayment->payment_method }}
                        </td>

                        <td class="px-4 py-3">

                            @if($repayment->status == 'completed')

                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                                    COMPLETED
                                </span>

                            @else

                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">
                                    {{ strtoupper($repayment->status) }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="4"
                            class="px-4 py-6 text-center text-gray-500"
                        >
                            No repayment history yet.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

    </div>

</div>

@endsection
