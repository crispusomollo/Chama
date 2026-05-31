@extends('layouts.erp')

@section('title', 'Financial Policies')

@section('content')

<div class="space-y-6">

    <div class="flex justify-between">

        <div>
            <h1 class="text-2xl font-bold">
                Financial Policies
            </h1>

            <p class="text-gray-500">
                Historical contribution and loan rules.
            </p>
        </div>

        <a href="{{ route('rules.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">

            New Policy

        </a>

    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded">

        <table class="w-full">

            <thead>
                <tr class="border-b">

                    <th class="p-3 text-left">
                        Effective Date
                    </th>

                    <th class="p-3">
                        Monthly
                    </th>

                    <th class="p-3">
                        Shares
                    </th>

                    <th class="p-3">
                        Insurance
                    </th>

                    <th class="p-3">
                        Interest
                    </th>

                    <th class="p-3">
                        Status
                    </th>

                    <th class="p-3">
                        Action
                    </th>

                </tr>
            </thead>

            <tbody>

            @foreach($rules as $rule)

                <tr class="border-b">

                    <td class="p-3">
                        {{ $rule->effective_from->format('d M Y') }}
                    </td>

                    <td class="p-3">
                        {{ number_format($rule->monthly_contribution) }}
                    </td>

                    <td class="p-3">
                        {{ number_format($rule->shares_allocation) }}
                    </td>

                    <td class="p-3">
                        {{ number_format($rule->insurance_allocation) }}
                    </td>

                    <td class="p-3">
                        {{ $rule->interest_rate }}%
                    </td>

                    <td class="p-3">

                        @if($rule->active)

                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                                Active
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded">
                                Inactive
                            </span>

                        @endif

                    </td>

                    <td class="p-3">

                        <a href="{{ route('rules.edit',$rule) }}"
                           class="text-blue-600">

                           Edit

                        </a>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
