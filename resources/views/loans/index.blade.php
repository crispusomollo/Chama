@extends('layouts.erp')

@section('title', 'Loans Management')

@section('content')

<div x-data="{ tab: 'applications', openCreate: false }" class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-700">
            Loan Registry
        </h2>

        <!-- TEMP TEST BUTTON (keep for now) -->
        <button
            @click="openCreate = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow"
        >
            + New Loan Application
        </button>
    </div>

    <!-- KPI -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total</p>
            <h3 class="text-xl font-bold">{{ $totalLoans }}</h3>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Applications</p>
            <h3 class="text-xl font-bold text-yellow-600">{{ $pendingApplications }}</h3>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Review Queue</p>
            <h3 class="text-xl font-bold text-indigo-600">{{ $reviewCount }}</h3>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Active</p>
            <h3 class="text-xl font-bold text-blue-600">{{ $activeLoansCount }}</h3>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">
            Performing
            </p>

            <h3 class="text-xl font-bold text-green-600">
                {{ $performingLoans }}
            </h3>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">
            Non-Performing
            </p>

            <h3 class="text-xl font-bold text-red-600">
                {{ $nonPerformingLoans }}
            </h3>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Overdue</p>
            <h3 class="text-xl font-bold text-red-600">{{ $overdueLoansCount }}</h3>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Disbursed</p>
            <h3 class="text-xl font-bold text-purple-600">
                KES {{ number_format($totalDisbursed) }}
            </h3>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">

            <p class="text-sm text-gray-500">
                Penalties
            </p>

            <h3 class="text-xl font-bold text-red-600">

            KES {{ number_format($totalPenalties) }}

            </h3>

        </div>

    </div>

    <!-- TABS -->
    <div class="flex gap-3 border-b pb-2 text-sm">

        <button @click="tab = 'applications'"
            :class="tab === 'applications' ? 'border-b-2 border-blue-600 text-blue-600' : ''"
            class="px-3 py-2">
            Loan Applications
        </button>

        <button @click="tab = 'review'"
            :class="tab === 'review' ? 'border-b-2 border-indigo-600 text-indigo-600' : ''"
            class="px-3 py-2">
            Review Queue
        </button>

        <button @click="tab = 'active'"
            :class="tab === 'active' ? 'border-b-2 border-green-600 text-green-600' : ''"
            class="px-3 py-2">
            Active Loans
        </button>

        <button @click="tab = 'overdue'"
            :class="tab === 'overdue' ? 'border-b-2 border-red-600 text-red-600' : ''"
            class="px-3 py-2">
            Overdue Loans
        </button>

        <button @click="tab = 'completed'"
            :class="tab === 'completed' ? 'border-b-2 border-gray-600 text-gray-600' : ''"
            class="px-3 py-2">
            Completed Loans
        </button>

    </div>

    
    <!-- APPLICATIONS -->
<div
    x-show="tab === 'applications'"
    class="bg-white rounded-xl shadow overflow-hidden"
>

    <div class="p-4 border-b flex justify-between items-center">

        <div>

            <h3 class="font-semibold text-gray-700">
                Loan Applications Queue
            </h3>

            <p class="text-sm text-gray-500">
                Pending applications awaiting review decision
            </p>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>

                    <th class="px-4 py-3 text-left">
                        Borrower
                    </th>

                    <th class="px-4 py-3 text-left">
                        Amount
                    </th>

                    <th class="px-4 py-3 text-left">
                        Date Applied
                    </th>

                    <th class="px-4 py-3 text-left">
                        Score
                    </th>

                    <th class="px-4 py-3 text-left">
                        Recommendation
                    </th>

                    <th class="px-4 py-3 text-left">
                        Status
                    </th>

                    <th class="px-4 py-3 text-left">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($applications as $loan)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3 font-medium">
                        {{ $loan->borrower->full_name }}
                    </td>

                    <td class="px-4 py-3">
                        KES {{ number_format($loan->amount) }}
                    </td>

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                    </td>

                    <!-- SCORE BADGE -->
                    <td class="px-4 py-3">

                        @if($loan->credit_score >= 80)

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $loan->credit_score }} - EXCELLENT
                            </span>

                        @elseif($loan->credit_score >= 60)

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $loan->credit_score }} - FAIR
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $loan->credit_score }} - RISK
                            </span>

                        @endif

                    </td>

                    <!-- RECOMMENDATION BADGE -->
                    <td class="px-4 py-3">

                        @if($loan->system_recommendation == 'auto_approved')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                AUTO APPROVED
                            </span>

                        @elseif($loan->system_recommendation == 'auto_rejected')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                AUTO REJECTED
                            </span>

                        @else

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                UNDER REVIEW
                            </span>

                        @endif

                    </td>

                    <!-- STATUS -->
                    <td class="px-4 py-3">

                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ strtoupper($loan->application_status) }}
                        </span>

                    </td>

                    <!-- ACTIONS -->
                    <td class="px-4 py-3">

                        <div class="flex gap-2">

                            <form
                                action="{{ route('loans.approve', $loan->id) }}"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                >
                                    Approve
                                </button>

                            </form>

                            <form
                                action="{{ route('loans.reject', $loan->id) }}"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                >
                                    Reject
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="px-4 py-6 text-center text-gray-500"
                    >
                        No applications found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


   <!-- REVIEW QUEUE -->
<div
    x-show="tab === 'review'"
    class="bg-white rounded-xl shadow overflow-hidden"
>

    <div class="p-4 border-b flex justify-between items-center">

        <div>

            <h3 class="font-semibold text-gray-700">
                Treasurer Review Queue
            </h3>

            <p class="text-sm text-gray-500">
                Approved loans awaiting disbursement
            </p>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>

                    <th class="px-4 py-3 text-left">
                        Borrower
                    </th>

                    <th class="px-4 py-3 text-left">
                        Amount Applied
                    </th>

                    <th class="px-4 py-3 text-left">
                        Date Applied
                    </th>

                    <th class="px-4 py-3 text-left">
                        Status
                    </th>

                    <th class="px-4 py-3 text-left">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($reviewQueue as $loan)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3 font-medium">
                        {{ $loan->borrower->full_name }}
                    </td>

                    <td class="px-4 py-3">
                        KES {{ number_format($loan->amount) }}
                    </td>

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                    </td>

                    <td class="px-4 py-3">

                        @if($loan->application_status == 'approved')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                APPROVED
                            </span>

                        @elseif($loan->application_status == 'rejected')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                REJECTED
                            </span>

                        @else

                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ strtoupper($loan->application_status) }}
                            </span>

                        @endif

                    </td>

                    <td class="px-4 py-3">


    <!-- DISBURSE -->
    @if($loan->canDisburse())

        <form
            action="{{ route('loans.disburse', $loan->id) }}"
            method="POST"
        >

            @csrf
            @method('PATCH')

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs"
            >
                Disburse
            </button>

        </form>

        @else

                            <span class="text-gray-400 text-xs">
                                Not Eligible
                            </span>

    @endif


                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="px-4 py-6 text-center text-gray-500"
                    >
                        No loans awaiting disbursement.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

    <!-- ACTIVE LOANS -->
<div x-show="tab === 'active'" class="bg-white rounded-xl shadow overflow-hidden">

    <div class="p-4 border-b flex justify-between items-center">

        <div>
            <h3 class="font-semibold text-gray-700">
                Active Loan Portfolio
            </h3>

            <p class="text-sm text-gray-500">
                Disbursed and currently servicing loans
            </p>
        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>

                    <th class="px-4 py-3 text-left">Borrower</th>

                    <th class="px-4 py-3 text-left">Amount</th>

                    <th class="px-4 py-3 text-left">Interest</th>

                    <th class="px-4 py-3 text-left">Total Payable</th>

                    <th class="px-4 py-3 text-left">Balance</th>

                    <th class="px-4 py-3 text-left">Due Date</th>

                    <th class="px-4 py-3 text-left">Penalty</th>

                    <th class="px-4 py-3 text-left">Actions</th>

                </tr>

            </thead>

            <tbody>

            @forelse($activeLoans as $loan)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3 font-medium">
                        {{ $loan->borrower->full_name }}
                    </td>

                    <td class="px-4 py-3">
                        KES {{ number_format($loan->amount) }}
                    </td>

                    <td class="px-4 py-3 text-indigo-600">
                        KES {{ number_format($loan->interest_amount) }}
                    </td>

                    <td class="px-4 py-3 font-semibold">
                        KES {{ number_format($loan->total_payable) }}
                    </td>

                    <td class="px-4 py-3 text-red-600 font-semibold">
                        KES {{ number_format($loan->balance) }}
                    </td>

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                    </td>

                    <td class="px-4 py-3 text-orange-600 font-semibold">

                        @if($loan->penalty_amount > 0)

                            KES {{ number_format($loan->penalty_amount) }}

                        @else

                            -

                        @endif

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex gap-2">

                            @if(!$loan->isCompleted())

                                <a
                                    href="{{ route('repayments.create', ['loan_id' => $loan->id]) }}"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                >
                                    Repay
                                </a>

                            @endif

                            <a
                                href="{{ route('repayments.show', $loan->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                            >
                                Statement
                            </a>

                            <a
        href="{{ route('loans.schedule', $loan->id) }}"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs"
    >
        Schedule
    </a>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                        No active loans found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

    <!-- OVERDUE LOANS -->
    <div
        x-show="tab === 'overdue'"
        class="bg-white rounded-xl shadow overflow-hidden"
    >

        <div class="p-4 border-b flex justify-between items-center">

            <div>

                <h3 class="font-semibold text-red-600">
                    Overdue Loans
                </h3>

                <p class="text-sm text-gray-500">
                    Loans requiring collections follow-up
                </p>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-red-50 text-gray-700">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Borrower
                        </th>

                        <th class="px-4 py-3 text-left">
                            Amount Borrowed
                        </th>

                        <th class="px-4 py-3 text-left">
                            Amount Paid
                        </th>

                        <th class="px-4 py-3 text-left">
                            Pending Balance
                        </th>

                        <th class="px-4 py-3 text-left">
                            Last Repayment
                        </th>

                        <th class="px-4 py-3 text-left">
                            Penalty
                        </th>

                        <th class="px-4 py-3 text-left">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($overdueLoans as $loan)

                <tr class="border-b hover:bg-red-50">

                    <td class="px-4 py-3 font-medium">
                        {{ $loan->borrower->full_name }}
                    </td>

                    <td class="px-4 py-3">
                        KES {{ number_format($loan->amount) }}
                    </td>

                    <td class="px-4 py-3 text-green-600 font-semibold">
                        KES {{ number_format($loan->totalRepaid()) }}
                    </td>

                    <td class="px-4 py-3 text-red-600 font-bold">
                        KES {{ number_format($loan->balance) }}
                    </td>

                    <td class="px-4 py-3">

                        @if($loan->lastRepayment())

                            {{ \Carbon\Carbon::parse(
                                $loan->lastRepayment()->payment_date
                            )->format('d M Y') }}

                        @else

                            <span class="text-gray-400">
                                No repayment
                            </span>

                        @endif

                    </td>

                    <td class="px-4 py-3 text-orange-600 font-semibold">

                        @if($loan->penalty_amount > 0)

                            KES {{ number_format($loan->penalty_amount) }}

                        @else

                            -

                        @endif

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex gap-2">

                            <a
                                href="{{ route('repayments.show', $loan->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                            >
                                Statement
                            </a>

                            @if(!$loan->isCompleted())

                                <a
                                    href="{{ route('repayments.create', ['loan_id' => $loan->id]) }}"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                >
                                    Repay
                                </a>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="px-4 py-6 text-center text-gray-500"
                    >
                        No overdue loans found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

    <!-- COMPLETED LOANS -->
<div
    x-show="tab === 'completed'"
    class="bg-white rounded-xl shadow overflow-hidden"
>

    <div class="p-4 border-b flex justify-between items-center">

        <div>

            <h3 class="font-semibold text-green-600">
                Completed Loans
            </h3>

            <p class="text-sm text-gray-500">
                Fully serviced and closed loans
            </p>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-green-50 text-gray-700">

                <tr>

                    <th class="px-4 py-3 text-left">
                        Borrower
                    </th>

                    <th class="px-4 py-3 text-left">
                        Amount Borrowed
                    </th>

                    <th class="px-4 py-3 text-left">
                        Total Paid
                    </th>

                    <th class="px-4 py-3 text-left">
                        Duration
                    </th>

                    <th class="px-4 py-3 text-left">
                        Status
                    </th>

                    <th class="px-4 py-3 text-left">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($completedLoans as $loan)

                <tr class="border-b hover:bg-green-50">

                    <td class="px-4 py-3 font-medium">
                        {{ $loan->borrower->full_name }}
                    </td>

                    <td class="px-4 py-3">
                        KES {{ number_format($loan->amount) }}
                    </td>

                    <td class="px-4 py-3 text-green-600 font-bold">
                        KES {{ number_format($loan->totalRepaid()) }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $loan->duration_months }} Months
                    </td>

                    <td class="px-4 py-3">

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                            COMPLETED
                        </span>

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

                    <td
                        colspan="6"
                        class="px-4 py-6 text-center text-gray-500"
                    >
                        No completed loans found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


<!-- CREATE LOAN MODAL -->
<div
    x-show="openCreate"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    x-cloak
>
    <div class="bg-white w-full max-w-lg rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">New Loan Application</h2>

            <button @click="openCreate = false" class="text-gray-500 text-xl">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('loans.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm">Borrower</label>
                <select name="borrower_id" class="w-full border rounded p-2">
                    @foreach($borrowers as $borrower)
                        <option value="{{ $borrower->id }}">
                            {{ $borrower->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm">Amount</label>
                <input name="amount" type="number" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="text-sm">Interest Rate (%)</label>
                <input name="interest_rate" type="number" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="text-sm">Duration (Months)</label>
                <input name="duration_months" type="number" class="w-full border rounded p-2">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                        @click="openCreate = false"
                        class="px-4 py-2 border rounded">
                    Cancel
                </button>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Submit Application
                </button>
            </div>
        </form>

    </div>
</div>    


</div>

@endsection