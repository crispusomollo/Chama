@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-4">
    <h2 class="text-2xl font-bold">Loans</h2>
</div>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">Borrower</th>
            <th class="p-2">Amount</th>
            <th class="p-2">Interest</th>
            <th class="p-2">Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($loans as $loan)
        <tr class="border-b">
            <td class="p-2">
                {{ $loan->borrower->full_name }}
            </td>
            <td class="p-2">
                {{ $loan->amount }}
            </td>
            <td class="p-2">
                {{ $loan->interest_rate }}%
            </td>
            <td class="p-2">
                {{ $loan->status }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
