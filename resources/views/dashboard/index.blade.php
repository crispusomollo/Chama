@extends('layouts.erp')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- KPI ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Total Contributions</p>
            <h2 class="text-2xl font-bold text-green-600">
                KES {{ number_format($totalContributions) }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Paid Members</p>
            <h2 class="text-2xl font-bold text-blue-600">
                {{ $paidCount }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Pending Members</p>
            <h2 class="text-2xl font-bold text-red-600">
                {{ $pendingCount }}
            </h2>
        </div>

    </div>

    {{-- TABLE GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- PAID --}}
        <div class="bg-white rounded-xl shadow p-5">

            <h3 class="font-semibold text-green-700 mb-4">Paid Members</h3>

            <table class="w-full text-sm">
                <thead class="text-left text-gray-500 border-b">
                    <tr>
                        <th class="py-2">Name</th>
                        <th class="py-2">Amount</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($paidMembers as $member)
                    <tr class="border-b">
                        <td class="py-2">
                            {{ $member->borrower->firstname }} {{ $member->borrower->lastname }}
                        </td>
                        <td class="py-2">
                            KES {{ number_format($member->amount) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="py-3 text-gray-400">
                            No data
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

        {{-- PENDING --}}
        <div class="bg-white rounded-xl shadow p-5">

            <h3 class="font-semibold text-red-700 mb-4">Pending Members</h3>

            <table class="w-full text-sm">
                <tbody>
                @forelse($pendingMembers as $member)
                    <tr class="border-b">
                        <td class="py-2">
                            {{ $member->firstname }} {{ $member->lastname }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-3 text-gray-400">
                            No data
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

    </div>

</div>

@endsection
