@extends('layouts.app')

@section('content')

<main class="max-w-7xl mx-auto px-6 py-16">

    <div class="grid lg:grid-cols-2 gap-12">

        {{-- Poster --}}
        <div>

            <img
                src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                    ? asset('storage/'.$event->poster_path)
                    : asset('assets/concert.png') }}"
                alt="{{ $event->title }}"
                class="w-full rounded-3xl shadow-xl object-cover">

        </div>

        {{-- Informasi --}}
        <div>

            {{-- Kategori --}}
            <span
                class="inline-block px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full font-semibold uppercase text-sm">

                {{ $event->category->name }}

            </span>

            {{-- Judul --}}
            <h1 class="text-5xl font-extrabold mt-5">

                {{ $event->title }}

            </h1>

            {{-- Informasi --}}
            <div class="mt-6 space-y-3 text-slate-600">

                <p>
                    📅
                    {{ \Carbon\Carbon::parse($event->date)->format('d F Y, H:i') }}
                </p>

                <p>
                    📍 {{ $event->location }}
                </p>

            </div>

            {{-- Deskripsi --}}
            <div class="mt-8">

                <h2 class="font-bold text-2xl mb-3">
                    Deskripsi Event
                </h2>

                <p class="leading-8 text-slate-600">

                    {{ $event->description }}

                </p>

            </div>

            {{-- Harga & Stok --}}
            <div
                class="mt-10 bg-indigo-600 rounded-3xl p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6 text-white">

                <div>

                    <p class="uppercase text-sm font-semibold">
                        Harga Tiket
                    </p>

                    <h2 class="text-5xl font-black mt-2">

                        Rp {{ number_format($event->price,0,',','.') }}

                    </h2>

                    {{-- Status Stok --}}
                    <div class="mt-5">

                        @if($event->stock > 20)

                            <span class="inline-block bg-green-500 px-4 py-2 rounded-full text-sm font-semibold">
                                ✅ Stok Tersedia
                            </span>

                        @elseif($event->stock > 0)

                            <span class="inline-block bg-yellow-500 px-4 py-2 rounded-full text-sm font-semibold">
                                ⚠️ Stok Hampir Habis
                            </span>

                        @else

                            <span class="inline-block bg-red-600 px-4 py-2 rounded-full text-sm font-semibold">
                                ❌ Tiket Habis
                            </span>

                        @endif

                    </div>

                    <p class="mt-4 text-lg">

                        Sisa tiket :

                        <strong class="text-2xl">

                            {{ $event->stock }}

                        </strong>

                    </p>

                </div>

                {{-- Tombol Checkout --}}
                <div>

                    @if($event->stock > 0)

                        <a
                            href="{{ route('checkout.create',$event->id) }}"
                            class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-2xl font-bold hover:bg-slate-100 transition">

                            🎟 Pesan Sekarang

                        </a>

                    @else

                        <button
                            disabled
                            class="bg-gray-400 text-white px-8 py-4 rounded-2xl font-bold cursor-not-allowed">

                            Tiket Sudah Habis

                        </button>

                    @endif

                </div>

            </div>

            {{-- Kebijakan --}}
            <div class="mt-10">

                <h3 class="font-bold text-xl mb-4">

                    Kebijakan Tiket

                </h3>

                <ul class="space-y-3 text-slate-600">

                    <li>✅ E-ticket akan dikirim otomatis setelah pembayaran berhasil.</li>

                    <li>✅ Tiket dapat digunakan untuk proses check-in pada hari acara.</li>

                    <li>✅ Pembelian tiket akan mengurangi stok secara otomatis.</li>

                    <li class="text-red-500">
                        ❌ Tiket yang telah dibeli tidak dapat dibatalkan ataupun direfund.
                    </li>

                </ul>

            </div>

        </div>

    </div>

</main>

@endsection