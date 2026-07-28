@extends('layouts.admin')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">
            Manajemen Event
        </h2>

        <a href="{{ route('admin.events.create') }}"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">

            Tambah Event
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 p-4 rounded mb-5">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">

        <table class="w-full bg-white rounded-lg shadow border">

            {{-- Header Table --}}
            <thead class="bg-gray-50">
                <tr>

                    <th class="p-4 text-center">Poster</th>

                    <th class="p-4 text-left">
                        Judul Event
                    </th>

                    <th class="p-4 text-center">
                        Kategori
                    </th>

                    <th class="p-4 text-center">
                        Tanggal
                    </th>

                    <th class="p-4 text-center">
                        Stok
                    </th>

                    <th class="p-4 text-center">
                        Aksi
                    </th>

                </tr>
            </thead>

            <tbody>

            @forelse($events as $event)

                <tr class="border-b hover:bg-gray-50">

                    {{-- Poster --}}
                    <td class="p-4 text-center">

                        <img
                            src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                                ? asset('storage/'.$event->poster_path)
                                : 'https://placehold.co/80x100' }}"
                            class="w-16 h-20 rounded-lg object-cover mx-auto">

                    </td>

                    {{-- Judul --}}
                    <td class="p-4 font-medium">

                        {{ $event->title }}

                    </td>

                    {{-- Kategori --}}
                    <td class="p-4 text-center">

                        {{ $event->category->name ?? '-' }}

                    </td>

                    {{-- Tanggal --}}
                    <td class="p-4 text-center">

                        {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}

                    </td>

                    {{-- STOK --}}
                    <td class="p-4 text-center">

                        @if($event->stock > 20)

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $event->stock }} Tiket
                            </span>

                        @elseif($event->stock > 0)

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $event->stock }} Tiket
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Habis
                            </span>

                        @endif

                    </td>

                    {{-- Aksi --}}
                    <td class="p-4">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('admin.events.edit',$event->id) }}"
                                class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200">

                                Edit

                            </a>

                            <form action="{{ route('admin.events.destroy',$event->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('Yakin ingin menghapus event ini?')"
                                    class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">

                                    Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center p-8 text-gray-500">

                        Belum ada data event.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
