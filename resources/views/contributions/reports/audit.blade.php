@extends('layouts.erp')

@section('content')

<div class="space-y-6">

    <h2 class="text-2xl font-bold">
        Audit Export
    </h2>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">

                <tr>
                    <th class="p-3">Member</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Created</th>
                </tr>

            </thead>

            <tbody>

            @foreach($contributions as $c)

                <tr class="border-b">

                    <td class="p-3">
                        {{ $c->borrower->full_name }}
                    </td>

                    <td class="p-3">
                        {{ number_format($c->amount) }}
                    </td>

                    <td class="p-3">
                        {{ $c->type }}
                    </td>

                    <td class="p-3">
                        {{ $c->status }}
                    </td>

                    <td class="p-3">
                        {{ $c->contribution_date }}
                    </td>

                    <td class="p-3">
                        {{ $c->created_at }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
