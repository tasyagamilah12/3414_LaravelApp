@extends('layouts.admin')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">
            Manajemen Event
        </h2>

        <a
            href="{{ route('admin.events.create') }}"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">

            Tambah Event
        </a>
    </div>


    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-5">
            {{ session('success') }}
        </div>
    @endif


    <div class="overflow-x-auto">

        <table class="w-full bg-white rounded shadow border">

            <thead>
                <tr class="bg-gray-50">

                    <th class="p-4">Poster</th>

                    <th class="p-4">
                        Judul Event
                    </th>

                    <th class="p-4">
                        Kategori
                    </th>

                    <th class="p-4">
                        Tanggal
                    </th>

                    <th class="p-4">
                        Aksi
                    </th>

                </tr>
            </thead>


            <tbody>

                @foreach($events as $event)

                <tr class="border-b">

                    {{-- POSTER --}}
                    <td class="p-4">

                        <img
                            src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                                ? asset('storage/'.$event->poster_path)
                                : 'https://placehold.co/80x100' }}"

                            class="w-16 h-20 rounded-xl object-cover">

                    </td>


                    {{-- JUDUL --}}
                    <td class="p-4">

                        {{ $event->title }}

                    </td>


                    {{-- KATEGORI --}}
                    <td class="p-4">

                        {{ $event->category->name ?? '-' }}

                    </td>


                    {{-- TANGGAL --}}
                    <td class="p-4">

                        {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}

                    </td>


                    {{-- AKSI --}}
                    <td class="p-4 flex gap-2">

                        <a
                            href="{{ route('admin.events.edit',$event->id) }}"
                            class="bg-blue-100 text-blue-600 px-3 py-1 rounded">

                            Edit

                        </a>


                        <form
                            action="{{ route('admin.events.destroy',$event->id) }}"
                            method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Yakin hapus?')"
                                class="bg-red-100 text-red-600 px-3 py-1 rounded">

                                Hapus

                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection