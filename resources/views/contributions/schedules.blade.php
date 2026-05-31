@extends('layouts.erp')

@section('title', 'Contribution Schedules')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-700">
            Contribution Compliance Center
        </h2>

        <a href="{{ route('contributions.index') }}"
           class="bg-gray-800 text-white px-4 py-2 rounded-lg">
            Back
        </a>
    </div>

    {{-- KPI ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Expected</p>

            <p class="text-2xl font-bold text-blue-600">
                KES {{ number_format($totalExpected) }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Paid</p>

            <p class="text-2xl font-bold text-green-600">
                KES {{ number_format($totalPaid) }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Outstanding</p>

            <p class="text-2xl font-bold text-red-600">
                KES {{ number_format($totalBalance) }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Penalties</p>

            <p class="text-2xl font-bold text-yellow-600">
                KES {{ number_format($totalPenalties) }}
            </p>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-700">
                Monthly Contribution Schedules
            </h3>
        </div>

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3 text-left">Member</th>
                    <th class="p-3 text-left">Period</th>
                    <th class="p-3 text-left">Expected</th>
                    <th class="p-3 text-left">Paid</th>
                    <th class="p-3 text-left">Balance</th>
                    <th class="p-3 text-left">Penalty</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>

            <tbody>

            @forelse($schedules as $s)

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3 font-medium">
                        {{ $s->borrower->full_name }}
                    </td>

                    <td class="p-3">
                        {{ $s->period }}
                    </td>

                    <td class="p-3">
                        KES {{ number_format($s->expected_amount) }}
                    </td>

                    <td class="p-3 text-green-600 font-semibold">
                        KES {{ number_format($s->paid_amount) }}
                    </td>

                    <td class="p-3 text-red-600 font-semibold">
                        KES {{ number_format($s->balance) }}
                    </td>

                    <td class="p-3 text-yellow-600 font-semibold">
                        KES {{ number_format($s->penalty) }}
                    </td>

                    <td class="p-3">

                        @if($s->status == 'paid')

                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">
                                PAID
                            </span>

                        @elseif($s->status == 'partial')

                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">
                                PARTIAL
                            </span>

                        @elseif($s->status == 'overdue')

                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">
                                OVERDUE
                            </span>

                        @else

                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-semibold">
                                PENDING
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7"
                        class="p-6 text-center text-gray-400">
                        No schedules found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div>
        {{ $schedules->links() }}
    </div>

</div>

@endsection
