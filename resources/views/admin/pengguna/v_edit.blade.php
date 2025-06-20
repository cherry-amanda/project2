@extends('layout.v_template')

@section('content')
<div class="container mt-4">
    <h3>Edit Pengguna</h3>
    <form action="{{ route('admin.pengguna.update', $data->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" value="{{ $data->nama }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $data->email }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" value="{{ $data->no_hp }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ $data->alamat }}</textarea>
        </div>
        <div class="mb-3">
            <label>Password Baru (kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ $data->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="vendor" {{ $data->role == 'vendor' ? 'selected' : '' }}>Vendor</option>
                <option value="klien" {{ $data->role == 'klien' ? 'selected' : '' }}>Klien</option>
            </select>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
