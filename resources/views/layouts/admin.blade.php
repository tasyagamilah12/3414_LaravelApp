<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AmikomEventHub</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    {{-- Script Inisialisasi Dark Mode --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    </script>
</head>

<body class="bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 flex min-h-screen transition-colors duration-200">

    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-900 text-white flex flex-col p-6 shrink-0">

        <div class="mb-10">
            <h2 class="text-2xl font-bold">
                AmikomEventHub
            </h2>
        </div>

        <nav class="flex flex-col gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-3 rounded-lg hover:bg-indigo-800 transition">
                Dashboard
            </a>

            <a href="{{ route('admin.events.index') }}" class="px-4 py-3 rounded-lg hover:bg-indigo-800 transition">
                Kelola Event
            </a>

            <a href="{{ route('admin.categories.index') }}" class="px-4 py-3 rounded-lg hover:bg-indigo-800 transition">
                Kategori
            </a>

            <a href="{{ route('admin.partners.index') }}" class="px-4 py-3 rounded-lg hover:bg-indigo-800 transition">
                Partner
            </a>

            <a href="{{ route('admin.transactions.index') }}" class="px-4 py-3 rounded-lg hover:bg-indigo-800 transition">
                Laporan Transaksi
            </a>

            {{-- MENU CHECK-IN SCANNER --}}
            <a href="{{ route('admin.checkin.index') }}" class="px-4 py-3 rounded-lg hover:bg-indigo-800 transition flex items-center gap-2">
                <span> Check-in Scanner</span>
            </a>
        </nav>

        <div class="mt-auto pt-8">
            <a href="{{ route('home') }}" class="block text-center px-4 py-3 rounded-lg bg-indigo-800 hover:bg-indigo-700 transition">
                Kembali ke Website
            </a>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
            @yield('content')
        </div>
    </main>

    {{-- Script Polling Real-Time --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setInterval(() => {
                fetch("{{ route('admin.transactions.index') }}?status=pending", {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    // Logic Notifikasi
                })
                .catch(err => {});
            }, 10000);
        });
    </script>

    @stack('scripts')

</body>
</html>