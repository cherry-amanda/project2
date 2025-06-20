@extends('layout.v_template')
@include('layout.v_nav')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* UPGRADE TAMPAILAN DASHBOARD */

.dashboard-hero {
    background: linear-gradient(90deg,rgb(22, 21, 23), #2575fc);
    color: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.stat-card {
    background: white;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    border-left: 5px solid transparent;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    border-left: 5px solid #2575fc;
}

.stat-icon {
    font-size: 2.2rem;
    opacity: 0.85;
    transition: all 0.2s ease-in-out;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1);
}

.section-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.list-group-item {
    border: none;
    font-weight: 500;
    transition: all 0.2s ease;
    border-radius: 8px;
    padding: 10px 14px;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}

.badge {
    font-size: 0.75rem;
}

footer {
    font-size: 0.875rem;
    color: #777;
}

</style>
@endsection

@section('content')
<div class="container-fluid px-4">

    {{-- Hero Section --}}
    <div class="dashboard-hero mb-4 d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h3 class="mb-1">Selamat Datang, Admin!</h3>
            <p class="mb-0">Pantau seluruh aktivitas Wedding Organizer kamu dalam satu tampilan.</p>
        </div>
        <div>
            <i class="bi bi-person-circle fs-1 text-white"></i>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.pesanan.index') }}" class="link-card">
                <div class="stat-card text-center">
                    <i class="bi bi-cart-check stat-icon text-success"></i>
                    <div class="fw-bold fs-4 mt-2">{{ $todaysOrders ?? 0 }}</div>
                    <div class="text-muted">Today's Orders</div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.pesanan.index') }}" class="link-card">
                <div class="stat-card text-center">
                    <i class="bi bi-journal-check stat-icon text-warning"></i>
                    <div class="fw-bold fs-4 mt-2">{{ $totalBooking ?? 0 }}</div>
                    <div class="text-muted">Total Orders</div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.keuangan.index') }}" class="link-card">
                <div class="stat-card text-center">
                    <i class="bi bi-cash-coin stat-icon text-primary"></i>
                    <div class="fw-bold fs-4 mt-2">Rp {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}</div>
                    <div class="text-muted">Total Profit</div>
                </div>
            </a>
        </div>
    </div>

    {{-- Tipe Layanan --}}
    <div class="row g-3">
        <div class="col-md-8">
            <div class="stat-card">
                <h5 class="section-title">Notifikasi / Info</h5>
                <p class="text-muted">Kamu bisa mengelola booking, vendor, pembayaran, dan lainnya dengan lebih mudah melalui navigasi sidebar atau statistik dashboard ini.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6 class="section-title">Tipe Layanan</h6>
                <ul class="list-group list-group-flush">
                    @forelse ($dataTipePaket as $tipe)
                        <a href="{{ route('admin.package.index', ['tipe' => $tipe->tipe]) }}" class="link-card">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-box2-heart me-2 text-info"></i>{{ ucfirst($tipe->tipe) }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $tipe->total }}</span>
                            </li>
                        </a>
                    @empty
                        <li class="list-group-item text-muted">Belum ada data tipe paket</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <footer class="text-muted text-center mt-4">
        © {{ date('Y') }} Infinity Organizer
    </footer>

</div>
@endsection
