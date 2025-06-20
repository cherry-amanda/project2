@extends('layout.v_template')

@section('content')
<div class="container mt-4">
    <h3>Data Pengguna</h3>
    <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary mb-3">+ Tambah Pengguna</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->no_hp }}</td>
                <td>{{ $row->alamat }}</td>
                <td>{{ ucfirst($row->role) }}</td>
                <td>
                    <a href="{{ route('admin.pengguna.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('admin.pengguna.delete', $row->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
