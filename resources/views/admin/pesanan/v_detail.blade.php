@extends('layout.v_template')
@include('layout.v_nav')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4 text-secondary">📄 Detail Pesanan & Pembayaran Klien</h4>

    {{-- Informasi Pembayaran --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">🧾 Informasi Pembayaran</h5>

            <div class="row mb-2">
                <div class="col-md-6"><strong>Nama Pasangan:</strong> {{ $payment->booking->nama_pasangan }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $payment->booking->pengguna->email }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6"><strong>Tanggal Bayar:</strong> {{ $payment->tanggal_bayar ?? '-' }}</div>
                <div class="col-md-6">
                    <strong>Jenis:</strong>
                    <span class="badge bg-secondary"><i class="bi bi-cash-coin me-1"></i>{{ ucfirst($payment->jenis) }}</span>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6"><strong>Jumlah:</strong> Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <span class="badge 
                        {{ $payment->status == 'berhasil' ? 'bg-success' : 
                            ($payment->status == 'menunggu_verifikasi_admin' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                        <i class="bi bi-info-circle me-1"></i> {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                    </span>
                </div>
            </div>

            @if($payment->bukti_bayar)
            <hr>
            <p class="mb-1"><strong>Bukti Pembayaran:</strong></p>
            <a href="{{ asset('storage/bukti/' . $payment->bukti_bayar) }}" target="_blank">
                <img src="{{ asset('storage/bukti/' . $payment->bukti_bayar) }}" class="img-thumbnail" style="max-width: 300px;">
            </a>
            @endif

            @if($payment->status === 'menunggu_verifikasi_admin')
            <hr>
            <form method="POST" action="{{ route('admin.pesanan.verifikasi', $payment->id) }}">
                @csrf
                <button type="submit" class="btn btn-outline-success mt-2">
                    <i class="bi bi-check-circle me-1"></i> Verifikasi Manual & Catat Keuangan
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Detail Paket --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">🎁 Detail Paket yang Dipesan</h5>

            <p><strong>Nama Paket:</strong> {{ $payment->booking->package->nama ?? '-' }}</p>

            <ul class="list-group list-group-flush">
                @foreach($payment->booking->package->vendorServices as $rab)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">{{ $rab->vendorService->nama_item ?? '-' }}</div>
                        <small class="text-muted">Vendor: {{ $rab->vendorService->vendor->pengguna->nama ?? '-' }}</small>
                    </div>
                    <span>Rp {{ number_format($rab->vendorService->harga ?? 0, 0, ',', '.') }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- Tambahkan CDN jika belum ada --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
