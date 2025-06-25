@extends('layout.v_template3')

@section('title', 'Dashboard Vendor')

@section('content')

<div class="container my-4">
    {{-- 🧾 Informasi Umum --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="fw-bold">Selamat datang, {{ Auth::user()->nama }}!</h4>
            <p class="text-muted">Pantau layananmu dan kelola profil agar lebih menarik bagi klien.</p>
        </div>
    </div>

    <div class="row">
        {{-- 💼 Statistik Layanan --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold">Layanan Aktif</h5>
                        <p class="mb-0">{{ $totalDisetujui }} layanan disetujui</p>
                        <small class="text-muted">{{ $totalMenunggu }} menunggu persetujuan</small>
                    </div>
                    <i class="fas fa-briefcase fa-2x text-primary"></i>
                </div>
            </div>
        </div>

        {{-- 🔧 Status Profil --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold">Status Profil</h5>
                        <p class="mb-1">{{ $profilLengkap ? 'Profil Lengkap' : 'Belum Lengkap' }}</p>
                        <a href="{{ url('/vendor/profile/v_profile') }}" class="btn btn-outline-secondary btn-sm">Lengkapi Profil</a>
                    </div>
                    <i class="fas fa-user-cog fa-2x text-secondary"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- 📢 Tips & Informasi --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">Tips agar layananmu lebih menarik:</h5>
            <ul class="mb-0">
                <li>✅ Tambahkan foto menarik</li>
                <li>✅ Beri deskripsi lengkap</li>
                <li>✅ Tetapkan harga yang bersaing</li>
            </ul>
        </div>
    </div>


@endsection
