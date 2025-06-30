@extends('layout.v_template')

@section('content')
<div class="container py-4">
    <a href="{{ route('admin.package.index') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-6">
                @if($package->photos->count())
                <div id="carouselDetail" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($package->photos as $key => $photo)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <img src="{{ asset('images/foto_paket/' . $photo->filename) }}" class="d-block w-100" style="object-fit: cover; aspect-ratio: 16/9;">
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
                <img src="{{ asset('images/default.jpg') }}" class="img-fluid rounded-start" alt="default">
                @endif
            </div>

            <div class="col-md-6">
                <div class="card-body">
                    <h3 class="card-title">{{ $package->nama }}</h3>
                    <p class="text-muted">Tipe: <strong>{{ ucfirst($package->type) }}</strong></p>
                    <p class="card-text mb-3">{{ $package->deskripsi }}</p>
                    <h5 class="fw-bold mb-4">Total Harga: <span class="text-success">Rp {{ number_format($package->harga_total, 0, ',', '.') }}</span></h5>

                    @if($package->type == 'paket' && $package->packageRabs->count())
                        <hr>
                        <h5 class="mb-3">Detail RAB</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>Item</th>
                                        <th>Harga</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($package->packageRabs as $rab)
                                    <tr>
                                        <td>{{ $rab->vendorService->nama_item ?? '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($rab->harga_item, 0, ',', '.') }}</td>
                                        <td>{{ $rab->deskripsi }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($package->type == 'jasa')
                        <hr>
                        <h5 class="mb-3">Detail Jasa</h5>
                        <p><strong>Nama Jasa:</strong> {{ $package->jasa->nama_item ?? '-' }}</p>
                        <p><strong>Kategori:</strong> {{ $package->jasa->kategori ?? '-' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
