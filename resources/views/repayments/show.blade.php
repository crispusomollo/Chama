@extends('layouts.erp')

@section('title', 'Repayment History')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Repayment History
            </h2>

            <p class="text-gray-500 mt-1">
                {{ $loan->borrower->full_name }}
            </p>

        </div>

        <a
            href="{{ route('repayments.create', ['loan_id' => $loan->id]) }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"
        >
            + Add Repayment
        </a>

    </div>

    <!-- KPI CARDS -->

    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

    <!-- TOTAL LOAN -->
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Total Loan
        </p>

        <h3 class="text-2xl font-bold">
            KES {{ number_format($loan->total_payable) }}
        </h3>

    </div>

    <!-- TOTAL PAID -->
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Total Paid
        </p>

        <h3 class="text-2xl font-bold text-green-600">
            KES {{ number_format($totalPaid) }}
        </h3>

    </div>

    <!-- BALANCE -->
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Outstanding
        </p>

        <h3 class="text-2xl font-bold text-red-600">
            KES {{ number_format($outstanding) }}
        </h3>

    </div>

    <!-- COMPLETION -->
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Completion
        </p>

        <h3 class="text-2xl font-bold text-blue-600">
            {{ number_format($completionRate, 1) }}%
        </h3>

    </div>

    <!-- TRANSACTIONS -->
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Transactions
        </p>

        <h3 class="text-2xl font-bold text-indigo-600">
            {{ $totalTransactions }}
        </h3>

    </div>

    <!-- STATUS -->
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Loan Status
        </p>

        <h3 class="text-xl font-bold uppercase">

            @if($loan->loan_status == 'active')

                <span class="text-blue-600">
                    ACTIVE
                </span>

            @elseif($loan->loan_status == 'ongoing')

                <span class="text-yellow-600">
                    ONGOING
                </span>

            @elseif($loan->loan_status == 'completed')

                <span class="text-green-600">
                    COMPLETED
                </span>

            @elseif($loan->loan_status == 'overdue')

                <span class="text-red-600">
                    OVERDUE
                </span>

            @else

                <span class="text-gray-700">
                    {{ strtoupper($loan->loan_status) }}
                </span>

            @endif

        </h3>

    </div>

</div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b">

            <h3 class="font-semibold text-gray-700">
                Repayment Transactions
            </h3>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Payment Date
                        </th>

                        <th class="px-4 py-3 text-left">
                            Amount
                        </th>

                        <th class="px-4 py-3 text-left">
                            Receipt
                        </th>

                        <th class="px-4 py-3 text-left">
                            Method
                        </th>

                        <th class="px-4 py-3 text-left">
                            Notes
                        </th>

                        <th class="px-4 py-3 text-left">
                            Recorded
                        </th>

                        <th class="px-4 py-3 text-left">
                            Receipt
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($repayments as $repayment)

                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-4 py-3">
                                {{ $repayment->payment_date }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-green-700">
                                KES {{ number_format($repayment->amount) }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $repayment->receipt_number }}
                            </td>

                            <td class="px-4 py-3 uppercase">
                                {{ $repayment->payment_method }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $repayment->notes }}
                            </td>

                            <td class="px-4 py-3 text-gray-500 text-sm">
                                {{ $repayment->created_at->diffForHumans() }}
                            </td>

                            <td class="px-4 py-3">

                                <a href="{{ route('repayments.receipt', $repayment->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                                >
                                   View Receipt
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-4 py-6 text-center text-gray-500"
                            >
                                No repayments recorded yet.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- PAGINATION -->
    <div>
        {{ $repayments->links() }}
    </div>

</div>

@endsection
