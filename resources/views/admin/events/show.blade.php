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
                class="inline-block px-4 py-2 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 rounded-full font-semibold uppercase text-sm">
                {{ $event->category->name ?? 'Uncategorized' }}
            </span>

            {{-- Judul --}}
            <h1 class="text-5xl font-extrabold mt-5 text-slate-900 dark:text-white">
                {{ $event->title }}
            </h1>

            {{-- Informasi Tanggal & Lokasi --}}
            <div class="mt-6 space-y-3 text-slate-600 dark:text-slate-300">
                <p>
                    📅 {{ \Carbon\Carbon::parse($event->date)->format('d F Y, H:i') }}
                </p>
                <p>
                    📍 {{ $event->location }}
                </p>
            </div>

            {{-- Deskripsi --}}
            <div class="mt-8">
                <h2 class="font-bold text-2xl mb-3 text-slate-800 dark:text-white">
                    Deskripsi Event
                </h2>
                <p class="leading-8 text-slate-600 dark:text-slate-300">
                    {{ $event->description }}
                </p>
            </div>

            {{-- Harga, Stok, & Tombol Action (Bypass Event Gratis) --}}
            <div class="mt-10 p-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
                
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">Harga Tiket</span>
                    <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                        {{ $event->price == 0 ? 'GRATIS / FREE' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-700 pt-3">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Sisa Stok Tiket</span>
                    <span class="font-bold {{ $event->stock > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $event->stock > 0 ? $event->stock . ' Tiket' : 'Habis' }}
                    </span>
                </div>

                {{-- Status Indicator Badge --}}
                <div class="pt-2">
                    @if($event->stock > 20)
                        <span class="inline-block bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 px-3 py-1 rounded-full text-xs font-semibold">
                            ✅ Stok Tersedia
                        </span>
                    @elseif($event->stock > 0)
                        <span class="inline-block bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 px-3 py-1 rounded-full text-xs font-semibold">
                            ⚠️ Stok Hampir Habis
                        </span>
                    @else
                        <span class="inline-block bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 px-3 py-1 rounded-full text-xs font-semibold">
                            ❌ Tiket Habis
                        </span>
                    @endif
                </div>

                {{-- Tombol Action --}}
                <div class="pt-2">
                    @if($event->stock > 0)
                        <a href="{{ route('checkout.create', $event->id) }}"
                            class="w-full inline-flex justify-center items-center py-3.5 px-6 rounded-xl text-white font-bold transition shadow-md {{ $event->price == 0 ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-indigo-600 hover:bg-indigo-700' }}">
                            {{ $event->price == 0 ? 'Daftar Gratis Sekarang' : '🎟 Beli Tiket Sekarang' }}
                        </a>
                    @else
                        <button disabled class="w-full py-3.5 px-6 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-400 font-bold cursor-not-allowed">
                            Tiket Sudah Habis
                        </button>
                    @endif
                </div>

            </div>

            {{-- Kebijakan Tiket --}}
            <div class="mt-10">
                <h3 class="font-bold text-xl mb-4 text-slate-800 dark:text-white">
                    Kebijakan Tiket
                </h3>

                <ul class="space-y-3 text-slate-600 dark:text-slate-300 text-sm">
                    <li>✅ E-ticket akan dikirim/diterbitkan otomatis setelah pendaftaran/pembayaran selesai.</li>
                    <li>✅ Tiket dapat digunakan untuk proses check-in pada hari acara[cite: 1].</li>
                    <li>✅ Pendaftaran tiket akan mengurangi stok secara otomatis[cite: 1].</li>
                    <li class="text-rose-500 font-medium">
                        ❌ Tiket yang telah dibeli/mendaftar tidak dapat dibatalkan.
                    </li>
                </ul>
            </div>

        </div>

    </div>

</main>

@endsection