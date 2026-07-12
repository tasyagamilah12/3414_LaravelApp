@extends('layouts.app')

@section('content')

<main class="max-w-4xl mx-auto px-6 py-20">

    <div class="mb-10">

        <a
            href="{{ route('events.show',$event->id) }}"
            class="text-indigo-600 font-bold">

            ← Kembali ke Event

        </a>

        <h1 class="text-4xl font-extrabold mt-4">

            Checkout

        </h1>

        <p class="text-slate-500">

            Lengkapi data pemesan.

        </p>

    </div>

    @if(session('error'))

        <div class="mb-8 bg-red-100 border border-red-300 text-red-700 rounded-xl p-4">

            {{ session('error') }}

        </div>

    @endif

    <div class="grid lg:grid-cols-2 gap-10">

        {{-- Ringkasan --}}

        <div class="bg-white rounded-3xl shadow border p-8">

            <h2 class="text-2xl font-bold mb-6">

                Ringkasan Pesanan

            </h2>

            <img

                src="{{ $event->poster_path && Storage::disk('public')->exists($event->poster_path)
                    ? asset('storage/'.$event->poster_path)
                    : asset('assets/concert.png') }}"

                class="rounded-2xl mb-5">

            <h3 class="font-bold text-xl">

                {{ $event->title }}

            </h3>

            <p class="text-slate-500 mt-2">

                {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}

            </p>

            <p class="text-slate-500">

                {{ $event->location }}

            </p>

            <hr class="my-6">

            <div class="flex justify-between">

                <span>Harga Tiket</span>

                <span>

                    Rp {{ number_format($event->price,0,',','.') }}

                </span>

            </div>

            <div class="flex justify-between mt-3">

                <span>Biaya Admin</span>

                <span>

                    Rp 5.000

                </span>

            </div>

            <hr class="my-6">

            <div class="flex justify-between text-2xl font-black">

                <span>Total</span>

                <span class="text-indigo-600">

                    Rp {{ number_format($event->price+5000,0,',','.') }}

                </span>

            </div>

        </div>

        {{-- Form --}}

        <div class="bg-white rounded-3xl shadow border p-8">

            <h2 class="text-2xl font-bold mb-8">

                Data Pemesan

            </h2>

            <form
                action="{{ route('checkout.store',$event->id) }}"
                method="POST">

                @csrf

                <div class="mb-6">

                    <label class="block font-semibold mb-2">

                        Nama Lengkap

                    </label>

                    <input

                        type="text"

                        name="customer_name"

                        value="{{ old('customer_name') }}"

                        class="w-full border rounded-xl p-3"

                        required>

                </div>

                <div class="mb-6">

                    <label class="block font-semibold mb-2">

                        Email

                    </label>

                    <input

                        type="email"

                        name="customer_email"

                        value="{{ old('customer_email') }}"

                        class="w-full border rounded-xl p-3"

                        required>

                </div>

                <div class="mb-8">

                    <label class="block font-semibold mb-2">

                        Nomor WhatsApp

                    </label>

                    <input

                        type="text"

                        name="customer_phone"

                        value="{{ old('customer_phone') }}"

                        class="w-full border rounded-xl p-3"

                        required>

                </div>

                <button

                    type="submit"

                    class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition">

                    Lanjut Pembayaran

                </button>

            </form>

        </div>

    </div>

</main>

@endsection