@extends('layout.v_template4')

@section('content')
<div class="container">
    <h2>Edit Profil</h2>

    <form action="{{ route('klien.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $pengguna->nama) }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $pengguna->email) }}" required>
        </div>

        <div class="form-group">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pengguna->no_hp) }}">
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ old('alamat', $pengguna->alamat) }}</textarea>
        </div>

        <div class="form-group">
            <label>Password Baru <small>(biarkan kosong jika tidak ingin mengubah)</small></label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <label>Foto Profil</label><br>
            @if ($pengguna->klien && $pengguna->klien->foto)
                <img src="{{ asset('images/foto_klien/' . $pengguna->klien->foto) }}" alt="Foto Profil" width="100">
            @else
                <img src="{{ asset('images/foto_klien/default.png') }}" alt="Foto Default" width="100">
            @endif
            <input type="file" name="foto" class="form-control mt-2">
        </div>

        <button type="submit" class="btn btn-success mt-3">Simpan Perubahan</button>
        <a href="{{ route('klien.profile') }}" class="btn btn-secondary mt-3">Batal</a>
    </form>
</div>
<style>
    .form-label {
        font-weight: 600;
    }
    .form-control,
    .form-select {
        border-radius: 0.5rem;
        border: 1px solid #000 !important;
        padding: 0.5rem 1rem;
        background-color: #fff;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #000;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.25);
        outline: none;
    }
</style>
@endsection
