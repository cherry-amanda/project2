@extends('layout.v_template4')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4">
                <div class="row g-0">
                    <!-- Foto Profil -->
                    <div class="col-md-4 d-flex align-items-center justify-content-center bg-light rounded-start">
                        <img src="{{ asset('images/foto_klien/' . ($pengguna->klien->foto ?? 'default.png')) }}"
                             alt="Foto Profil"
                             class="img-fluid rounded-circle m-3 shadow"
                             style="width: 150px; height: 150px; object-fit: cover;"
                             onerror="this.onerror=null;this.src='{{ asset('images/foto_klien/default.png') }}';">
                    </div>

                    <!-- Info Profil -->
                    <div class="col-md-8">
                        <div class="card-body p-4">
                            <h3 class="card-title fw-bold mb-3">{{ $pengguna->nama }}</h3>
                            <p class="mb-2"><i class="bi bi-envelope-fill me-2"></i> <strong>Email:</strong> {{ $pengguna->email }}</p>
                            <p class="mb-2"><i class="bi bi-telephone-fill me-2"></i> <strong>No HP:</strong> {{ $pengguna->no_hp }}</p>
                            <p class="mb-4"><i class="bi bi-geo-alt-fill me-2"></i> <strong>Alamat:</strong> {{ $pengguna->alamat }}</p>
                            <a href="{{ route('klien.profile.edit') }}" class="btn btn-outline-primary rounded-pill px-4">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
