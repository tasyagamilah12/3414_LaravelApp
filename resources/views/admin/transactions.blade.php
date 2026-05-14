@extends('layouts.admin')

@section('content')

<header class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-3xl font-black">Laporan Transaksi</h1>
        <p class="text-slate-500 font-medium">
            Pantau arus kas dan penjualan tiket Anda.
        </p>
    </div>

    <div class="flex gap-4">
        <button class="px-6 py-3 border-2 border-slate-200 rounded-2xl font-bold">
            Ekspor Excel
        </button>

        <button class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold">
            Unduh PDF
        </button>
    </div>
</header>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-8 py-4">Order ID</th>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4">Total</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="px-8 py-4">#TRX001</td>
                    <td class="px-8 py-4">Donni Prabowo</td>
                    <td class="px-8 py-4">Jazz Night</td>
                    <td class="px-8 py-4">Success</td>
                    <td class="px-8 py-4">Rp155.000</td>
                </tr>

                <tr>
                    <td class="px-8 py-4">#TRX002</td>
                    <td class="px-8 py-4">Maya Sari</td>
                    <td class="px-8 py-4">AI Workshop</td>
                    <td class="px-8 py-4">Pending</td>
                    <td class="px-8 py-4">Rp55.000</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection