@extends('layouts.app')

@section('content')

<div x-data="{ openCreate: false, openEdit: false, selectedLoan: null }">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Loans</h2>

        <button
            @click="openCreate = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + New Loan
        </button>
    </div>


<!-- KPI CARDS -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Total Loans</p>
        <h3 class="text-2xl font-bold">
            {{ $totalLoans }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Active Loans</p>
        <h3 class="text-2xl font-bold text-blue-600">
            {{ $activeLoans }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Paid Loans</p>
        <h3 class="text-2xl font-bold text-green-600">
            {{ $paidLoans }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Overdue Loans</p>
        <h3 class="text-2xl font-bold text-red-600">
            {{ $overdueLoans }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Total Disbursed</p>
        <h3 class="text-2xl font-bold text-purple-600">
            KES {{ number_format($totalDisbursed) }}
        </h3>
    </div>

</div>


    <!-- TABLE -->
    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Borrower</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Interest</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
            @foreach($loans as $loan)
                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">
                        {{ $loan->borrower->full_name }}
                    </td>

                    <td class="p-3">
                        {{ $loan->amount }}
                    </td>

                    <td class="p-3">
                        {{ $loan->interest_rate }}%
                    </td>

                    <td class="p-3">
                        <span class="px-2 py-1 text-xs rounded
                            {{ $loan->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200' }}">
                            {{ $loan->status }}
                        </span>
                    </td>

                    <!-- ACTIONS -->
                    <td class="p-3 text-right space-x-2">

                        <!-- EDIT -->
                        <button
    @click="
        selectedLoan = JSON.parse('{{ json_encode($loan) }}');
        openEdit = true;
    "
    class="text-yellow-600 hover:text-yellow-800">
    ✏️
</button>

                        <!-- DELETE -->
                        <form action="{{ route('loans.destroy', $loan->id) }}"
                              method="POST"
                              class="inline">

                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Delete this loan?')"
                                    class="text-red-600 hover:text-red-800">
                                🗑
                            </button>
                        </form>

                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>


<!-- CREATE LOAN MODAL -->
<div x-show="openCreate"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

    <div class="bg-white p-6 rounded-lg w-full max-w-lg"
         @click.away="openCreate = false">

        <h2 class="text-xl font-bold mb-4">Create Loan</h2>

        <form method="POST" action="{{ route('loans.store') }}" class="space-y-3">
            @csrf

            <!-- Borrower -->
            <select name="borrower_id" class="w-full border p-2 rounded">
                <option value="">Select Borrower</option>
                @foreach($borrowers as $b)
                    <option value="{{ $b->id }}">
                        {{ $b->full_name }}
                    </option>
                @endforeach
            </select>

            <input name="amount" placeholder="Amount"
                   class="w-full border p-2 rounded">

            <input name="interest_rate" placeholder="Interest %"
                   class="w-full border p-2 rounded">

            <input type="date" name="loan_date"
                   class="w-full border p-2 rounded">

            <input type="date" name="due_date"
                   class="w-full border p-2 rounded">

            <div class="flex justify-end gap-2">
                <button type="button"
                        @click="openCreate = false"
                        class="px-4 py-2 bg-gray-300 rounded">
                    Cancel
                </button>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>


<!-- EDIT LOAN MODAL -->
<div x-show="openEdit"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

    <div class="bg-white p-6 rounded-lg w-full max-w-lg"
         @click.away="openEdit = false">

        <h2 class="text-xl font-bold mb-4">Edit Loan</h2>

        <form method="POST"
              :action="'/loans/' + selectedLoan.id"
              class="space-y-3">

            @csrf
            @method('PUT')

            <input type="hidden" name="id" :value="selectedLoan.id">

            <input name="amount"
                   x-model="selectedLoan.amount"
                   class="w-full border p-2 rounded">

            <input name="interest_rate"
                   x-model="selectedLoan.interest_rate"
                   class="w-full border p-2 rounded">

            <input name="loan_date"
                   x-model="selectedLoan.loan_date"
                   type="date"
                   class="w-full border p-2 rounded">

            <input name="due_date"
                   x-model="selectedLoan.due_date"
                   type="date"
                   class="w-full border p-2 rounded">

            <select name="status"
                    x-model="selectedLoan.status"
                    class="w-full border p-2 rounded">
                <option value="active">Active</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
            </select>

            <div class="flex justify-end gap-2">
                <button type="button"
                        @click="openEdit = false"
                        class="px-4 py-2 bg-gray-300 rounded">
                    Cancel
                </button>

                <button class="px-4 py-2 bg-yellow-600 text-white rounded">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>



    </div>

</div>
@endsection
