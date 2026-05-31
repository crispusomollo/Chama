<x-app-layout>

<div class="max-w-3xl mx-auto py-8">

    <h2 class="text-2xl font-bold mb-6">
        Edit Loan
    </h2>

    <form method="POST"
          action="{{ route('loans.update', $loan->id) }}"
          class="bg-white p-6 rounded shadow space-y-4">

        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Borrower</label>

            <select name="borrower_id"
                    class="w-full border rounded p-2">

                @foreach($borrowers as $b)
                    <option value="{{ $b->id }}"
                        {{ $loan->borrower_id == $b->id ? 'selected' : '' }}>

                        {{ $b->firstname }} {{ $b->lastname }}

                    </option>
                @endforeach

            </select>
        </div>

        <div>
            <label class="block mb-1">Amount</label>

            <input type="number"
                   name="amount"
                   value="{{ $loan->amount }}"
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block mb-1">Interest Rate</label>

            <input type="number"
                   step="0.01"
                   name="interest_rate"
                   value="{{ $loan->interest_rate }}"
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block mb-1">Status</label>

            <select name="status"
                    class="w-full border rounded p-2">

                <option value="pending" {{ $loan->status == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="active" {{ $loan->status == 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="paid" {{ $loan->status == 'paid' ? 'selected' : '' }}>
                    Paid
                </option>

                <option value="overdue" {{ $loan->status == 'overdue' ? 'selected' : '' }}>
                    Overdue
                </option>

            </select>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update Loan
        </button>

    </form>

</div>

</x-app-layout>
