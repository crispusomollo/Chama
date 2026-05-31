@extends('layouts.erp')

@section('title', 'Contributions')

@section('content')


<div x-data="{
    openCreate: false,
    openEdit: false,
    selectedContribution: null
}" class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    {{-- PAGE TITLE --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Contributions Control Center
        </h2>

        <p class="text-sm text-gray-500">
            Manage member contributions, compliance, and ledger reports
        </p>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="flex flex-wrap items-center gap-3">

        {{-- NEW CONTRIBUTION --}}
        <button
            @click="openCreate = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-sm transition">

            + New Contribution

        </button>

        {{-- COMPLIANCE CENTER --}}
        <a href="{{ route('contributions.schedules') }}"
           class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl shadow-sm transition">

            Compliance Center

        </a>

        {{-- LEDGER --}}
        <a href="{{ route('contributions.ledger') }}"
           class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-xl shadow-sm transition">

            View Ledger

        </a>

        </div>

    </div>


    <!-- Quick Summary Banner -->

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl p-5 shadow">

    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-xl font-bold">
                Contribution Control Center
            </h2>

            <p class="text-sm text-blue-100 mt-1">
                Monitor collections, arrears, member payment activity, and contribution performance.
            </p>
        </div>

        <div class="text-right">
            <p class="text-sm text-blue-100">
                Current Period
            </p>

            <p class="text-2xl font-bold">
                {{ now()->format('F Y') }}
            </p>
        </div>

    </div>

</div>

    {{-- KPI ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">This Month Total</p>
            <p class="text-2xl font-bold text-green-600">
                KES {{ number_format($monthlyTotal) }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Members Paid</p>
            <p class="text-2xl font-bold text-blue-600">
                {{ $paidCount }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Members Pending</p>
            <p class="text-2xl font-bold text-red-600">
                {{ $pendingCount }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Contributions</p>
            <p class="text-2xl font-bold text-gray-800">
                KES {{ number_format($totalContributions) }}
            </p>
        </div>

    </div>


    


{{-- TREASURER ANALYTICS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    {{-- EXPECTED COLLECTIONS --}}
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Expected Collections
        </p>

        <h2 class="text-2xl font-bold text-indigo-600 mt-2">
            KES {{ number_format($expectedCollections) }}
        </h2>

    </div>

    {{-- ACTUAL COLLECTIONS --}}
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Actual Collections
        </p>

        <h2 class="text-2xl font-bold text-green-600 mt-2">
            KES {{ number_format($actualCollections) }}
        </h2>

    </div>

    {{-- COLLECTION RATE --}}
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Collection Rate
        </p>

        <h2 class="text-2xl font-bold text-blue-600 mt-2">
            {{ number_format($collectionRate, 1) }}%
        </h2>

    </div>

    {{-- TOTAL ARREARS --}}
    <div class="bg-white rounded-xl shadow p-5">

        <p class="text-sm text-gray-500">
            Outstanding Arrears
        </p>

        <h2 class="text-2xl font-bold text-red-600 mt-2">
            KES {{ number_format($totalArrearsAmount) }}
        </h2>

    </div>

</div>



{{-- ANALYTICS SECTION --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- EXISTING TREND CHART --}}
    <div class="bg-white rounded-xl shadow p-5">

        <div class="flex justify-between items-center mb-4">

            <div>
                <h3 class="font-semibold text-gray-800">
                    Monthly Contribution Trend
                </h3>

                <p class="text-sm text-gray-500">
                    Contribution performance overview
                </p>
            </div>

        </div>

        <canvas id="monthlyTrendChart"></canvas>

    </div>

    {{-- MONTHLY COLLECTION ANALYTICS --}}
    <div class="bg-white rounded-xl shadow p-5">

        <div class="flex justify-between items-center mb-4">

            <div>
                <h3 class="font-semibold text-gray-800">
                    Monthly Collections Analytics
                </h3>

                <p class="text-sm text-gray-500">
                    Amount collected per period
                </p>
            </div>

        </div>

        <canvas id="monthlyCollectionsChart"></canvas>

    </div>

</div>


<!-- CONTRIBUTIONS SECTION -->

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- PAID MEMBERS -->
    <div class="bg-white rounded-xl shadow p-4">
        <h3 class="font-semibold text-green-600 mb-3">
            Paid This Month
        </h3>

        <ul class="space-y-2">
            @forelse($paidMembers as $m)
                <li class="p-2 bg-green-50 rounded">
                    {{ $m->full_name }}
                </li>
            @empty
                <p class="text-gray-400 text-sm">No payments yet</p>
            @endforelse
        </ul>
    </div>

    <!-- PENDING MEMBERS -->
    <div class="bg-white rounded-xl shadow p-4">
        <h3 class="font-semibold text-red-600 mb-3">
            Pending Contributions
        </h3>

        <ul class="space-y-2">
            @forelse($pendingMembers as $m)
                <li class="p-2 bg-red-50 rounded flex justify-between">
                    <span>{{ $m->full_name }}</span>
                    <span class="text-xs text-red-500">PENDING</span>
                </li>
            @empty
                <p class="text-gray-400 text-sm">Everyone has paid 🎉</p>
            @endforelse
        </ul>
    </div>

</div>


<!-- Top Contributors -->

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-4">

        <div>

            <h2 class="text-lg font-bold text-gray-800">
                Top Contributors
            </h2>

            <p class="text-sm text-gray-500">
                Highest contribution totals across the system.
            </p>

        </div>

    </div>

    <div class="space-y-3">

        @forelse($topContributors as $contributor)

            <div class="flex items-center justify-between border-b pb-3">

                <div>

                    <h3 class="font-semibold text-gray-700">
                        {{ $contributor->borrower->full_name }}
                    </h3>

                    <p class="text-xs text-gray-400">
                        Member Contribution Ranking
                    </p>

                </div>

                <div class="text-right">

                    <h3 class="font-bold text-green-600">
                        KES {{ number_format($contributor->total) }}
                    </h3>

                </div>

            </div>

        @empty

            <p class="text-gray-400 text-sm">
                No contribution analytics available.
            </p>

        @endforelse

    </div>

</div>



    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">Contribution Ledger (Audit Trail)</h3>

            <form method="GET" class="flex gap-2">
                <input name="search"
                       value="{{ request('search') }}"
                       placeholder="Search member..."
                       class="border rounded-lg px-3 py-1 text-sm">

                
                <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-black">
                Search
                </button>
            </form>
        </div>

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">
<tr>
    <th class="p-3 text-left">Member</th>
    <th class="p-3 text-left">Amount</th>
    <th class="p-3 text-left">Shares</th>
    <th class="p-3 text-left">Insurance</th>
    <th class="p-3 text-left">Period</th>
    <th class="p-3 text-left">Type</th>
    <th class="p-3 text-left">Status</th>
    <th class="p-3 text-left">Date</th>
    <th class="p-3 text-right">Actions</th>
</tr>
</thead>

            <tbody>

@forelse($contributions as $c)

<tr class="border-b hover:bg-gray-50">

    <td class="p-3 font-medium">
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

    <td class="p-3">
        {{ $c->period }}
    </td>

    <td class="p-3">
        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
            {{ strtoupper($c->type) }}
        </span>
    </td>

    <td class="p-3">

    @php

        $statusClasses = [

            'paid' =>
                'bg-green-100 text-green-700 border-green-200',

            'partial' =>
                'bg-yellow-100 text-yellow-700 border-yellow-200',

            'pending' =>
                'bg-red-100 text-red-700 border-red-200',

            'overdue' =>
                'bg-red-200 text-red-900 border-red-300',

        ];

    @endphp

    <span class="
        px-3 py-1 rounded-full text-xs font-semibold border
        {{ $statusClasses[$c->status] ?? 'bg-gray-100 text-gray-700' }}
    ">

        {{ strtoupper($c->status) }}

    </span>

</td>

    <td class="p-3">
        {{ \Carbon\Carbon::parse($c->contribution_date)->format('d M Y') }}
    </td>

    <td class="p-3 text-right space-x-2">

    <a href="{{ route('contributions.receipt', $c->id) }}"
        class="text-indigo-600 hover:text-indigo-800">

        🧾 

    </a>    
    <button
            @click='openEdit = true; selectedContribution = @json($c)'
            class="text-yellow-600 hover:text-yellow-800">
            ✏️
        </button>

        <form action="{{ route('contributions.destroy', $c->id) }}"
              method="POST"
              class="inline">

            @csrf
            @method('DELETE')

            <button class="text-red-600 hover:text-red-800"
                    onclick="return confirm('Delete contribution?')">
                🗑
            </button>

        </form>

    </td>

</tr>

@empty

<tr>
    <td colspan="9" class="p-6 text-center text-gray-400">
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

    {{-- CREATE MODAL --}}
    <div x-show="openCreate"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

        <div @click.away="openCreate = false"
             class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">

            <h2 class="text-xl font-bold mb-4">New Contribution</h2>

            <form method="POST" action="{{ route('contributions.store') }}" class="space-y-4">
                @csrf

                @csrf

<div>
    <label class="text-sm text-gray-600">Member</label>

    <select name="borrower_id"
            class="w-full border rounded-lg p-2 mt-1"
            required>

        <option value="">Select Member</option>

        @foreach($borrowers as $b)
            <option value="{{ $b->id }}">
                {{ $b->full_name }}
            </option>
        @endforeach

    </select>
</div>

<div>
    <label class="text-sm text-gray-600">Amount</label>

    <input type="number"
           name="amount"
           class="w-full border rounded-lg p-2 mt-1"
           required>
</div>

<div>
    <label class="text-sm text-gray-600">Contribution Date</label>

    <input type="date"
           name="contribution_date"
           value="{{ now()->format('Y-m-d') }}"
           class="w-full border rounded-lg p-2 mt-1"
           required>
</div>

<div>
    <label class="text-sm text-gray-600">Contribution Type</label>

    <select name="type"
            class="w-full border rounded-lg p-2 mt-1">

        <option value="monthly">Monthly</option>
        <option value="special">Special</option>
        <option value="welfare">Welfare</option>

    </select>
</div>

<div>
    <label class="text-sm text-gray-600">Notes</label>

    <textarea name="notes"
              rows="3"
              class="w-full border rounded-lg p-2 mt-1"></textarea>
</div>

                <div class="flex justify-end gap-2">
                    <button type="button"
                            @click="openCreate = false"
                            class="px-4 py-2 border rounded-lg">
                        Cancel
                    </button>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div x-show="openEdit"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

        <div @click.away="openEdit = false"
             class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">

            <h2 class="text-xl font-bold mb-4">Edit Contribution</h2>

            <form :action="'/contributions/' + selectedContribution.id"
      method="POST"
      class="space-y-4">

    @csrf
    @method('PUT')

    <div>
        <label class="text-sm text-gray-600">Amount</label>

        <input type="number"
               name="amount"
               x-model="selectedContribution.amount"
               class="w-full border rounded-lg p-2 mt-1">
    </div>

    <div>
        <label class="text-sm text-gray-600">Contribution Date</label>

        <input type="date"
               name="contribution_date"
               x-model="selectedContribution.contribution_date"
               class="w-full border rounded-lg p-2 mt-1">
    </div>

    <div>
        <label class="text-sm text-gray-600">Status</label>

        <select name="status"
                x-model="selectedContribution.status"
                class="w-full border rounded-lg p-2 mt-1">

            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
            <option value="pending">Pending</option>

        </select>
    </div>

    <div>
        <label class="text-sm text-gray-600">Type</label>

        <select name="type"
                x-model="selectedContribution.type"
                class="w-full border rounded-lg p-2 mt-1">

            <option value="monthly">Monthly</option>
            <option value="special">Special</option>
            <option value="welfare">Welfare</option>

        </select>
    </div>

    <div>
        <label class="text-sm text-gray-600">Notes</label>

        <textarea name="notes"
                  rows="3"
                  x-model="selectedContribution.notes"
                  class="w-full border rounded-lg p-2 mt-1"></textarea>
    </div>

    <div class="flex justify-end gap-2">

        <button type="button"
                @click="openEdit = false"
                class="px-4 py-2 border rounded-lg">
            Cancel
        </button>

        <button class="bg-yellow-600 text-white px-4 py-2 rounded-lg">
            Update Contribution
        </button>

    </div>

</form>

        </div>
    </div>

</div>


<script>

const ctx = document.getElementById('collectionChart');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: @json($chartLabels),

        datasets: [{

            label: 'Monthly Contributions',

            data: @json($chartData),

            borderWidth: 3,

            tension: 0.3,

            fill: true

        }]
    },

    options: {

        responsive: true,

        plugins: {

            legend: {
                display: true
            }

        },

        scales: {

            y: {
                beginAtZero: true
            }

        }

    }

});


/*
|--------------------------------------------------------------------------
| MONTHLY COLLECTION ANALYTICS
|--------------------------------------------------------------------------
*/

new Chart(
    document.getElementById('monthlyCollectionsChart'),
    {
        type: 'bar',

        data: {

            labels: {!! json_encode(
                $monthlyAnalytics->pluck('period')
            ) !!},

            datasets: [{

                label: 'KES Collected',

                data: {!! json_encode(
                    $monthlyAnalytics->pluck('total')
                ) !!},

            }]
        }
    }
);


/*
|--------------------------------------------------------------------------
| MONTHLY TREND CHART
|--------------------------------------------------------------------------
*/

new Chart(
    document.getElementById('monthlyTrendChart'),
    {
        type: 'line',

        data: {

            labels: {!! json_encode(
                $monthlyAnalytics->pluck('period')
            ) !!},

            datasets: [{

                label: 'Monthly Trend',

                data: {!! json_encode(
                    $monthlyAnalytics->pluck('total')
                ) !!},

                borderWidth: 3,

                tension: 0.3,

                fill: true

            }]
        },

        options: {

            responsive: true,

            plugins: {

                legend: {
                    display: true
                }

            },

            scales: {

                y: {
                    beginAtZero: true
                }

            }

        }
    }
);

</script>





@endsection
