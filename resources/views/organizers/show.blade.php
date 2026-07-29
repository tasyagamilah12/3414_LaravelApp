@extends('layouts.app')

@section('title', 'Profil Penyelenggara - ' . ($organizer->organization_name ?? $organizer->name))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    {{-- Banner & Profil Header Penyelenggara --}}
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col md:flex-row items-center md:items-start gap-6">
        <img src="{{ $organizer->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($organizer->organization_name ?? $organizer->name) . '&background=4F46E5&color=fff' }}"
            alt="Organizer Avatar"
            class="w-28 h-28 rounded-2xl object-cover shadow-md border-2 border-indigo-500">

        <div class="flex-1 text-center md:text-left space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300">
                Penyelenggara Terverifikasi
            </span>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                {{ $organizer->organization_name ?? $organizer->name }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Anggota sejak {{ $organizer->created_at->format('M Y') }} • {{ $organizer->email }}
            </p>

            {{-- Ringkasan Rating & Ulasan --}}
            <div class="flex items-center justify-center md:justify-start gap-4 pt-2">
                <div class="flex items-center gap-1.5">
                    <svg class="w-6 h-6 text-amber-400 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $averageRating }}</span>
                    <span class="text-sm text-slate-400">/ 5.0</span>
                </div>
                <span class="text-slate-300 dark:text-slate-700">|</span>
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ $totalReviews }} Ulasan Pembeli</span>
            </div>
        </div>
    </div>

    {{-- Grid Layout: Event & Ulasan Publik --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Kolom Kiri (2/3): Event Diselenggarakan --}}
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                Event Diselenggarakan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($events as $event)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition">
                        <img src="{{ $event->poster_path ? asset('storage/' . $event->poster_path) : 'https://via.placeholder.com/400x200?text=Event+Poster' }}"
                            alt="{{ $event->title }}"
                            class="w-full h-40 object-cover">
                        <div class="p-5 space-y-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                {{ $event->category->name ?? 'Event' }}
                            </span>
                            <h3 class="font-bold text-slate-800 dark:text-white truncate">
                                <a href="{{ route('events.show', $event->id) }}" class="hover:text-indigo-600">
                                    {{ $event->title }}
                                </a>
                            </h3>
                            <div class="flex justify-between items-center text-sm pt-2 border-t border-slate-100 dark:border-slate-700">
                                <span class="text-slate-500 dark:text-slate-400">
                                    {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                </span>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-10 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-400">
                        Belum ada event yang dipublikasikan.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $events->links() }}
            </div>
        </div>

        {{-- Kolom Kanan (1/3): Rekam Jejak Ulasan & Rating --}}
        <div class="space-y-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                Ulasan & Testimoni
            </h2>

            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-800 dark:text-white">{{ $review->user->name ?? 'Pengguna' }}</h4>
                                    <p class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            {{-- Rating Bintang --}}
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-slate-300 dark:text-slate-600' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>

                        <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">
                            Acara: {{ $review->event->title ?? '-' }}
                        </p>

                        <p class="text-sm text-slate-600 dark:text-slate-300 italic">
                            "{{ $review->comment ?? 'Tidak ada ulasan tertulis.' }}"
                        </p>
                    </div>
                @empty
                    <div class="text-center py-8 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-400 text-sm">
                        Belum ada ulasan untuk penyelenggara ini.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>

    </div>

</div>
@endsection