@extends('layout.v_template3')

@section('content')
<div class="container">
    <h2>Profil Vendor</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        $user = auth()->user();
    @endphp

    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                @if($profile && $profile->foto)
                        <img src="{{ asset('images/vendors/' . $profile->foto) }}" alt="Foto" width="80">
                    @else
                        <img src="{{ asset('default.jpg') }}" class="img-fluid rounded-start" alt="Default Foto">
                    @endif

            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p><strong>Kategori:</strong> {{ $profile ? $profile->kategori : '-' }}</p>
                    <p><strong>Deskripsi:</strong> {{ $profile ? $profile->deskripsi : '-' }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Telepon:</strong> {{ $user->telepon ?? '-' }}</p>
                    <p><strong>Alamat:</strong> {{ $user->alamat ?? '-' }}</p>

                    @if($profile)
                        <a href="{{ route('vendor.profile.edit') }}" class="btn btn-warning">Edit Profil</a>
                    @else
                        <a href="{{ route('vendor.profile.create') }}" class="btn btn-primary">Lengkapi Profil</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
