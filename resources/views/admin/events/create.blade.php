@extends('layouts.admin')
@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Form
            Tambah Event</h2>
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mt-2">
            @csrf

            @if ($errors->any())
                <div class="mb-5 bg-red-100 border border-red-300 text-red-700 p-4 rounded">
                    <strong>Gagal menyimpan data!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray700">Judul Event</label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="w-full border border-gray-300 p-2.5 rounded" required>
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray-700">
                    Kategori Event
                </label>

                <select name="category_id" class="w-full border border-gray-300 p-2.5 rounded" required>

                    <option value="">
                        -- Pilih Kategori --
                    </option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>
                    @endforeach

                </select>
            </div>
            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray700">Deskripsi Pendek</label>
                <<textarea name="description" rows="3" class="w-full border border-gray-300 p-2.5 rounded" required>{{ old('description') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb4">
                <div>
                    <label class="block mb-2 font-medium textgray-700">Tanggal & Waktu</label>
                    <input type="datetime-local" name="date" value="{{ old('date') }}"
                        class="w-full border border-gray-300 p-2.5 rounded" required>
                </div>
                <div>
                    <label class="block mb-2 font-medium textgray-700">Harga Tiket (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}"
                        class="w-full border border-gray-300 p-2.5 rounded" required>
                </div>
                <div>
                    <label class="block mb-2 font-medium textgray-700">Kapasitas Stok</label>
                    <input type="number" name="stock" value="{{ old('stock') }}"
                        class="w-full border border-gray-300 p-2.5 rounded" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray700">Lokasi / Gedung</label>
                <input type="text" name="location" value="{{ old('location') }}"
                    class="w-full border border-gray-300 p-2.5 rounded" required>
            </div>
            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray-700">Poster Event
                    (Opsional)</label>
                <input type="file" name="poster" accept="image/*" class="w-full
border border-gray-300 p-2.5 rounded">
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
