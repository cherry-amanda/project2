@extends('layout.v_template')

@section('content')
<div class="container mt-4">
    <h3>Tambah Pengguna</h3>
    <form action="{{ route('admin.pengguna.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="vendor">Vendor</option>
                <option value="klien">Klien</option>
            </select>
        </div>
        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
