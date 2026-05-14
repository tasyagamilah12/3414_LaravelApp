@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-indigo-600 text-white flex items-center justify-center p-6 -mt-10 -mb-10">

    <div class="max-w-md w-full">
        <!-- Success Banner -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black">Pembayaran Berhasil!</h1>
            <p class="text-indigo-100 mt-2">Tiket Anda telah terbit dan siap digunakan.</p>
        </div>

        <!-- Ticket Card -->
        <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative">

            <div class="p-8 bg-indigo-50 border-b-4 border-dashed border-indigo-100 text-center relative">
                <p class="text-indigo-600 font-bold uppercase text-xs mb-2">E-Ticket Resmi</p>
                <h2 class="text-2xl font-black">Jazz Night 2024</h2>

                <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
                <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400">Nama</p>
                        <p class="font-bold">Donni Prabowo</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Tanggal</p>
                        <p class="font-bold">16 Nov</p>
                    </div>
                </div>

                <button onclick="window.print()" class="w-full py-3 bg-indigo-600 text-white rounded-xl">
                    Cetak PDF
                </button>
            </div>
        </div>
    </div>

</div>
@endsection