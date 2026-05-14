@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tambah Partner</h1>

<form action="/admin/partners/store" method="POST">
    @csrf

    <input 
        type="text"
        name="name"
        placeholder="Nama Partner"
        class="border p-2 w-full mb-3"
    >

    <input 
        type="text"
        name="logo_url"
        placeholder="URL Logo"
        class="border p-2 w-full mb-3"
    >

    <button class="bg-green-500 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>
@endsection