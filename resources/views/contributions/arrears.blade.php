@extends('layouts.erp')

@section('title', 'Arrears Management')

@section('content')

<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Arrears Management Center
            </h2>

            <p class="text-gray-500 text-sm">
                Monitor overdue contributions, penalties, and collection performance.
            </p>
        </div>

    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        {{-- OVERDUE MEMBERS --}}
        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Overdue Members
            </p>

            <h2 class="text-3xl font-bold text-red-600 mt-2">
                {{ $totalOverdueMembers }}
            </h2>

        </div>

        {{-- TOTAL ARREARS --}}
        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Total Arrears
            </p>

            <h2 class="text-3xl font-bold text-orange-600 mt-2">
                KES {{ number_format($totalArrears) }}
            </h2>

        </div>

        {{-- PENALTIES --}}
        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Total Penalties
            </p>

            <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                KES {{ number_format($totalPenalties) }}
            </h2>

        </div>

        {{-- PARTIAL PAYMENTS --}}
        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Partial Payments
            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $partialPayments }}
            </h2>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b">

            <h3 class="font-semibold text-gray-700">
                Overdue Contribution Schedules
            </h3>

        </div>

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>
                    <th class="p-3 text-left">Member</th>
                    <th class="p-3 text-left">Expected</th>
                    <th class="p-3 text-left">Paid</th>
                    <th class="p-3 text-left">Balance</th>
                    <th class="p-3 text-left">Penalty</th>
                    <th class="p-3 text-left">Due Date</th>
                    <th class="p-3 text-left">Status</th>
                </tr>

            </thead>

            <tbody>

                @forelse($arrears as $item)

                    <tr class="border-b hover:bg-gray-50">

                        {{-- MEMBER --}}
                        <td class="p-3 font-medium">
                            {{ $item->borrower->full_name }}
                        </td>

                        {{-- EXPECTED --}}
                        <td class="p-3">
                            KES {{ number_format($item->expected_amount) }}
                        </td>

                        {{-- PAID --}}
                        <td class="p-3 text-green-700 font-semibold">
                            KES {{ number_format($item->paid_amount) }}
                        </td>

                        {{-- BALANCE --}}
                        <td class="p-3 text-red-600 font-semibold">
                            KES {{ number_format($item->balance) }}
                        </td>

                        {{-- PENALTY --}}
                        <td class="p-3 text-yellow-700 font-semibold">
                            KES {{ number_format($item->penalty) }}
                        </td>

                        {{-- DUE DATE --}}
                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($item->due_date)->format('d M Y') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="p-3">

                            @if($item->status == 'paid')

                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    PAID
                                </span>

                            @elseif($item->status == 'partial')

                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                    PARTIAL
                                </span>

                            @elseif($item->status == 'overdue')

                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                    OVERDUE
                                </span>

                            @else

                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                    PENDING
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="p-6 text-center text-gray-400">

                            No arrears records found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
