@extends('layouts.erp')

@section('title', 'Shares Ledger')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Shares Ledger
            </h2>

            <p class="text-sm text-gray-500">
                Track member share allocations and balances
            </p>

        </div>

    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Total Shares
            </p>

            <h2 class="text-2xl font-bold text-green-600 mt-2">
                KES {{ number_format($totalShares) }}
            </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Shareholders
            </p>

            <h2 class="text-2xl font-bold text-blue-600 mt-2">
                {{ $totalMembers }}
            </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Transactions
            </p>

            <h2 class="text-2xl font-bold text-indigo-600 mt-2">
                {{ $totalTransactions }}
            </h2>

        </div>

    </div>

    {{-- LEDGER TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b">

            <h3 class="font-semibold text-gray-700">
                Shares Transactions
            </h3>

        </div>

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>

                    <th class="p-3 text-left">
                        Receipt
                    </th>

                    <th class="p-3 text-left">
                        Member
                    </th>

                    <th class="p-3 text-left">
                        Transaction
                    </th>

                    <th class="p-3 text-left">
                        Amount
                    </th>

                    <th class="p-3 text-left">
                        Date
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($entries as $entry)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-3">
                            {{ $entry->contribution->receipt_no ?? '-' }}
                        </td>

                        <td class="p-3 font-medium">
                            {{ $entry->borrower->full_name }}
                        </td>

                        <td class="p-3">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                {{ strtoupper($entry->transaction_type) }}
                            </span>
                        </td>

                        <td class="p-3 font-bold text-green-600">
                            KES {{ number_format($entry->amount) }}
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($entry->transaction_date)->format('d M Y') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="p-6 text-center text-gray-400">
                            No share transactions found
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div>
        {{ $entries->links() }}
    </div>

</div>

@endsection
