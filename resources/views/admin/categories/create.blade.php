@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-5">Tambah Kategori</h1>

<form action="/admin/categories" method="POST">

    @csrf

    <input
        type="text"
        name="name"
        placeholder="Nama kategori"
        class="border p-2 rounded w-full mb-3"
    >

    <button class="bg-blue-500 text-white px-4 py-2 rounded">
        Simpan
    </button>

</form>

@endsection