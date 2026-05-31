@extends('layouts.erp')

@section('title', 'Reports')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Reports</h1>
        <p class="text-gray-500">System-wide financial and loan analytics</p>
    </div>

    {{-- KPI ROW (MATCH DASHBOARD STYLE) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Total Loans</p>
            <h2 class="text-2xl font-bold">
                {{ $totalLoans ?? 0 }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Active Loans</p>
            <h2 class="text-2xl font-bold text-blue-600">
                {{ $activeLoans ?? 0 }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Total Disbursed</p>
            <h2 class="text-2xl font-bold text-green-600">
                KES {{ number_format($totalDisbursed ?? 0) }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Overdue Loans</p>
            <h2 class="text-2xl font-bold text-red-600">
                {{ $overdueLoans ?? 0 }}
            </h2>
        </div>

    </div>

    {{-- TABLE SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-1">

        <div class="bg-white rounded-xl shadow p-5">

            <h3 class="font-semibold text-gray-700 mb-4">
                Loan Activity Report
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="text-left text-gray-500 border-b">
                        <tr>
                            <th class="py-2">Borrower</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($loans ?? [] as $loan)
                            <tr class="border-b hover:bg-gray-50">

                                <td class="py-2">
                                    {{ $loan->borrower->firstname ?? 'N/A' }}
                                    {{ $loan->borrower->lastname ?? '' }}
                                </td>

                                <td class="py-2">
                                    KES {{ number_format($loan->amount) }}
                                </td>

                                <td class="py-2">
                                    <span class="px-2 py-1 text-xs rounded
                                        @if($loan->status === 'active') bg-blue-100 text-blue-700
                                        @elseif($loan->status === 'paid') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>

                                <td class="py-2">
                                    {{ $loan->created_at->format('Y-m-d') }}
                                </td>

                            </tr>
                        @empty

                            <tr>
                                <td colspan="4" class="py-4 text-gray-400 text-center">
                                    No report data available
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection