@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- PAGE TITLE -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Dashboard
        </h1>

        <p class="text-gray-500">
            Contribution summary for {{ $currentMonth }}
        </p>
    </div>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- TOTAL CONTRIBUTIONS -->
        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-sm text-gray-500 mb-2">
                Total Contributions
            </p>

            <h2 class="text-3xl font-bold text-green-600">
                KES {{ number_format($totalContributions) }}
            </h2>
        </div>

        <!-- PAID MEMBERS -->
        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-sm text-gray-500 mb-2">
                Paid Members
            </p>

            <h2 class="text-3xl font-bold text-blue-600">
                {{ $paidCount }}
            </h2>
        </div>

        <!-- PENDING MEMBERS -->
        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-sm text-gray-500 mb-2">
                Pending Members
            </p>

            <h2 class="text-3xl font-bold text-red-600">
                {{ $pendingCount }}
            </h2>
        </div>

    </div>

    <!-- TABLES -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- PAID MEMBERS -->
        <div class="bg-white shadow rounded-xl p-6">

            <h2 class="text-xl font-bold mb-4 text-green-700">
                Paid Members
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Name</th>
                            <th class="text-left py-2">Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($paidMembers as $member)

                            <tr class="border-b">
                                <td class="py-2">
                                    {{ $member->borrower->firstname }}
                                    {{ $member->borrower->lastname }}
                                </td>

                                <td class="py-2">
                                    KES {{ number_format($member->amount) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="2" class="py-3 text-gray-500">
                                    No paid members yet.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <!-- PENDING MEMBERS -->
        <div class="bg-white shadow rounded-xl p-6">

            <h2 class="text-xl font-bold mb-4 text-red-700">
                Pending Members
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Name</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pendingMembers as $member)

                            <tr class="border-b">
                                <td class="py-2">
                                    {{ $member->firstname }}
                                    {{ $member->lastname }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td class="py-3 text-gray-500">
                                    No pending members.
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
