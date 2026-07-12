@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">

    <div class="flex-1 space-y-8">

        <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
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

    <div class="flex-1">

        <img src="{{ asset('assets/concert.png') }}"
            class="rounded-3xl shadow-2xl w-full">

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


    <!-- FILTER -->
    <div class="mb-8 flex gap-4 flex-wrap">

        <a href="/"
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
            Semua Kategori
        </a>

        @foreach ($categories as $cat)

            <a href="#"
                class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition">

                {{ $cat->name }}

            </a>

        @endforeach

    </div>



    <!-- LIST EVENT -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        @foreach ($events as $event)

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition">

                <div class="aspect-[3/4] overflow-hidden">

                    <img
                        src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                            ? asset('storage/'.$event->poster_path)
                            : 'https://placehold.co/400x600' }}"

                        alt="{{ $event->title }}"

                        class="w-full h-full object-cover hover:scale-110 transition duration-500">

                </div>


                <div class="p-6">

                    <span class="inline-block mb-3 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-semibold">

                        {{ $event->category->name }}

                    </span>

                    <h3 class="text-xl font-bold mb-2">

                        {{ $event->title }}

                    </h3>

                    <p class="text-gray-500 text-sm mb-2">

                        {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}

                    </p>

                    <p class="text-gray-500 mb-4">

                        {{ $event->location }}

                    </p>

                    <div class="flex justify-between items-center">

                        <span class="text-2xl font-bold text-indigo-600">

                            Rp {{ number_format($event->price,0,',','.') }}

                        </span>

                        <a href="{{ route('events.show',$event->id) }}"
                            class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">

                            Lihat Detail

                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</section>



<!-- PARTNER -->
<section class="max-w-7xl mx-auto px-6 py-20">

    <h2 class="text-3xl font-bold mb-8">

        Partner Kami

    </h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">

        @foreach ($partners as $partner)

            <div class="bg-white rounded-2xl shadow p-4 text-center">

                <img
                    src="{{ $partner->logo_url }}"
                    alt="{{ $partner->name }}"
                    class="w-full h-24 object-cover rounded mb-3">

                <h3 class="font-bold">

                    {{ $partner->name }}

                </h3>

            </div>

        @endforeach

    </div>

</section>

@endsection