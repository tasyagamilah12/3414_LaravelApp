<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AmikomEventHub</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-900 text-white flex flex-col p-6">

        <div class="mb-10">
            <h2 class="text-2xl font-bold">
                AmikomEventHub
            </h2>
        </div>

        <nav class="flex flex-col gap-3">

            <a href="{{ route('admin.dashboard') }}"
                class="px-4 py-3 rounded-lg hover:bg-indigo-800">
                Dashboard
            </a>

            <a href="{{ route('admin.events.index') }}"
                class="px-4 py-3 rounded-lg hover:bg-indigo-800">
                Kelola Event
            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="px-4 py-3 rounded-lg hover:bg-indigo-800">
                Kategori
            </a>

            <a href="{{ route('admin.partners.index') }}"
                class="px-4 py-3 rounded-lg hover:bg-indigo-800">
                Partner
            </a>

        </nav>

        <div class="mt-auto pt-8">

            <a href="{{ route('home') }}"
                class="block px-4 py-3 rounded-lg bg-indigo-800 hover:bg-indigo-700">
                Kembali ke Website
            </a>

        </div>

    </aside>

    <!-- Content -->
    <main class="flex-1 p-8">

        <div class="bg-white rounded-xl shadow-sm p-6">

            @yield('content')

        </div>

    </main>

</body>

</html>