@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-4">Manajemen Kategori</h1>

<table class="w-full border">
    <tr class="bg-gray-200">
        <th class="p-2">Nama</th>
        <th class="p-2">Aksi</th>
    </tr>
    <tr>
        <td class="p-2">Seminar</td>
        <td class="p-2">
            <button class="bg-yellow-400 px-2 py-1">Edit</button>
            <button class="bg-red-500 text-white px-2 py-1">Hapus</button>
        </td>
    </tr>
</table>

<button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
    Tambah Kategori
</button>

@endsection