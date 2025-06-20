@extends('layout.v_template')
@include('layout.v_nav')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold">Detail Pembayaran</h4>

    <div class="card mb-3 shadow">
        <div class="card-body">
            <p><strong>Nama Pasangan:</strong> {{ $payment->booking->nama_pasangan }}</p>
            <p><strong>Email:</strong> {{ $payment->booking->pengguna->email }}</p>
            <p><strong>Tanggal Bayar:</strong> {{ $payment->tanggal_bayar ?? '-' }}</p>
            <p><strong>Jenis:</strong> {{ ucfirst($payment->jenis) }}</p>
            <p><strong>Jumlah:</strong> Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
            <p><strong>Status:</strong>
                <span class="badge 
                    {{ $payment->status == 'berhasil' ? 'bg-success' : 
                        ($payment->status == 'menunggu_verifikasi_admin' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </p>

            @if($payment->bukti_bayar)
                <p><strong>Bukti Pembayaran:</strong></p>
                <a href="{{ asset('storage/bukti/' . $payment->bukti_bayar) }}" target="_blank">
                    <img src="{{ asset('storage/bukti/' . $payment->bukti_bayar) }}" alt="Bukti Pembayaran"
                        class="img-thumbnail mb-3" style="max-width: 300px;">
                </a>
            @endif

            @if($payment->status === 'menunggu_verifikasi_admin')
                <form method="POST" action="{{ route('admin.pesanan.verifikasi', $payment->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success mt-2">
                        Verifikasi Manual & Catat Keuangan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <h5 class="fw-bold">Detail Paket yang Dipesan</h5>
    <div class="card shadow">
        <div class="card-body">
            <ul class="list-group">
                @foreach($payment->booking->bookingDetails as $detail)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $detail->vendorService->nama_item ?? '-' }}
                        <span>Rp {{ number_format($detail->vendorService->harga ?? 0, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
