@extends('layouts.app')

@section('content')

<main class="max-w-7xl mx-auto px-6 py-16">

    <div class="grid lg:grid-cols-2 gap-12">

        {{-- Poster --}}
        <div>

            <img
                src="{{ $event->poster_path && Storage::disk('public')->exists($event->poster_path)
                    ? asset('storage/'.$event->poster_path)
                    : asset('assets/concert.png') }}"
                alt="{{ $event->title }}"
                class="w-full rounded-3xl shadow-xl object-cover">

        </div>

        {{-- Informasi --}}
        <div>

            <span
                class="inline-block px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full font-semibold uppercase text-sm">

                {{ $event->category->name }}

            </span>

            <h1 class="text-5xl font-extrabold mt-5">

                {{ $event->title }}

            </h1>

            <div class="mt-6 space-y-3 text-slate-600">

                <p>

                    📅
                    {{ \Carbon\Carbon::parse($event->date)->format('d F Y, H:i') }}

                </p>

                <p>

                    📍 {{ $event->location }}

                </p>

            </div>

            <div class="mt-8">

                <h2 class="font-bold text-2xl mb-3">

                    Deskripsi Event

                </h2>

                <p class="leading-8 text-slate-600">

                    {{ $event->description }}

                </p>

            </div>

            <div
                class="mt-10 bg-indigo-600 rounded-3xl p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6 text-white">

                <div>

                    <p class="uppercase text-sm font-semibold">

                        Harga Tiket

                    </p>

                    <h2 class="text-5xl font-black mt-2">

                        Rp {{ number_format($event->price,0,',','.') }}

                    </h2>

                    <p class="mt-2">

                        Sisa stok :

                        <strong>

                            {{ $event->stock }}

                        </strong>

                        tiket

                    </p>

                </div>

                <div>

                    <a
                        href="{{ route('checkout.create',$event->id) }}"
                        class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-2xl font-bold hover:bg-slate-100 transition">

                        Pesan Sekarang

                    </a>

                </div>

            </div>

            <div class="mt-10">

                <h3 class="font-bold text-xl mb-4">

                    Kebijakan Tiket

                </h3>

                <ul class="space-y-3 text-slate-600">

                    <li>✅ E-ticket dikirim otomatis setelah pembayaran.</li>

                    <li>✅ Tiket dapat discan saat check-in.</li>

                    <li class="text-red-500">

                        ❌ Tiket yang telah dibeli tidak dapat direfund.

                    </li>

                </ul>

            </div>

        </div>

    </div>

</main>

@endsection