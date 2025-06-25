@extends('layout.v_template3')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold text-dark">Profil Vendor</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        $user = auth()->user();
    @endphp

    <div class="card shadow-sm border-0 rounded-4">
        <div class="row g-0">
            <div class="col-md-4 d-flex align-items-center justify-content-center p-4">
                @if($profile && $profile->foto)
                    <img src="{{ asset('images/vendors/' . $profile->foto) }}" alt="Foto Vendor" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <img src="{{ asset('default.jpg') }}" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" alt="Default Foto">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body px-4 py-3">
                    <h5 class="card-title mb-3">{{ $user->name }}</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Kategori:</strong> {{ $profile->kategori ?? '-' }}</li>
                        <li class="list-group-item"><strong>Deskripsi:</strong> {{ $profile->deskripsi ?? '-' }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                        <li class="list-group-item"><strong>Telepon:</strong> {{ $user->telepon ?? '-' }}</li>
                        <li class="list-group-item"><strong>Alamat:</strong> {{ $user->alamat ?? '-' }}</li>
                    </ul>

                    <div class="mt-4">
                        @if($profile)
                            <a href="{{ route('vendor.profile.edit') }}" class="btn btn-warning px-4 rounded-pill">Edit Profil</a>
                        @else
                            <a href="{{ route('vendor.profile.create') }}" class="btn btn-primary px-4 rounded-pill">Lengkapi Profil</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
