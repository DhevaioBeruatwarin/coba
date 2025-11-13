@extends('layouts.admin')

@section('content')
<h1>Kelola Karya Seni</h1>

<table border="1" cellpadding="6">
    <tr>
        <th>Kode</th>
        <th>Judul</th>
        <th>Seniman</th>
        <th>Aksi</th>
    </tr>
    @foreach($karya as $k)
    <tr>
        <td>{{ $k->kode_seni }}</td>
        <td>{{ $k->judul }}</td>
        <td>{{ $k->seniman->nama ?? '-' }}</td>
        <td>
            <form action="{{ route('admin.karya.delete', $k->kode_seni) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
