@extends('layout.v_template')

@section('content')
<div class="container py-4">
    <h3 class="text-center fw-bold mb-4" style="color: #3A3A3A;">Profil Vendor</h3>

    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    @if($vendor->foto && file_exists(public_path('images/vendors/' . $vendor->foto)))
                        <img src="{{ asset('images/vendors/' . $vendor->foto) }}" class="img-fluid rounded-4 shadow-sm" style="max-width: 240px;">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="img-fluid rounded-4 shadow-sm" style="max-width: 240px;">
                    @endif
                </div>

                <div class="col-md-8">
                    <h4 class="fw-semibold">{{ $vendor->pengguna->nama ?? '-' }}</h4>
                    <span class="badge {{ $vendor->status == 'aktif' ? 'bg-success' : 'bg-secondary' }} mb-3">{{ ucfirst($vendor->status) }}</span>

                    <ul class="list-unstyled small text-muted">
                        <li><strong>Kategori:</strong> {{ $vendor->kategori }}</li>
                        <li><strong>Email:</strong> {{ $vendor->pengguna->email ?? '-' }}</li>
                        <li><strong>Telepon:</strong> {{ $vendor->pengguna->no_hp ?? '-' }}</li>
                        <li><strong>Alamat:</strong> {{ $vendor->pengguna->alamat ?? '-' }}</li>
                    </ul>

                    <p class="mt-3"><strong>Deskripsi:</strong><br>{{ $vendor->deskripsi ?? '-' }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.vendor.index') }}" class="btn btn-outline-dark rounded-pill">← Kembali</a>
            </div>
        </div>
    </div>
</div>

@endsection
