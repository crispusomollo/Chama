@extends('layouts.erp')

@section('content')

<div class="space-y-6">

    <h2 class="text-2xl font-bold">
        Treasurer Report
    </h2>

    <div class="grid grid-cols-3 gap-4">

        <div class="bg-white p-5 rounded-xl shadow">
            <p>Total Amount</p>

            <h2 class="text-2xl font-bold text-green-600">
                KES {{ number_format($summary['total_amount']) }}
            </h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p>Paid Members</p>

            <h2 class="text-2xl font-bold text-blue-600">
                {{ $summary['paid_members'] }}
            </h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p>Total Transactions</p>

            <h2 class="text-2xl font-bold">
                {{ $summary['transactions'] }}
            </h2>
        </div>

    </div>

</div>

@endsection
