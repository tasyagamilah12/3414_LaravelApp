@extends('layouts.admin')

@section('title','Laporan Transaksi - Admin')

@section('page_title','Laporan Transaksi')

@section('page_subtitle','Pantau seluruh transaksi pelanggan.')

@section('content')

<div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-slate-50 border-b">

                <tr class="text-left text-xs uppercase tracking-widest text-slate-500">

                    <th class="px-8 py-5">Order ID</th>

                    <th class="px-8 py-5">Pembeli</th>

                    <th class="px-8 py-5">Event</th>

                    <th class="px-8 py-5">Tanggal</th>

                    <th class="px-8 py-5">Status</th>

                    <th class="px-8 py-5 text-right">Total</th>

                </tr>

            </thead>

            <tbody class="divide-y">

                @forelse($transactions as $trx)

                <tr class="hover:bg-slate-50">

                    <td class="px-8 py-6">

                        <div class="font-mono text-sm font-bold">

                            {{ $trx->order_id }}

                        </div>

                    </td>

                    <td class="px-8 py-6">

                        <div class="font-semibold">

                            {{ $trx->customer_name }}

                        </div>

                        <div class="text-sm text-slate-500">

                            {{ $trx->customer_email }}

                        </div>

                        <div class="text-sm text-slate-500">

                            {{ $trx->customer_phone }}

                        </div>

                    </td>

                    <td class="px-8 py-6">

                        {{ $trx->event->title ?? '-' }}

                    </td>

                    <td class="px-8 py-6 text-sm">

                        {{ $trx->created_at->format('d M Y H:i') }}

                    </td>

                    <td class="px-8 py-6">

                        @switch(strtolower($trx->status))

                            @case('success')

                            @case('settlement')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                    SUCCESS
                                </span>
                                @break

                            @case('pending')

                                <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold">
                                    PENDING
                                </span>
                                @break

                            @case('challenge')

                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">
                                    CHALLENGE
                                </span>
                                @break

                            @case('expire')

                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold">
                                    EXPIRED
                                </span>
                                @break

                            @case('cancel')

                            @case('deny')

                            @case('failed')

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                    FAILED
                                </span>
                                @break

                            @default

                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">

                                    {{ strtoupper($trx->status) }}

                                </span>

                        @endswitch

                    </td>

                    <td class="px-8 py-6 text-right font-bold">

                        Rp {{ number_format($trx->total_price,0,',','.') }}

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center py-10 text-slate-500">

                        Belum ada transaksi.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="px-8 py-6 border-t bg-slate-50">

        {{ $transactions->links() }}

    </div>

</div>

@endsection