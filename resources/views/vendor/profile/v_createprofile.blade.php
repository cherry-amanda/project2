@extends('layout.v_template3')

@section('content')
<div class="container">
    <h2>Lengkapi Profil Vendor</h2>

    <form action="{{ route('vendor.profile.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Dekorasi" {{ old('kategori') == 'Dekorasi' ? 'selected' : '' }}>Dekorasi</option>
                <option value="Katering" {{ old('kategori') == 'Katering' ? 'selected' : '' }}>Katering</option>
                <option value="Fotografi" {{ old('kategori') == 'Fotografi' ? 'selected' : '' }}>Fotografi</option>
                <!-- Tambah kategori lain sesuai kebutuhan -->
            </select>
            @error('kategori')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Foto</label>
            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Profil</button>
    </form>
</div>



{{-- Styling tambahan --}}
<style>
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
        border-radius: 10px;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        border: 1px solid #000 !important;
        background-color: #fff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #000;
        box-shadow: 0 0 0 0.15rem rgba(0, 0, 0, 0.25);
    }

    .img-thumbnail {
        border-radius: 8px;
        object-fit: cover;
    }
</style>
@endsection
