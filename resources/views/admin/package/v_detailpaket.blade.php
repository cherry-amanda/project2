@extends('layout.v_template')

@section('content')
<div class="container py-4">

    <a href="{{ route('admin.package.index') }}" class="btn btn-secondary mb-4">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="card shadow rounded-4">
        <div class="row g-0">
            <div class="col-md-6">
                @if($package->photos->count())
                <div id="carouselDetail" class="carousel slide rounded-start overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($package->photos as $key => $photo)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <img src="{{ asset('images/foto_paket/' . $photo->filename) }}" class="d-block w-100" style="object-fit: cover; height: 100%; max-height: 400px;">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetail" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselDetail" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                @else
                <img src="{{ asset('images/default.jpg') }}" class="img-fluid rounded-start w-100" style="max-height: 400px; object-fit: cover;">
                @endif
            </div>

            <div class="col-md-6">
                <div class="card-body p-4">
                    <h3 class="fw-bold">{{ $package->nama }}</h3>
                    <p class="mb-1 text-muted">Tipe: <span class="fw-semibold text-dark">{{ ucfirst($package->type) }}</span></p>
                    <p class="mb-3">{{ $package->deskripsi }}</p>

                    <h5 class="fw-bold">Total Harga:</h5>
                    <h4 class="text-success mb-4">Rp {{ number_format($package->harga_total, 0, ',', '.') }}</h4>

                    @if($package->type == 'paket' && $package->packageRabs->count())
                        <hr>
                        <h5 class="fw-bold mb-3">Detail RAB</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle table-bordered rounded">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th style="width: 35%;">Item</th>
                                        <th style="width: 20%;">Harga</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($package->packageRabs as $rab)
                                    <tr>
                                        <td>
                                            {{ $rab->vendorService->nama_item ?? $rab->nama_manual ?? '-' }}
                                            <br>
                                            <span class="badge bg-secondary mt-1">{{ $rab->category->nama_kategori ?? '-' }}</span>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($rab->harga_item, 0, ',', '.') }}</td>
                                        <td>{{ $rab->deskripsi ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($package->type == 'jasa')
                        <hr>
                        <h5 class="fw-bold mb-2">Detail Jasa</h5>
                        <p><strong>Nama Jasa:</strong> {{ $package->jasa->nama_item ?? '-' }}</p>
                        <p><strong>Kategori:</strong> {{ $package->jasa->kategori ?? '-' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
