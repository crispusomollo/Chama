@extends('layouts.erp')

@section('title', 'Ledger Hub')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Ledger Hub
        </h2>
        <p class="text-sm text-gray-500">
            Central access to all financial ledgers
        </p>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Shares</p>
            <h2 class="text-2xl font-bold text-green-600">
                KES {{ number_format($totalShares) }}
            </h2>
            <p class="text-xs text-gray-400">
                {{ $shareTransactions }} transactions
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Insurance</p>
            <h2 class="text-2xl font-bold text-blue-600">
                KES {{ number_format($totalInsurance) }}
            </h2>
            <p class="text-xs text-gray-400">
                {{ $insuranceTransactions }} transactions
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Ledger Value</p>
            <h2 class="text-2xl font-bold text-indigo-600">
                KES {{ number_format($totalLedgerValue) }}
            </h2>
            <p class="text-xs text-gray-400">
                Combined financial position
            </p>
        </div>

    </div>

    {{-- NAVIGATION CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- SHARES --}}
        <a href="{{ route('shares.ledger') }}"
           class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">

            <h3 class="text-xl font-semibold text-green-700">
                Shares Ledger
            </h3>

            <p class="text-sm text-gray-500 mt-2">
                View member share allocations, balances, and transactions
            </p>

            <div class="mt-4 text-green-600 font-medium">
                Open →
            </div>

        </a>

        {{-- INSURANCE --}}
        <a href="{{ route('insurance.ledger') }}"
           class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">

            <h3 class="text-xl font-semibold text-blue-700">
                Insurance Ledger
            </h3>

            <p class="text-sm text-gray-500 mt-2">
                Track insurance contributions and allocations
            </p>

            <div class="mt-4 text-blue-600 font-medium">
                Open →
            </div>

        </a>

    </div>

</div>

@endsection
