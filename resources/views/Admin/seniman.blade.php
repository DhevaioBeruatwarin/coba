@extends('layouts.admin')

@section('content')
<h1>Kelola Seniman</h1>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>
    @foreach($seniman as $s)
    <tr>
        <td>{{ $s->id_seniman }}</td>
        <td>{{ $s->nama }}</td>
        <td>{{ $s->email }}</td>
        <td>
            <form action="{{ route('admin.seniman.delete', $s->id_seniman) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
