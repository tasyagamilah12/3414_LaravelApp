@extends('layouts.admin')
@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Form
            Tambah Event</h2>
        <form action="{{ route('admin.events.store') }}" method="POST"
            class="bg-white p-6 rounded-lg shadow-sm border
border-gray-200 mt-2">
            @csrf

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray700">Judul Event</label>
                <input type="text" name="title"
                    class="w-full
border border-gray-300 p-2.5 rounded focus:ring focus:ringindigo-200" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray700">Kategori Event</label>
                <select name="category_id"
                    class="w-full border
border-gray-300 p-2.5 rounded focus:ring focus:ring-indigo200" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray700">Deskripsi Pendek</label>
                <textarea name="description" class="w-full border
border-gray-300 p-2.5 rounded focus:ring focus:ring-indigo200"
                    rows="3" required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb4">
                <div>
                    <label class="block mb-2 font-medium textgray-700">Tanggal & Waktu</label>
                    <input type="datetime-local" name="date" class="w-full border border-gray-300 p-2.5 rounded"
                        required>
                </div>
                <div>
                    <label class="block mb-2 font-medium textgray-700">Harga Tiket (Rp)</label>
                    <input type="number" name="price" class="wfull border border-gray-300 p-2.5 rounded" required>
                </div>
                <div>
                    <label class="block mb-2 font-medium textgray-700">Kapasitas Stok</label>
                    <input type="number" name="stock" class="wfull border border-gray-300 p-2.5 rounded" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray700">Lokasi / Gedung</label>
                <input type="text" name="location" class="w-full
border border-gray-300 p-2.5 rounded" required>
            </div>
            <div class="flex justify-end border-t pt-4">
                <button type="submit"
                    class="bg-indigo-600 textwhite px-8 py-2.5 rounded font-semibold hover:bg-indigo-700
shadow">Simpan
                    Data</button>
            </div>
        </form>
    </div>
@endsection
