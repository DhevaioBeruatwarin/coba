@extends('layouts.admin')

@section('content')
<h1>Kelola Pembeli</h1>

<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>
    @foreach($pembeli as $p)
    <tr>
        <td>{{ $p->id_pembeli }}</td>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->email }}</td>
        <td>
            <form action="{{ route('admin.pembeli.delete', $p->id_pembeli) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
