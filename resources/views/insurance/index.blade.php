@extends('layouts.erp')

@section('title', 'Insurance Ledger')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Insurance Ledger
            </h2>

            <p class="text-sm text-gray-500">
                Insurance contribution audit trail
            </p>

        </div>

    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Total Insurance Fund
            </p>

            <h2 class="text-2xl font-bold text-indigo-600 mt-2">
                KES {{ number_format($totalInsurance, 2) }}
            </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Total Transactions
            </p>

            <h2 class="text-2xl font-bold text-green-600 mt-2">
                {{ $totalTransactions }}
            </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Members Contributing
            </p>

            <h2 class="text-2xl font-bold text-blue-600 mt-2">
                {{ $totalMembers }}
            </h2>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>

                    <th class="p-3 text-left">Member</th>

                    <th class="p-3 text-left">Amount</th>

                    <th class="p-3 text-left">Type</th>

                    <th class="p-3 text-left">Description</th>

                    <th class="p-3 text-left">Date</th>

                </tr>

            </thead>

            <tbody>

                @forelse($ledger as $entry)

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">
                        {{ $entry->borrower->full_name }}
                    </td>

                    <td class="p-3 font-semibold text-indigo-600">
                        KES {{ number_format($entry->amount, 2) }}
                    </td>

                    <td class="p-3">

                        <span class="px-2 py-1 rounded text-xs
                            bg-green-100 text-green-700">

                            {{ strtoupper($entry->transaction_type) }}

                        </span>

                    </td>

                    <td class="p-3">
                        {{ $entry->description }}
                    </td>

                    <td class="p-3">
                        {{ \Carbon\Carbon::parse(
                            $entry->transaction_date
                        )->format('d M Y') }}
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5"
                        class="p-6 text-center text-gray-400">

                        No insurance transactions found

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div>
        {{ $ledger->links() }}
    </div>

</div>

@endsection
