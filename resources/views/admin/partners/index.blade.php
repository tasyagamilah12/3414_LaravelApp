@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-5">Data Partner</h1>

<form method="GET" class="mb-4 flex gap-2">

    <input
        type="text"
        name="search"
        placeholder="Cari partner..."
        class="border p-2 rounded w-80"
    >

    <button class="bg-blue-500 text-white px-4 rounded">
        Cari
    </button>

</form>

<a href="/admin/partners/create"
class="bg-green-500 text-white px-4 py-2 rounded">
Tambah Partner
</a>

<table class="w-full mt-5 border">

    <tr class="bg-gray-200">
        <th class="border p-2">ID</th>
        <th class="border p-2">Nama</th>
        <th class="border p-2">Logo</th>
        <th class="border p-2">Action</th>
    </tr>

    @foreach($partners as $partner)

    <tr>
        <td class="border p-2">{{ $partner->id }}</td>

        <td class="border p-2">{{ $partner->name }}</td>

        <td class="border p-2">
            <img src="{{ $partner->logo_url }}" width="100">
        </td>

        <td class="border p-2 flex gap-2">

            <a href="/admin/partners/{{ $partner->id }}/edit"
            class="bg-yellow-500 text-white px-3 py-1 rounded">
                Edit
            </a>

            <form action="/admin/partners/{{ $partner->id }}"
            method="POST">

                @csrf
                @method('DELETE')

                <button class="bg-red-500 text-white px-3 py-1 rounded">
                    Hapus
                </button>

            </form>

        </td>

    </tr>

    @endforeach

</table>

@endsection