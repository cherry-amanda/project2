@extends('layout.v_template4')

@section('content')
<div class="container">
    <h2>Profil Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="width: 18rem;">
        <img src="{{ asset('images/foto_klien/' . ($pengguna->klien->foto ?? 'default.png')) }}"
            class="card-img-top"
            alt="Foto Profil"
            onerror="this.onerror=null;this.src='{{ asset('images/foto_klien/default.png') }}';">

        <div class="card-body">
            <h5 class="card-title">{{ $pengguna->nama }}</h5>
            <p class="card-text">Email: {{ $pengguna->email }}</p>
            <p class="card-text">No HP: {{ $pengguna->no_hp }}</p>
            <p class="card-text">Alamat: {{ $pengguna->alamat }}</p>
            <a href="{{ route('klien.profile.edit') }}" class="btn btn-primary">Edit Profil</a>
        </div>
    </div>
</div>
@endsection
