@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-8">
    Dashboard Admin
</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <div class="bg-indigo-100 p-6 rounded-xl">
        <h3 class="text-sm text-gray-500">
            Total Pendapatan
        </h3>

        <p class="text-3xl font-bold text-indigo-700">
            Rp 12.450.000
        </p>
    </div>

    <div class="bg-green-100 p-6 rounded-xl">
        <h3 class="text-sm text-gray-500">
            Tiket Terjual
        </h3>

        <p class="text-3xl font-bold text-green-700">
            1.284
        </p>
    </div>

    <div class="bg-yellow-100 p-6 rounded-xl">
        <h3 class="text-sm text-gray-500">
            Event Aktif
        </h3>

        <p class="text-3xl font-bold text-yellow-700">
            8 Event
        </p>
    </div>

    <div class="bg-red-100 p-6 rounded-xl">
        <h3 class="text-sm text-gray-500">
            Pesanan Pending
        </h3>

        <p class="text-3xl font-bold text-red-700">
            12 Pesanan
        </p>
    </div>

</div>

<div class="mt-10">

    <h2 class="text-2xl font-bold mb-4">
        Menu Cepat
    </h2>

    <div class="flex flex-wrap gap-4">

        <a href="{{ route('admin.events.index') }}"
            class="bg-blue-600 text-white px-5 py-3 rounded-lg">
            Kelola Event
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="bg-green-600 text-white px-5 py-3 rounded-lg">
            Kelola Kategori
        </a>

        <a href="{{ route('admin.partners.index') }}"
            class="bg-purple-600 text-white px-5 py-3 rounded-lg">
            Kelola Partner
        </a>

    </div>

</div>

<div class="mt-10">

    <form action="{{ route('admin.logout') }}" method="POST">

        @csrf

        <button
            type="submit"
            class="bg-red-600 text-white px-5 py-3 rounded-lg">
            Logout
        </button>

    </form>

</div>

@endsection