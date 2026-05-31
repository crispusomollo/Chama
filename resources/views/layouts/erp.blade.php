<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chama Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body class="bg-gray-100 font-sans">

<style>
[x-cloak] { display: none !important; }
</style>

<div class="flex h-screen">

    {{-- SIDEBAR --}}
<aside class="w-64 bg-gray-900 text-white flex flex-col">

    <div class="p-5 text-xl font-bold border-b border-gray-700">
        Chama Platform
    </div>

    <nav class="flex-1 p-3 space-y-4 text-sm">

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-4 px-4 py-3 rounded hover:bg-gray-800 {{ request()->is('dashboard') ? 'bg-gray-800' : '' }}">
            📊 Dashboard
        </a>

        <a href="{{ route('borrowers.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded hover:bg-gray-800 {{ request()->is('borrowers*') ? 'bg-gray-800' : '' }}">
            👥 Members
        </a>

        <a href="{{ route('contributions.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded hover:bg-gray-800 {{ request()->is('contributions*') ? 'bg-gray-800' : '' }}">
            🧾 Contributions
        </a>

        @role('admin|treasurer')

        <a href="{{ route('contributions.arrears') }}"
           class="flex items-center gap-4 px-4 py-3 rounded hover:bg-gray-800 {{ request()->is('contributions.arrears') ? 'bg-gray-800' : '' }}">
            ⚡Arrears Management
        </a>

        @endrole

        <a href="{{ route('ledger.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded hover:bg-gray-800 {{ request()->is('ledger.index') ? 'bg-gray-800' : '' }}">
            🧭 Ledger Hub
</a>
        
        <a href="{{ route('loans.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded hover:bg-gray-800 {{ request()->is('loans*') ? 'bg-gray-800' : '' }}">
            💰 Loans
        </a>

        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded hover:bg-gray-800 {{ request()->is('reports*') ? 'bg-gray-800' : '' }}">
            📈 Reports
        </a>

        <a href="{{ route('settings.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded hover:bg-gray-800 {{ request()->is('settings*') ? 'bg-gray-800' : '' }}">
            ⚙️ Settings
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                    🚪 Logout
            </button>
        </form>

    </nav>

    <div class="p-3 border-t border-gray-700 text-xs text-gray-400">
        Chama System v1
    </div>

</aside>

    {{-- MAIN AREA --}}
    <main class="flex-1 overflow-y-auto">

        {{-- TOP BAR --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-lg font-semibold text-gray-700">
                @yield('title', 'ERP')
            </h1>

            <div class="text-sm text-gray-500">
                {{ auth()->user()->name ?? '' }}
            </div>
        </div>


        @if(session('success'))

        <div
           x-data="{ show: true }"
           x-show="show"
           x-transition
           class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex justify-between items-center"
        >

           <span>
                 {{ session('success') }}
           </span>

           <button
              @click="show = false"
              class="text-green-700 font-bold"
           >
            ×
           </button>

        </div>

        @endif

        @if(session('error'))

       <div
           x-data="{ show: true }"
           x-show="show"
           x-transition
           class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex justify-between items-center"
       >

          <span>
              {{ session('error') }}
          </span>

          <button
            @click="show = false"
            class="text-red-700 font-bold"
          >
            ×
          </button>

        </div>

        @endif

        @if($errors->any())

        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">

           <ul class="list-disc list-inside text-sm space-y-1">

             @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

             @endforeach

            </ul>

        </div>

        @endif


        {{-- PAGE CONTENT --}}
        <div class="p-6">
            @yield('content')
        </div>

    </main>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
