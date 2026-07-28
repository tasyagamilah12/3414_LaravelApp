@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    
    {{-- Header & Dashboard Filter (STEP 27 & STEP 28) --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Dashboard Analytics</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Metrik performa dan analisis transaksi platform Anda.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Dark Mode Button --}}
            <button onclick="toggleDarkMode()" type="button" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <select name="period" onchange="this.form.submit()" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 px-4 py-2.5 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 shadow-sm transition">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Pendapatan</p>
            <h2 class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tiket Terjual</p>
            <h2 class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ number_format($ticketsSold) }}</h2>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Event Aktif</p>
            <h2 class="text-3xl font-bold text-amber-500 dark:text-amber-400 mt-2">{{ number_format($activeEvents) }}</h2>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Pending Order</p>
            <h2 class="text-3xl font-bold text-rose-500 dark:text-rose-400 mt-2">{{ number_format($pendingOrders) }}</h2>
        </div>
    </div>

    {{-- Baris Grafik 1: Revenue Line Chart & Status Doughnut --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Tren Pendapatan Bulanan</h2>
            <div class="relative h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Rasio Status Transaksi</h2>
            <div class="relative h-72 flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Card Revenue per Event --}}
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Revenue per Event</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm">10 event dengan pendapatan terbesar.</p>
            </div>
        </div>
        <canvas id="eventRevenueChart" height="120"></canvas>
    </div>

    {{-- STEP 26 — Penjualan Tiket per Bulan --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Volume Tiket Terjual</h2>
        <p class="text-xs text-slate-400 mb-4">Jumlah tiket terjual per bulan</p>
        <div class="relative h-72">
            <canvas id="ticketSalesChart"></canvas>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Transaksi Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-semibold uppercase text-xs">
                    <tr>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Event</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                    @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4 whitespace-nowrap">{{ $trx->created_at->format('d M Y H:i') }}</td>
                            <td class="p-4">
                                <div class="font-semibold text-slate-800 dark:text-white">{{ $trx->customer_name }}</div>
                                <div class="text-xs text-slate-400">{{ $trx->customer_email }}</div>
                            </td>
                            <td class="p-4 font-medium">{{ $trx->event->title ?? '-' }}</td>
                            <td class="p-4">
                                @php $st = strtolower($trx->status); @endphp
                                @if(in_array($st, ['success', 'settlement']))
                                    <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-400 px-2.5 py-0.5 rounded-full text-xs font-semibold">Success</span>
                                @elseif($st === 'pending')
                                    <span class="bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-400 px-2.5 py-0.5 rounded-full text-xs font-semibold">Pending</span>
                                @else
                                    <span class="bg-rose-100 dark:bg-rose-900/40 text-rose-800 dark:text-rose-400 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ ucfirst($st) }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-right font-bold text-slate-900 dark:text-white">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        const revenueValues = Object.values(@json($monthlyRevenue));
        const ticketValues = Object.values(@json($monthlyTicketSales));
        const statusValues = @json($statusData);

        // 1. Line Chart - Revenue
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueValues,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // 2. Doughnut Chart - Status
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Berhasil', 'Pending', 'Gagal'],
                datasets: [{
                    data: [statusValues.success, statusValues.pending, statusValues.failed],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // 3. Bar Chart - Revenue per Event
        const eventRevenueCtx = document.getElementById('eventRevenueChart');
        if(eventRevenueCtx){
            new Chart(eventRevenueCtx, {
                type: 'bar',
                data: {
                    labels: @json($revenuePerEvent->pluck('event_title')),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenuePerEvent->pluck('total_revenue')),
                        borderRadius: 10,
                        backgroundColor: '#4F46E5'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // 4. Bar Chart - Tiket Terjual
        new Chart(document.getElementById('ticketSalesChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Tiket Terjual',
                    data: ticketValues,
                    backgroundColor: '#10B981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });
</script>
@endpush