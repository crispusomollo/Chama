@extends('layouts.erp')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Contribution Analytics
            </h2>

            <p class="text-gray-500">
                Financial insights & collection trends
            </p>
        </div>

    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-gray-500 text-sm">
                Paid Contributions
            </p>

            <h2 class="text-3xl font-bold text-green-600">
                {{ $paidCount }}
            </h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-gray-500 text-sm">
                Partial Contributions
            </p>

            <h2 class="text-3xl font-bold text-yellow-600">
                {{ $partialCount }}
            </h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-gray-500 text-sm">
                Pending Contributions
            </p>

            <h2 class="text-3xl font-bold text-red-600">
                {{ $pendingCount }}
            </h2>
        </div>

    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- MONTHLY COLLECTIONS --}}
        <div class="bg-white p-5 rounded-xl shadow">

            <h3 class="font-semibold mb-4">
                Monthly Collections
            </h3>

            <canvas id="monthlyCollectionsChart"></canvas>

        </div>

        {{-- CONTRIBUTION TYPES --}}
        <div class="bg-white p-5 rounded-xl shadow">

            <h3 class="font-semibold mb-4">
                Contribution Types
            </h3>

            <canvas id="contributionTypesChart"></canvas>

        </div>

    </div>

</div>

{{-- CHART SCRIPTS --}}
<script>

    /*
    |--------------------------------------------------------------------------
    | MONTHLY COLLECTIONS
    |--------------------------------------------------------------------------
    */

    new Chart(
        document.getElementById('monthlyCollectionsChart'),
        {
            type: 'bar',

            data: {

                labels: {!! json_encode(
                    $monthlyData->pluck('period')
                ) !!},

                datasets: [{

                    label: 'KES Collected',

                    data: {!! json_encode(
                        $monthlyData->pluck('total')
                    ) !!},

                }]
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | CONTRIBUTION TYPES
    |--------------------------------------------------------------------------
    */

    new Chart(
        document.getElementById('contributionTypesChart'),
        {
            type: 'pie',

            data: {

                labels: [
                    'Monthly',
                    'Registration'
                ],

                datasets: [{

                    data: [
                        {{ $monthlyType }},
                        {{ $registrationType }}
                    ],

                }]
            }
        }
    );

</script>

@endsection
