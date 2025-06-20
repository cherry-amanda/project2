@extends('layout.v_template3')

@section('title', 'Detail Layanan Vendor')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white px-0">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Master Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Data</li>
        </ol>
    </nav>

    <div class="card p-4">
        <h4 class="mb-4">Detail Layanan Vendor</h4>

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" value="{{ $service->nama_item }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($service->harga, 0, ',', '.') }}" readonly>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vendor</label>
                        <input type="text" class="form-control" value="{{ $vendor->nama_vendor ?? '-' }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" class="form-control" value="{{ $vendor->kategori ?? '-' }}" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" rows="3" readonly>{{ $service->deskripsi ?? '-' }}</textarea>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Gambar Produk</label>
                @if($service->foto && file_exists(public_path('images/vendorservices/' . $service->foto)))
                    <div class="border rounded mb-3">
                        <img src="{{ asset('images/vendorservices/' . $service->foto) }}" class="img-fluid rounded">
                    </div>
                @else
                    <div class="border rounded mb-3 d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="text-muted">Tidak ada gambar</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('vendor.service.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('vendor.service.edit', $service->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>

{{-- Styling tambahan --}}
<style>
    .form-label {
        font-weight: 500;
    }

    .form-control {
        border-radius: 8px;
        background-color: #f9f9f9;
        border: 1px solid #ced4da;
        pointer-events: none;
    }

    textarea.form-control {
        resize: none;
    }

    .img-thumbnail {
        border-radius: 8px;
        object-fit: cover;
    }
</style>
@endsection
