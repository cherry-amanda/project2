@extends('layout.v_template4')
@section('title', 'Pilih Paket Pernikahan')

@section('content')
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins&display=swap" rel="stylesheet">

<style>
    .btn-floating-cart {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        width: 60px;
        height: 60px;
        border-radius: 50%;
    }

    .title-elegant {
        font-family: 'Great Vibes', cursive;
        font-size: 2.8rem;
        color: #c77dff;
        text-align: center;
        margin-bottom: 2rem;
    }

    .filter-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .card-paket {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .card-paket:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .foto-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(0,0,0,0.6);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        cursor: pointer;
        z-index: 10;
    }

    .carousel-img {
        object-fit: contain;
        max-height: 80vh;
    }
</style>

<div class="container mt-4">
    <h2 class="title-elegant">Pilih Paket Pernikahan</h2>

    <!-- Filter -->
    <form method="GET" class="filter-section font-poppins">
        <div class="row">
            <div class="col-md-5 input-wrapper">
                <label class="form-label fw-semibold">Filter Harga (Budget):</label>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text">Min</span>
                            <input type="number" name="min_harga" class="form-control border-dark rounded" placeholder="Minimal" value="{{ request('min_harga') }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text">Max</span>
                            <input type="number" name="max_harga" class="form-control border-dark rounded" placeholder="Maksimal" value="{{ request('max_harga') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 input-wrapper">
                <label class="form-label fw-semibold">Tipe Paket:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="type[]" value="paket" {{ is_array(request('type')) && in_array('paket', request('type')) ? 'checked' : '' }}>
                    <label class="form-check-label">Paket</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="type[]" value="jasa" {{ is_array(request('type')) && in_array('jasa', request('type')) ? 'checked' : '' }}>
                    <label class="form-check-label">Jasa</label>
                </div>
            </div>

            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-50">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('klien.booking.index') }}" class="btn btn-outline-secondary w-50">Reset</a>
            </div>
        </div>
    </form>

    <!-- List Paket -->
    <div class="row">
        @forelse($packages as $p)
        <div class="col-md-4 mb-4">
            <div class="card card-paket shadow-sm h-100">
                <div class="position-relative" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $p->id }}" style="cursor:pointer;">
                    @php
                        $first = $p->photos->first();
                        $img = $first ? asset('images/foto_paket/' . $first->filename) : asset('images/default.jpg');
                    @endphp
                    <img src="{{ $img }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                    <div class="foto-overlay">
                        <i class="bi bi-images me-1"></i> Lihat Foto
                    </div>
                </div>

                <div class="card-body font-poppins">
                    <span class="badge bg-info text-dark badge-custom mb-2">
                        <i class="fas fa-tags me-1"></i> {{ ucfirst($p->type) }}
                    </span>
                    <h5 class="card-title fw-bold">{{ $p->nama }}</h5>
                    <p class="text-muted small">{{ Str::limit($p->deskripsi, 100) }}</p> {{-- Tambahan baris ini --}}
                    <p class="text-success fw-semibold">Rp{{ number_format($p->harga_total, 0, ',', '.') }}</p>

                    @if($p->type == 'paket' && $p->packageRabs->count())
                    <h6 class="text-secondary fw-semibold">RAB Ringkas:</h6>
                    <ul class="list-group list-group-flush mb-2">
                        @foreach($p->packageRabs->take(2) as $rab)
                        <li class="list-group-item small">
                            <strong>{{ $rab->nama_item }}</strong> - Rp{{ number_format($rab->harga_item, 0, ',', '.') }}
                        </li>
                        @endforeach
                    </ul>
                    <button class="btn btn-rab w-100" data-bs-toggle="modal" data-bs-target="#modalRAB{{ $p->id }}">
                        <i class="fas fa-eye me-1"></i> Detail RAB
                    </button>
                    @endif

                    <form action="{{ route('klien.cart.add', ['id' => $p->id]) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100 mb-2">
                            <i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang
                        </button>
                    </form>

                    <a href="{{ route('klien.checkout.now', ['id' => $p->id]) }}" class="btn btn-success w-100">
                        <i class="fas fa-bolt me-2"></i> Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>

        {{-- Modal Foto --}}
        <div class="modal fade" id="modalFoto{{ $p->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-body p-0">
                        <div id="carouselFoto{{ $p->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($p->photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('images/foto_paket/' . $photo->filename) }}" class="d-block w-100 carousel-img">
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselFoto{{ $p->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselFoto{{ $p->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal RAB --}}
        <div class="modal fade" id="modalRAB{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail RAB: {{ $p->nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($p->packageRabs as $rab)
                                <tr>
                                    <td>{{ $rab->nama_item }}</td>
                                    <td>{{ $rab->deskripsi }}</td>
                                    <td class="text-end">Rp{{ number_format($rab->harga_item, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">Paket tidak ditemukan.</div>
        </div>
        @endforelse
    </div>

    <!-- Floating Keranjang -->
    <a href="{{ route('klien.cart') }}" class="btn btn-primary shadow btn-floating-cart d-flex align-items-center justify-content-center">
        <i class="fas fa-shopping-cart fa-lg"></i>
    </a>
</div>
@endsection
