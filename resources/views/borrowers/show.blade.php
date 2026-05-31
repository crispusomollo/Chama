@extends('layouts.erp')

@section('title', 'Member Profile')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                {{ $borrower->full_name }}
            </h2>

            <p class="text-gray-500">
                Member Financial Profile
            </p>

        </div>

        <a
            href="{{ route('borrowers.index') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
        >
            Back To Members
        </a>

    </div>

    <!-- PROFILE CARD -->
    <div class="bg-white rounded-xl shadow p-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div>

                <p class="text-sm text-gray-500">
                    Phone
                </p>

                <p class="font-semibold">
                    {{ $borrower->contact_no }}
                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Email
                </p>

                <p class="font-semibold">
                    {{ $borrower->email ?? 'N/A' }}
                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Address
                </p>

                <p class="font-semibold">
                    {{ $borrower->address ?? 'N/A' }}
                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Member Since
                </p>

                <p class="font-semibold">
                    {{ $borrower->created_at->format('d M Y') }}
                </p>

            </div>

        </div>

    </div>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Contributions
            </p>

            <h3 class="text-2xl font-bold text-green-600">

                KES {{ number_format($totalContributions) }}

            </h3>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Total Borrowed
            </p>

            <h3 class="text-2xl font-bold text-blue-600">

                KES {{ number_format($totalLoans) }}

            </h3>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Outstanding
            </p>

            <h3 class="text-2xl font-bold text-red-600">

                KES {{ number_format($totalOutstanding) }}

            </h3>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Total Repaid
            </p>

            <h3 class="text-2xl font-bold text-indigo-600">

                KES {{ number_format($totalRepaid) }}

            </h3>

        </div>

    </div>

    <!-- SECOND KPI ROW -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Active Loans
            </p>

            <h3 class="text-2xl font-bold text-blue-600">
                {{ $activeLoans }}
            </h3>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Completed Loans
            </p>

            <h3 class="text-2xl font-bold text-green-600">
                {{ $completedLoans }}
            </h3>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Overdue Loans
            </p>

            <h3 class="text-2xl font-bold text-red-600">
                {{ $overdueLoans }}
            </h3>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-sm text-gray-500">
                Credit Score
            </p>

            <h3 class="text-2xl font-bold">

                {{ $creditScore }}/100

            </h3>

            <p class="mt-2">

                @if($riskGrade == 'LOW RISK')

                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                        LOW RISK
                    </span>

                @elseif($riskGrade == 'MEDIUM RISK')

                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                        MEDIUM RISK
                    </span>

                @else

                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                        HIGH RISK
                    </span>

                @endif

            </p>

        </div>

    </div>

    <!-- LOAN HISTORY -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b">

            <h3 class="font-semibold text-gray-700">
                Loan History
            </h3>

        </div>

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>

                    <th class="px-4 py-3 text-left">
                        Amount
                    </th>

                    <th class="px-4 py-3 text-left">
                        Interest
                    </th>

                    <th class="px-4 py-3 text-left">
                        Balance
                    </th>

                    <th class="px-4 py-3 text-left">
                        Status
                    </th>

                    <th class="px-4 py-3 text-left">
                        Due Date
                    </th>

                    <th class="px-4 py-3 text-left">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($loans as $loan)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3">
                        KES {{ number_format($loan->amount) }}
                    </td>

                    <td class="px-4 py-3">
                        KES {{ number_format($loan->interest_amount) }}
                    </td>

                    <td class="px-4 py-3 text-red-600 font-semibold">
                        KES {{ number_format($loan->balance) }}
                    </td>

                    <td class="px-4 py-3">

                        @if($loan->loan_status == 'completed')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                COMPLETED
                            </span>

                        @elseif($loan->loan_status == 'overdue')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                OVERDUE
                            </span>

                        @else

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                ACTIVE
                            </span>

                        @endif

                    </td>

                    <td class="px-4 py-3">

                        {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}

                    </td>

                    <td class="px-4 py-3">

                        <a
                            href="{{ route('repayments.show', $loan->id) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                        >
                            Statement
                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">

                        No loans found.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    <!-- CONTRIBUTION HISTORY -->
<div class="bg-white rounded-xl shadow overflow-hidden mt-6">

    <div class="p-4 border-b">
        <h3 class="font-semibold text-gray-700">
            Contribution History
        </h3>
    </div>

    <table class="w-full text-sm">

        <thead class="bg-gray-100 text-gray-600">

            <tr>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Amount</th>
                <th class="px-4 py-3 text-left">Period</th>
            </tr>

        </thead>

        <tbody>

        @forelse($contributions as $c)

            <tr class="border-b hover:bg-gray-50">

                <td class="px-4 py-3">
                    {{ $c->created_at->format('d M Y') }}
                </td>

                <td class="px-4 py-3 font-semibold text-green-600">
                    KES {{ number_format($c->amount) }}
                </td>

                <td class="px-4 py-3 text-gray-500">
                    {{ $c->period ?? 'N/A' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                    No contributions found.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>


<!-- REPAYMENT HISTORY -->
<div class="bg-white rounded-xl shadow overflow-hidden mt-6">

    <div class="p-4 border-b">
        <h3 class="font-semibold text-gray-700">
            Loan Repayment History
        </h3>
    </div>

    <table class="w-full text-sm">

        <thead class="bg-gray-100 text-gray-600">

            <tr>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Loan</th>
                <th class="px-4 py-3 text-left">Amount</th>
                <th class="px-4 py-3 text-left">Method</th>
                <th class="px-4 py-3 text-left">Reference</th>
            </tr>

        </thead>

        <tbody>

        @forelse($repayments as $r)

            <tr class="border-b hover:bg-gray-50">

                <td class="px-4 py-3">
                    {{ $r->created_at->format('d M Y') }}
                </td>

                <td class="px-4 py-3">
                    KES {{ number_format($r->loan->amount ?? 0) }}
                </td>

                <td class="px-4 py-3 font-semibold text-blue-600">
                    KES {{ number_format($r->amount) }}
                </td>

                <td class="px-4 py-3 uppercase text-gray-600">
                    {{ $r->payment_method }}
                </td>

                <td class="px-4 py-3 text-gray-500">
                    {{ $r->reference_number }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                    No repayments found.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>



</div>

@endsection