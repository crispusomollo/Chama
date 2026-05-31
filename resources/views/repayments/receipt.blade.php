@extends('layouts.erp')

@section('title', 'Payment Receipt')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white shadow rounded-xl p-8">

        <!-- HEADER -->
        <div class="flex justify-between items-start border-b pb-4">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">
                    PAYMENT RECEIPT
                </h1>

                <p class="text-gray-500 mt-1">
                    Loan Repayment Confirmation
                </p>

            </div>

            <div class="text-right">

                <p class="text-sm text-gray-500">
                    Receipt No
                </p>

                <h2 class="font-bold text-lg">
                    {{ $repayment->receipt_number }}
                </h2>

            </div>

        </div>

        <!-- BODY -->
        <div class="grid grid-cols-2 gap-6 mt-8">

            <div>

                <h3 class="font-semibold text-gray-700 mb-3">
                    Borrower Information
                </h3>

                <div class="space-y-2 text-sm">

                    <p>
                        <span class="font-medium">Name:</span>
                        {{ $repayment->loan->borrower->full_name }}
                    </p>

                    <p>
                        <span class="font-medium">Loan ID:</span>
                        #{{ $repayment->loan->id }}
                    </p>

                    <p>
                        <span class="font-medium">Payment Date:</span>
                        {{ $repayment->payment_date }}
                    </p>

                </div>

            </div>

            <div>

                <h3 class="font-semibold text-gray-700 mb-3">
                    Transaction Details
                </h3>

                <div class="space-y-2 text-sm">

                    <p>
                        <span class="font-medium">Reference:</span>
                        {{ $repayment->reference_number }}
                    </p>

                    <p>
                        <span class="font-medium">Method:</span>
                        {{ strtoupper($repayment->payment_method) }}
                    </p>

                    <p>
                        <span class="font-medium">Transaction Code:</span>

                        {{ $repayment->transaction_code ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

        <!-- PAYMENT SUMMARY -->
        <div class="mt-10">

            <h3 class="font-semibold text-gray-700 mb-4">
                Payment Summary
            </h3>

            <div class="bg-gray-50 rounded-lg p-5 space-y-3">

                <div class="flex justify-between">

                    <span>
                        Amount Paid
                    </span>

                    <span class="font-bold text-green-700">
                        KES {{ number_format($repayment->amount) }}
                    </span>

                </div>

                <div class="flex justify-between">

                    <span>
                        Remaining Balance
                    </span>

                    <span class="font-bold text-red-600">
                        KES {{ number_format($repayment->loan->balance) }}
                    </span>

                </div>

                <div class="flex justify-between">

                    <span>
                        Loan Status
                    </span>

                    <span class="font-bold uppercase">

                        {{ $repayment->loan->loan_status }}

                    </span>

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="mt-10 border-t pt-5">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-gray-500">
                        Received By
                    </p>

                    <p class="font-semibold">
                        {{ auth()->user()->name }}
                    </p>

                </div>

                <button
                    onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg"
                >
                    Print Receipt
                </button>

            </div>

        </div>

    </div>

</div>

@endsection
