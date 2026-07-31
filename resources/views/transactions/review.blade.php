@extends('layouts.app')
@section('title', 'Beri Ulasan')
@section('content')
<main class="max-w-xl mx-auto px-6 py-16">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <h2 class="text-2xl font-black mb-1">Beri Rating & Ulasan</h2>
        <p class="text-slate-500 text-sm mb-6">Acara: <strong>{{ $event->title }}</strong></p>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 rounded-xl bg-rose-50 text-rose-700 text-sm">{{ session('error') }}</div>
        @endif

        @if($alreadyReviewed)
            <div class="p-4 rounded-xl bg-slate-50 text-slate-600 text-sm">
                Kamu sudah memberikan ulasan untuk transaksi ini. Terima kasih!
            </div>
        @elseif(!$isEligible)
            <div class="p-4 rounded-xl bg-amber-50 text-amber-700 text-sm">
                Ulasan baru bisa diberikan mulai H+1 setelah acara selesai
                ({{ \Carbon\Carbon::parse($event->date)->addDay()->translatedFormat('d F Y') }}).
            </div>
        @else
            <form action="{{ route('reviews.store', $transaction->id) }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Rating</label>
                    <div class="flex gap-2" id="starPicker">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}"
                                class="star-btn text-3xl text-slate-300 hover:text-amber-400 transition">&#9733;</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Testimoni (opsional)</label>
                    <textarea name="comment" rows="4" maxlength="1000"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                        placeholder="Ceritakan pengalamanmu di acara ini..."></textarea>
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition">
                    Kirim Ulasan
                </button>
            </form>

            <script>
                const buttons = document.querySelectorAll('.star-btn');
                const ratingInput = document.getElementById('ratingInput');
                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const value = parseInt(btn.dataset.value);
                        ratingInput.value = value;
                        buttons.forEach(b => {
                            b.classList.toggle('text-amber-400', parseInt(b.dataset.value) <= value);
                            b.classList.toggle('text-slate-300', parseInt(b.dataset.value) > value);
                        });
                    });
                });
            </script>
        @endif
    </div>
</main>
@endsection