@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Data Partner</h1>

<a href="/admin/partners/create" 
class="bg-blue-500 text-white px-4 py-2 rounded">
Tambah Partner
</a>

<table class="w-full mt-4 border">
    <tr>
        <th>Nama</th>
        <th>Logo</th>
    </tr>

    @foreach($partners as $partner)
    <tr>
        <td>{{ $partner->name }}</td>
        <td>
            <img src="{{ $partner->logo_url }}" width="100">
        </td>
    </tr>
    @endforeach
</table>
@endsection