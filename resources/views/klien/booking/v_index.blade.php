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

    .btn-rab {
        background-color: #f2f2f2;
        border: none;
        width: 100%;
        padding: 8px;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background-color 0.3s;
    }

    .btn-rab:hover {
        background-color: #e2e2e2;
    }

    .font-poppins {
        font-family: 'Poppins', sans-serif;
    }

    .input-group-text {
        background-color: #e9ecef;
        font-weight: 500;
    }

    .form-label {
        margin-bottom: 4px;
    }

    .input-wrapper {
        margin-bottom: 1rem;
    }

    .form-check {
        margin-bottom: 5px;
    }

    .badge-custom {
        font-size: 0.8rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
</style>

<div class="container mt-4">
    <h2 class="title-elegant">Pilih Paket Pernikahan</h2>

    <!-- Filter -->
    <form method="GET" class="filter-section font-poppins">
        <div class="row">
            <!-- Harga -->
            <div class="col-md-5 input-wrapper">
                <label class="form-label fw-semibold">Filter Harga (Budget):</label>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text">Min</span>
                            <input type="number" name="min_harga" class="form-control border border-dark rounded" placeholder="Harga Minimal" value="{{ request('min_harga') }}">

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text">Max</span>
                            <input type="number" name="max_harga" class="form-control border border-dark rounded" placeholder="Harga Maxsimal" value="{{ request('max_harga') }}">
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('klien.cart') }}" class="btn btn-primary position-fixed shadow-lg"
                style="bottom: 30px; right: 30px; border-radius: 50%; width: 60px; height: 60px; z-index: 9999;">
                    <i class="fas fa-shopping-cart fa-lg"></i>
            </a>
            <!-- Tipe -->
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

            <!-- Tombol -->
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-50">
                    <i class="fas fa-filter me-1"></i> Terapkan Filter
                </button>
                <a href="{{ route('klien.booking.index') }}" class="btn btn-outline-secondary w-50">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <!-- List Paket -->
    <div class="row">
        @forelse($packages as $p)
        <div class="col-md-4 mb-4">
            <div class="card card-paket shadow-sm h-100">
                <img src="{{ asset('images/foto_paket/' . $p->foto) }}" class="card-img-top" height="220" style="object-fit: cover;">

                <div class="card-body font-poppins">
                    <span class="badge bg-info text-dark badge-custom mb-2">
                        <i class="fas fa-tags me-1"></i> {{ ucfirst($p->type) }}
                    </span>
                    <h5 class="card-title fw-bold">{{ $p->nama }}</h5>
                    <p class="card-text text-success">Rp{{ number_format($p->harga_total, 0, ',', '.') }}</p>

                    @if($p->type == 'paket' && $p->packageRabs->count())
                    <h6 class="text-secondary fw-semibold">RAB Ringkas:</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th class="text-end">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($p->packageRabs->take(2) as $rab)
                            <tr>
                                <td>
                                    <strong>{{ $rab->nama_item }}</strong><br>
                                    <small class="text-muted">{{ $rab->deskripsi }}</small>
                                </td>
                                <td class="text-end">Rp{{ number_format($rab->harga_item, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button class="btn btn-rab" data-bs-toggle="modal" data-bs-target="#modalRAB{{ $p->id }}">
                        <i class="fas fa-eye me-1"></i> Lihat Detail RAB
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

        {{-- Modal RAB --}}
        <div class="modal fade" id="modalRAB{{ $p->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $p->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel{{ $p->id }}">Detail RAB: {{ $p->nama }}</h5>
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
</div>
<!-- Tombol Keranjang -->



@endsection
