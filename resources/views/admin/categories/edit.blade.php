@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-5">Edit Kategori</h1>

<form action="/admin/categories/{{ $category->id }}" method="POST">

    @csrf
    @method('PUT')

    <input
        type="text"
        name="name"
        value="{{ $category->name }}"
        class="border p-2 rounded w-full mb-3"
    >

    <button class="bg-yellow-500 text-white px-4 py-2 rounded">
        Update
    </button>

</form>

@endsection