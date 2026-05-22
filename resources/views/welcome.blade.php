@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">

    <div class="flex-1 space-y-8">

        <span
            class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
            #1 Event Platform
        </span>

        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
            Temukan & Pesan
            <span class="text-indigo-600">
                Tiket Event
            </span>
            Impianmu.
        </h1>

        <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
            Dari konser musik hingga workshop teknologi,
            semua ada di genggamanmu.
            Pesan aman & cepat dengan Midtrans.
        </p>

        <div class="flex gap-4">

            <a href="#events"
                class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">

                Mulai Jelajah

            </a>

            <a href="#"
                class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">

                Cara Pesan

            </a>

        </div>

    </div>

    <div class="flex-1 relative">

        <img
            src="{{ asset('assets/concert.png') }}"
            class="rounded-3xl shadow-2xl"
        >

    </div>

</section>

<!-- EVENT SECTION -->
<section id="events" class="max-w-7xl mx-auto px-6 py-20">

    <div class="flex justify-between items-end mb-12">

        <div>

            <h2 class="text-3xl font-extrabold mb-2">
                Event Terdekat
            </h2>

            <p class="text-slate-500 font-medium">
                Jangan sampai ketinggalan acara seru minggu ini!
            </p>

        </div>

    </div>

    <!-- FILTER KATEGORI -->
    <div class="mb-8 flex gap-4 flex-wrap">

        <a href="/"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-black transition">

            Semua Kategori

        </a>

        @foreach ($categories as $cat)

            <a href="/?category={{ $cat->slug }}"
                class="px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded shadow-sm transition">

                {{ $cat->name }}

            </a>

        @endforeach

    </div>

    <!-- GRID EVENT -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        @foreach ($events as $event)

            <div
                class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">

                <div class="relative overflow-hidden aspect-[3/4]">

                    @php

                        $imageMap = [
                            'entertainment' => 'concert.png',
                            'seminar-it' => 'hackathon.png',
                            'workshop' => 'workshop.png',
                        ];

                        $slug = $event->category->slug ?? '';

                        $image = $imageMap[$slug] ?? 'concert.png';

                    @endphp

                    <img
                        src="{{ asset('assets/' . $image) }}"
                        alt="{{ $event->title }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    >

                    <div
                        class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">

                        {{ $event->category->name }}

                    </div>

                </div>

                <div class="p-6">

                    <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">

                        {{ $event->title }}

                    </h3>

                    <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">

                        <span>
                            {{ \Carbon\Carbon::parse($event->date)->format('d-m-Y H:i') }}
                        </span>

                    </div>

                    <div class="flex justify-between items-center pt-4 border-t">

                        <span class="text-2xl font-black text-indigo-600">

                            Rp {{ number_format($event->price, 0, ',', '.') }}

                        </span>

                        <a href="{{ url('event/1') }}"
                            class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">

                            Lihat Detail

                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</section>

<!-- PARTNER SECTION -->
<section class="max-w-7xl mx-auto px-6 py-20">

    <h2 class="text-3xl font-bold mb-8">

        Partner Kami

    </h2>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">

        @foreach($partners as $partner)

            <div class="bg-white p-4 rounded-2xl shadow text-center">

                <img
                    src="{{ $partner->logo_url }}"
                    class="w-full h-24 object-cover rounded mb-3"
                >

                <h3 class="font-bold">

                    {{ $partner->name }}

                </h3>

            </div>

        @endforeach

    </div>

</section>

@endsection