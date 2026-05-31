@extends('layouts.erp')

@section('title', 'Contribution Receipt')

@section('content')

<div class="max-w-3xl mx-auto bg-white shadow rounded-xl p-8">

    <div class="flex justify-between items-start border-b pb-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Contribution Receipt
            </h1>

            <p class="text-sm text-gray-500">
                Official Contribution Acknowledgement
            </p>
        </div>

        <div class="text-right">
            <p class="text-sm text-gray-500">
                Receipt No
            </p>

            <h2 class="font-bold text-indigo-600">
                {{ $contribution->receipt_no }}
            </h2>
        </div>

    </div>

    <div class="grid grid-cols-2 gap-6 mt-6">

        <div>

            <p class="text-sm text-gray-500">
                Member
            </p>

            <h2 class="font-semibold text-gray-800">
                {{ $contribution->borrower->full_name }}
            </h2>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Contribution Date
            </p>

            <h2 class="font-semibold text-gray-800">
                {{ $contribution->contribution_date }}
            </h2>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Total Amount
            </p>

            <h2 class="font-bold text-green-600 text-xl">
                KES {{ number_format($contribution->amount) }}
            </h2>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Contribution Type
            </p>

            <h2 class="font-semibold text-gray-800 uppercase">
                {{ $contribution->type }}
            </h2>

        </div>

    </div>

    {{-- BREAKDOWN --}}
    <div class="mt-8 border rounded-xl overflow-hidden">

        <div class="bg-gray-100 px-4 py-3 font-semibold">
            Contribution Breakdown
        </div>

        <table class="w-full text-sm">

            <tr class="border-b">
                <td class="p-3">Shares Contribution</td>
                <td class="p-3 text-right font-semibold">
                    KES 1,000
                </td>
            </tr>

            <tr>
                <td class="p-3">Insurance Contribution</td>
                <td class="p-3 text-right font-semibold">
                    KES 300
                </td>
            </tr>

        </table>

    </div>

    <div class="mt-8 flex justify-end gap-3">

        <button
            onclick="window.print()"
            class="bg-indigo-600 text-white px-5 py-2 rounded-lg">

            Print Receipt

        </button>

        <a href="{{ route('contributions.index') }}"
           class="bg-gray-800 text-white px-5 py-2 rounded-lg">

            Back

        </a>

    </div>

</div>

@endsection
