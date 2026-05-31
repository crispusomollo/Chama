@extends('layouts.erp')

@section('title', 'Contribution Ledger')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Contribution Ledger
            </h2>

            <p class="text-sm text-gray-500">
                Reporting, exports, statements & audit center
            </p>
        </div>

        <a href="{{ route('contributions.index') }}"
           class="bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Back
        </a>

    </div>



    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

</div>


    {{-- FILTERS --}}
    <div class="bg-white rounded-xl shadow p-4">

        <form method="GET"
              class="grid grid-cols-1 md:grid-cols-6 gap-4">

            {{-- MEMBER --}}
            <div class="relative">

                <input
                    type="text"
                    id="memberSearch"
                    placeholder="Search member..."
                    class="border rounded-lg px-3 py-2 w-64"
                    autocomplete="off">

                <input
                    type="hidden"
                    name="borrower_id"
                    id="borrower_id">

                <div id="memberResults"
                    class="absolute z-50 bg-white border rounded-lg shadow w-full mt-1 hidden max-h-60 overflow-y-auto">

                    @foreach($borrowers as $b)

                <div
                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer member-option"
                    data-id="{{ $b->id }}"
                    data-name="{{ $b->full_name }}">

                {{ $b->full_name }}

            </div>

        @endforeach

    </div>

</div>

            {{-- STATUS --}}
            <select name="status"
                    class="border rounded-lg p-2">

                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="partial">Partial</option>
                <option value="pending">Pending</option>

            </select>


            {{-- MONTH --}}
            <input type="month"
                   name="month"
                   value="{{ request('month') }}"
                   class="border rounded-lg p-2">

            {{-- FROM --}}
            <input type="date"
                   name="from"
                   value="{{ request('from') }}"
                   class="border rounded-lg p-2">

            {{-- TO --}}
            <input type="date"
                   name="to"
                   value="{{ request('to') }}"
                   class="border rounded-lg p-2">

            <div class="md:col-span-6 flex gap-2">

                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Apply Filters
                </button>

                <a href="{{ route('contributions.ledger') }}"
                   class="bg-gray-200 px-4 py-2 rounded-lg">
                    Reset
                </a>

            </div>

        </form>

        <div class="flex justify-end mt-4">

    <div class="flex justify-end gap-3 mt-4">

    {{-- EXCEL --}}
    <a href="{{ route('contributions.export.excel', request()->query()) }}"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl shadow">
        Export Raw Excel
    </a>

    <a href="{{ route('contributions.export.styled', request()->query()) }}"
       class="bg-emerald-700 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-800">
        Export Styled Excel
    </a>

    {{-- PDF --}}
    <a href="{{ route('contributions.export.pdf', request()->query()) }}"
       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl shadow">

        Export PDF

    </a>

</div>

</div>

    </div>

    

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">

                <tr>
                    <th class="p-3 text-left">Member</th>
                    <th class="p-3 text-left">Amount</th>
                    <th class="p-3 text-left">Shares</th>
                    <th class="p-3 text-left">Insurance</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse($contributions as $c)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-3">
                            {{ $c->borrower->full_name }}
                        </td>

                        <td class="p-3">
                            KES {{ number_format($c->amount) }}
                        </td>

                        <td class="p-3">
                        <span class="font-semibold text-green-600">
                            KES {{ number_format($c->shares_amount) }}
                        </span>
                        </td>

                        <td class="p-3">
                        <span class="font-semibold text-blue-600">
                            KES {{ number_format($c->insurance_amount) }}
                        </span>
                        </td>

                        <td class="p-3 uppercase text-xs">
                            {{ $c->type }}
                        </td>

                        <td class="p-3">

                            <span class="
                                px-2 py-1 rounded-full text-xs

                                @if($c->status == 'paid')
                                    bg-green-100 text-green-700
                                @elseif($c->status == 'partial')
                                    bg-yellow-100 text-yellow-700
                                @else
                                    bg-red-100 text-red-700
                                @endif
                            ">

                                {{ strtoupper($c->status) }}

                            </span>

                        </td>

                        <td class="p-3">
                            {{ $c->contribution_date }}
                        </td>

                        <td class="p-3">
                            <a href="{{ route('contributions.member.statement', $c->borrower_id) }}"
                                target="_blank" class="text-blue-600 hover:underline">

                                Statement

                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="p-6 text-center text-gray-400">

                            No contributions record found

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div>
        {{ $contributions->links() }}
    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('memberSearch');

    const resultsBox = document.getElementById('memberResults');

    const hiddenInput = document.getElementById('borrower_id');

    const options = document.querySelectorAll('.member-option');

    searchInput.addEventListener('focus', () => {
        resultsBox.classList.remove('hidden');
    });

    searchInput.addEventListener('keyup', function () {

        let value = this.value.toLowerCase();

        options.forEach(option => {

            let text = option.dataset.name.toLowerCase();

            option.style.display =
                text.includes(value)
                ? 'block'
                : 'none';

        });

    });

    options.forEach(option => {

        option.addEventListener('click', function () {

            searchInput.value = this.dataset.name;

            hiddenInput.value = this.dataset.id;

            resultsBox.classList.add('hidden');

        });

    });

    document.addEventListener('click', function (e) {

        if (!e.target.closest('.relative')) {
            resultsBox.classList.add('hidden');
        }

    });

});

</script>


@endsection
