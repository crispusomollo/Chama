@extends('layouts.erp')

@section('title', 'Loan Schedule')

@section('content')

<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Loan Amortization Schedule
            </h2>

            <p class="text-gray-500">
                {{ $loan->borrower->full_name }}
            </p>

        </div>

        <a
            href="{{ route('loans.index') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg"
        >
            Back To Loans
        </a>

    </div>

    <!-- SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-white p-5 rounded-xl shadow">

            <p class="text-sm text-gray-500">
                Loan Amount
            </p>

            <h3 class="text-2xl font-bold">
                KES {{ number_format($loan->amount) }}
            </h3>

        </div>

        <div class="bg-white p-5 rounded-xl shadow">

            <p class="text-sm text-gray-500">
                Interest
            </p>

            <h3 class="text-2xl font-bold text-indigo-600">
                KES {{ number_format($loan->interest_amount) }}
            </h3>

        </div>

        <div class="bg-white p-5 rounded-xl shadow">

            <p class="text-sm text-gray-500">
                Total Payable
            </p>

            <h3 class="text-2xl font-bold text-green-600">
                KES {{ number_format($loan->total_payable) }}
            </h3>

        </div>

        <div class="bg-white p-5 rounded-xl shadow">

            <p class="text-sm text-gray-500">
                Monthly Installment
            </p>

            <h3 class="text-2xl font-bold text-blue-600">
                KES {{ number_format($loan->monthly_installment) }}
            </h3>

        </div>

    </div>

    <!-- SCHEDULE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>

                    <th class="px-4 py-3 text-left">
                        #
                    </th>

                    <th class="px-4 py-3 text-left">
                        Due Date
                    </th>

                    <th class="px-4 py-3 text-left">
                        Principal
                    </th>

                    <th class="px-4 py-3 text-left">
                        Interest
                    </th>

                    <th class="px-4 py-3 text-left">
                        Installment
                    </th>

                    <th class="px-4 py-3 text-left">
                        Balance
                    </th>

                    <th class="px-4 py-3 text-left">
                        Status
                    </th>

                </tr>

            </thead>

            <tbody>

            @foreach($loan->schedules as $schedule)

                <tr class="border-b">

                    <td class="px-4 py-3">
                        {{ $schedule->installment_number }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $schedule->due_date }}
                    </td>

                    <td class="px-4 py-3">
                        KES {{ number_format($schedule->principal_amount) }}
                    </td>

                    <td class="px-4 py-3">
                        KES {{ number_format($schedule->interest_amount) }}
                    </td>

                    <td class="px-4 py-3 font-semibold">
                        KES {{ number_format($schedule->installment_amount) }}
                    </td>

                    <td class="px-4 py-3 text-red-600">
                        KES {{ number_format($schedule->balance_after) }}
                    </td>

                    <td class="px-4 py-3">

                        <span class="text-yellow-600 font-semibold">
                            {{ strtoupper($schedule->status) }}
                        </span>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
