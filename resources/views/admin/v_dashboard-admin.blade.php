@extends('layout.v_template')
@include('layout.v_nav')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: #f0f4f3;
}
.dashboard-hero {
    background: linear-gradient(135deg, #c8e6c9, #a5d6a7);
    color: #1b5e20;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
}
.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    border-left: 6px solid #81c784;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
}
.stat-icon {
    font-size: 2.5rem;
    color: #388e3c;
}
.section-title {
    font-weight: 600;
    color: #2e7d32;
}
.list-group-item {
    border: none;
    font-weight: 500;
    transition: 0.2s;
    border-radius: 10px;
}
.list-group-item:hover {
    background-color: #f1f8f6;
}
.card-smooth {
    background: #ffffff;
    border-radius: 16px;
    padding: 1rem 1.5rem;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.04);
    margin-bottom: 1rem;
}
.link-card {
    text-decoration: none;
    color: inherit;
}
footer {
    font-size: 0.85rem;
    color: #789262;
    margin-top: 3rem;
}
</style>
@endsection

@section('content')
<div class="container-fluid px-4">

    {{-- Hero --}}
    <div class="dashboard-hero d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Halo, Admin 👋</h2>
            <p class="mb-0">Pantau seluruh data klien, booking, dan jadwal dengan mudah dan cepat.</p>
        </div>
        <i class="bi bi-person-circle display-3"></i>
    </div>

    {{-- Statistik --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.pesanan.index') }}" class="link-card">
                <div class="stat-card text-center">
                    <i class="bi bi-cart-check-fill stat-icon"></i>
                    <h4 class="mt-3 fw-bold">{{ $todaysOrders ?? 0 }}</h4>
                    <p class="text-muted mb-0">Pesanan Hari Ini</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.pesanan.index') }}" class="link-card">
                <div class="stat-card text-center">
                    <i class="bi bi-journal-check stat-icon"></i>
                    <h4 class="mt-3 fw-bold">{{ $totalBooking ?? 0 }}</h4>
                    <p class="text-muted mb-0">Total Booking</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.keuangan.index') }}" class="link-card">
                <div class="stat-card text-center">
                    <i class="bi bi-cash-coin stat-icon"></i>
                    <h4 class="mt-3 fw-bold">Rp {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}</h4>
                    <p class="text-muted mb-0">Total Pemasukan</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <i class="bi bi-people-fill stat-icon"></i>
                <h4 class="mt-3 fw-bold">{{ $newClientsThisWeek ?? 0 }}</h4>
                <p class="text-muted mb-0">Klien Baru (Minggu Ini)</p>
            </div>
        </div>
    </div>

    {{-- Booking Menunggu Konfirmasi --}}
    <div class="row g-4 mb-4">
        <div class="col-md-12">
            <a href="{{ route('admin.pesanan.index') }}" class="link-card">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="section-title mb-1">⏳ Booking Menunggu Konfirmasi</h5>
                        <p class="text-muted mb-0">Total pesanan yang belum dikonfirmasi</p>
                    </div>
                    <h4 class="fw-bold text-danger mb-0">{{ $pendingVerifications ?? 0 }}</h4>
                </div>
            </a>
        </div>
    </div>

    {{-- Tipe Layanan & Info --}}
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="stat-card">
                <h5 class="section-title">📢 Info & Notifikasi</h5>
                <p class="text-muted">Kelola semua kegiatan, vendor, dan keuangan dengan navigasi di sidebar kiri.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h5 class="section-title">🎁 Tipe Layanan</h5>
                <ul class="list-group list-group-flush">
                    @forelse ($dataTipePaket as $tipe)
                        <a href="{{ route('admin.package.index', ['tipe' => $tipe->tipe]) }}" class="link-card">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-box2-heart me-2 text-success"></i>{{ ucfirst($tipe->tipe) }}</span>
                                <span class="badge rounded-pill bg-success">{{ $tipe->total }}</span>
                            </li>
                        </a>
                    @empty
                        <li class="list-group-item text-muted">Belum ada data tipe paket</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Jadwal 7 Hari --}}
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="stat-card">
                <h5 class="section-title">📅 Jadwal 7 Hari ke Depan</h5>
                @forelse($upcomingEvents as $event)
                    <div class="card-smooth d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-dark">
                                <i class="bi bi-calendar-event me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($event->booking->tanggal)->translatedFormat('l, d M Y') }}
                            </strong>
                            <div class="text-muted small mt-1">
                                Klien: {{ $event->booking->pengguna->nama ?? '-' }}<br>
                                Pasangan: {{ $event->booking->nama_pasangan ?? '-' }}
                            </div>
                        </div>
                        <a href="{{ route('admin.event.show', $event->id) }}" class="btn btn-outline-success rounded-pill btn-sm">Lihat Detail</a>
                    </div>
                @empty
                    <p class="text-muted">Tidak ada kegiatan dalam 7 hari ke depan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <footer class="text-center text-muted">
        &copy; {{ date('Y') }} Infinity Organizer | Admin Dashboard
    </footer>
</div>
@endsection
