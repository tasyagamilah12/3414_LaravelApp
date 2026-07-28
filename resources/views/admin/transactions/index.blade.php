@extends('layouts.admin')
@section('title', 'Laporan Transaksi - Admin')
@section('content')

    <div class="space-y-6">

        {{-- Header & Export Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Laporan Transaksi
                </h1>
                <p class="text-slate-500 mt-1">
                    Pantau seluruh transaksi pelanggan, lakukan export laporan, serta analisis data transaksi.
                </p>
            </div>

            {{-- Export Buttons --}}
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('admin.transactions.export.pdf', request()->query()) }}"
                    class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 16V4m0 12l-4-4m4 4l4-4M4 20h16" />
                    </svg>
                    Export PDF
                </a>

                <a href="{{ route('admin.transactions.export.excel', request()->query()) }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 16V4m0 12l-4-4m4 4l4-4M4 20h16" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            {{-- Total Revenue --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl p-6 shadow-md">
                <p class="text-sm font-medium opacity-80">Total Pendapatan</p>
                <h2 class="text-2xl lg:text-3xl font-bold mt-2">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
            </div>

            {{-- Success --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Berhasil (Success)</p>
                <h2 class="text-3xl font-bold text-emerald-600 mt-2">
                    {{ number_format($successCount) }}
                </h2>
            </div>

            {{-- Pending --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Menunggu (Pending)</p>
                <h2 class="text-3xl font-bold text-amber-500 mt-2">
                    {{ number_format($pendingCount) }}
                </h2>
            </div>

            {{-- Failed --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Gagal / Batal</p>
                <h2 class="text-3xl font-bold text-rose-600 mt-2">
                    {{ number_format($failedCount) }}
                </h2>
            </div>
        </div>

        {{-- Filter & Search Form --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

                    {{-- Search Input --}}
                    <div class="relative lg:col-span-4">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Order ID, Customer, Event..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
                    </div>

                    {{-- Status Select --}}
                    <div class="lg:col-span-2">
                        <select name="status"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Settlement</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="challenge" {{ request('status') == 'challenge' ? 'selected' : '' }}>Challenge</option>
                            <option value="expire" {{ request('status') == 'expire' ? 'selected' : '' }}>Expired</option>
                            <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                            <option value="deny" {{ request('status') == 'deny' ? 'selected' : '' }}>Deny</option>
                        </select>
                    </div>

                    {{-- Start Date --}}
                    <div class="lg:col-span-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500">
                    </div>

                    {{-- End Date --}}
                    <div class="lg:col-span-2">
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500">
                    </div>

                    {{-- Submit & Reset --}}
                    <div class="lg:col-span-2 flex items-center gap-2">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.transactions.index') }}"
                            class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl font-semibold text-sm transition">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-xs font-semibold uppercase tracking-wider text-slate-500">

                            {{-- ORDER ID --}}
                            <th class="px-6 py-4">
                                <a href="{{ route('admin.transactions.index', array_merge(request()->query(), ['sort' => 'order_id', 'direction' => request('sort') == 'order_id' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600">
                                    Order ID
                                    @if (request('sort') == 'order_id')
                                        <span>{{ request('direction') == 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>

                            {{-- PEMBELI --}}
                            <th class="px-6 py-4">
                                <a href="{{ route('admin.transactions.index', array_merge(request()->query(), ['sort' => 'customer_name', 'direction' => request('sort') == 'customer_name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600">
                                    Pembeli
                                    @if (request('sort') == 'customer_name')
                                        <span>{{ request('direction') == 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>

                            {{-- EVENT --}}
                            <th class="px-6 py-4">Event</th>

                            {{-- TANGGAL --}}
                            <th class="px-6 py-4">
                                <a href="{{ route('admin.transactions.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600">
                                    Tanggal
                                    @if (request('sort') == 'created_at')
                                        <span>{{ request('direction') == 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>

                            {{-- STATUS --}}
                            <th class="px-6 py-4">
                                <a href="{{ route('admin.transactions.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600">
                                    Status
                                    @if (request('sort') == 'status')
                                        <span>{{ request('direction') == 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>

                            {{-- TOTAL --}}
                            <th class="px-6 py-4 text-right">
                                <a href="{{ route('admin.transactions.index', array_merge(request()->query(), ['sort' => 'total_price', 'direction' => request('sort') == 'total_price' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600">
                                    Total
                                    @if (request('sort') == 'total_price')
                                        <span>{{ request('direction') == 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>

                            {{-- AKSI --}}
                            <th class="px-6 py-4 text-center">Aksi</th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-mono font-medium text-slate-900">
                                    {{ $trx->order_id }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $trx->customer_name }}</div>
                                    <div class="text-xs text-slate-400">{{ $trx->customer_email }}</div>
                                    <div class="text-xs text-slate-400">{{ $trx->customer_phone }}</div>
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $trx->event->title ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                    {{ $trx->created_at ? $trx->created_at->format('d M Y H:i') : '-' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = strtolower($trx->status);
                                    @endphp

                                    @if (in_array($status, ['success', 'settlement']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                            SUCCESS
                                        </span>
                                    @elseif($status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                            PENDING
                                        </span>
                                    @elseif($status == 'challenge')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            CHALLENGE
                                        </span>
                                    @elseif(in_array($status, ['cancel', 'deny', 'expire', 'failed']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                            {{ strtoupper($status) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                                            {{ strtoupper($trx->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right font-bold text-indigo-600 whitespace-nowrap">
                                    Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <button type="button" class="detailBtn bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                                        data-id="{{ $trx->id }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada data transaksi yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $transactions->withQueryString()->links() }}
            </div>
        </div>

    </div>

    {{-- MODAL DETAIL TRANSAKSI --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full overflow-hidden border border-slate-100">
            {{-- Header Modal --}}
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Detail Transaksi</h3>
                <button type="button" id="closeModalBtn" class="text-slate-400 hover:text-slate-600 text-xl font-bold p-1">&times;</button>
            </div>

            {{-- Body Modal --}}
            <div class="p-6 space-y-4" id="modalContent">
                <div id="modalLoading" class="text-center py-8 text-slate-400">
                    Mengambil data...
                </div>

                <div id="invoiceprintable" class="hidden space-y-3 text-sm">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-500">Order ID:</span>
                        <span id="modalOrderId" class="font-mono font-bold text-slate-800"></span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-500">Nama Pelanggan:</span>
                        <span id="modalCustomerName" class="font-medium text-slate-800"></span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-500">Email:</span>
                        <span id="modalCustomerEmail" class="text-slate-800"></span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-500">Telepon:</span>
                        <span id="modalCustomerPhone" class="text-slate-800"></span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-500">Event:</span>
                        <span id="modalEventTitle" class="font-medium text-slate-800"></span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-500">Metode Pembayaran:</span>
                        <span id="modalPaymentType" class="uppercase font-medium text-slate-800"></span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-500">Tanggal Transaksi:</span>
                        <span id="modalCreatedAt" class="text-slate-800"></span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-500">Status:</span>
                        <span id="modalStatus" class="font-bold uppercase"></span>
                    </div>
                    <div class="flex justify-between pt-2 text-base font-bold">
                        <span class="text-slate-700">Total Pembayaran:</span>
                        <span id="modalTotalPrice" class="text-indigo-600"></span>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/50">
                <button type="button" id="printInvoiceBtn" class="hidden bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-xl text-sm transition">
                    Print Invoice
                </button>
                <button type="button" id="closeModalFooterBtn" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-4 py-2 rounded-xl text-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT MODAL & PRINT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('detailModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const closeModalFooterBtn = document.getElementById('closeModalFooterBtn');
            const modalLoading = document.getElementById('modalLoading');
            const invoiceContent = document.getElementById('invoiceprintable');
            const printBtn = document.getElementById('printInvoiceBtn');

            // Buka Modal & Fetch Data
            document.querySelectorAll('.detailBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    
                    // Reset UI State
                    modal.classList.remove('hidden');
                    modalLoading.classList.remove('hidden');
                    invoiceContent.classList.add('hidden');
                    printBtn.classList.add('hidden');

                    // Ajax Request
                    fetch(`/admin/transactions/${id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal mengambil data.');
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('modalOrderId').innerText = data.order_id;
                        document.getElementById('modalCustomerName').innerText = data.customer_name;
                        document.getElementById('modalCustomerEmail').innerText = data.customer_email || '-';
                        document.getElementById('modalCustomerPhone').innerText = data.customer_phone || '-';
                        document.getElementById('modalEventTitle').innerText = data.event;
                        document.getElementById('modalPaymentType').innerText = data.payment_type || '-';
                        document.getElementById('modalCreatedAt').innerText = data.created_at;
                        document.getElementById('modalStatus').innerText = data.status;
                        document.getElementById('modalTotalPrice').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_price);

                        modalLoading.classList.add('hidden');
                        invoiceContent.classList.remove('hidden');
                        printBtn.classList.remove('hidden');
                    })
                    .catch(error => {
                        modalLoading.innerText = 'Terjadi kesalahan saat memuat data.';
                    });
                });
            });

            // Tutup Modal
            function closeModal() {
                modal.classList.add('hidden');
            }

            closeModalBtn.addEventListener('click', closeModal);
            closeModalFooterBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            // Cetak Invoice
            printBtn.addEventListener('click', function () {
                const printContents = invoiceContent.innerHTML;
                const originalContents = document.body.innerHTML;

                document.body.innerHTML = `
                    <div style="padding: 40px; font-family: sans-serif;">
                        <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 20px;">INVOICE TRANSAKSI</h2>
                        ${printContents}
                    </div>
                `;
                window.print();
                document.body.innerHTML = originalContents;
                window.location.reload();
            });
        });
    </script>

@endsection