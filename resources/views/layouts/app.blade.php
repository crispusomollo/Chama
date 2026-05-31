<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN AREA -->
    <div class="flex-1">

        <!-- TOP BAR -->
        @include('layouts.topbar')

        <!-- PAGE CONTENT -->
        <main class="p-6">

            {{ $slot ?? '' }}
            @yield('content')

        </main>

    </div>

</div>

</body>