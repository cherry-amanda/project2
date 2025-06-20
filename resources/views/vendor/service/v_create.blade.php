@extends('layout.v_template3')

@section('title', 'Tambah Layanan Vendor')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white px-0">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Master Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
        </ol>
    </nav>

    <div class="card p-4">
        <h4 class="mb-4">Tambah Layanan Vendor</h4>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('vendor.service.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="nama_item" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama_item" id="nama_item" class="form-control @error('nama_item') is-invalid @enderror" value="{{ old('nama_item') }}" required>
                        @error('nama_item')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vendor</label>
                            <input type="text" class="form-control" value="{{ $vendor->nama_vendor ?? '-' }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control" value="{{ $vendor->kategori ?? '-' }}" disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="2" placeholder="Tulis catatan di sini..."></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="foto" class="form-label">Gambar Produk</label>
                    <input type="file" name="foto" id="foto" class="form-control mb-3 @error('foto') is-invalid @enderror" accept="image/*">
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="border rounded p-2 text-center">
                        <img id="preview-image" src="https://via.placeholder.com/200x150?text=Preview+Gambar" alt="Preview" class="img-fluid rounded">
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('vendor.service.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-label {
        font-weight: 500;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        border: 1px solid #ced4da;
        background-color: #fff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #000;
        box-shadow: 0 0 0 0.15rem rgba(0, 0, 0, 0.25);
    }
</style>

<script>
    // Preview gambar saat dipilih
    document.getElementById('foto').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('preview-image').src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
