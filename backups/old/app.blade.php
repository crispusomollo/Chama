<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-white p-5">
        <h1 class="text-xl font-bold mb-6">Loan Admin</h1>

        <nav class="space-y-3">
            <a href="/borrowers" class="block hover:bg-blue-700 p-2 rounded">Borrowers</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</div>

</body>
</html>
