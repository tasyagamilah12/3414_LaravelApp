@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">

        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Menyunting Pengaturan Event
        </h2>

        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')


            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    Judul Event
                </label>

                <input type="text" name="title" value="{{ $event->title }}"
                    class="w-full border border-gray-300 p-3 rounded" required>
            </div>


            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    Kategori Event
                </label>

                <select name="category_id" class="w-full border border-gray-300 p-3 rounded" required>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>
                    @endforeach

                </select>

            </div>


            <div class="mb-6">

                <label class="block mb-2 font-medium text-gray-700">

                    Deskripsi

                </label>

                <textarea name="description" rows="4" class="w-full border border-gray-300 p-3 rounded" required>{{ $event->description }}</textarea>

            </div>



            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

                <div>

                    <label class="block mb-2 font-medium text-gray-700">

                        Tanggal & Waktu

                    </label>

                    <input type="datetime-local" name="date"
                        value="{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d\TH:i') }}"
                        class="w-full border border-gray-300 p-3 rounded" required>

                </div>


                <div>

                    <label class="block mb-2 font-medium text-gray-700">

                        Harga (Rp)

                    </label>

                    <input type="number" name="price" value="{{ $event->price }}"
                        class="w-full border border-gray-300 p-3 rounded" required>

                </div>


                <div>

                    <label class="block mb-2 font-medium text-gray-700">

                        Stok

                    </label>

                    <input type="number" name="stock" value="{{ $event->stock }}"
                        class="w-full border border-gray-300 p-3 rounded" required>

                </div>

            </div>



            <div class="mb-6">

                <label class="block mb-2 font-medium text-gray-700">

                    Lokasi

                </label>

                <input type="text" name="location" value="{{ $event->location }}"
                    class="w-full border border-gray-300 p-3 rounded" required>

            </div>



            <div class="mb-6">

                <label class="block mb-2 font-medium text-gray-700">

                    Poster Event (Opsional)

                </label>


                @if ($event->poster_path)
                    <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-48 rounded mb-4">
                @endif


                <input type="file" name="poster" accept="image/*" class="w-full border border-gray-300 p-3 rounded">

            </div>



            <div class="flex justify-end">

                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded hover:bg-blue-700">

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>
@endsection
