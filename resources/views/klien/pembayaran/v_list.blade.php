@extends('layout.v_template4')
@include('layout.v_nav4')

@section('content')
<div class="container py-4">
    @if(session('status') && session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '{{ session("status") === "sukses" ? "success" : (session("status") === "pending" ? "info" : "error") }}',
                title: '{{ session("message") }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    @endif

    @if ($bookings->count())
        @foreach ($bookings as $booking)
        <div class="card mb-4 shadow border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <strong>Booking #{{ $loop->iteration }} - {{ $booking->package->nama ?? 'Paket tidak ditemukan' }}</strong>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#bookingDetail{{ $booking->id }}">
                    Lihat Detail
                </button>
            </div>

            <div id="bookingDetail{{ $booking->id }}" class="collapse">
                <div class="card-body">
                    {{-- Info Pesanan --}}
                    <h5 class="fw-bold mb-2">Info Pesanan</h5>
                    <p><strong>Nama Pasangan:</strong> {{ $booking->nama_pasangan }}</p>
                    <p><strong>Tanggal Acara:</strong> {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($booking->status) }}</span></p>
                    <p><strong>Alamat Akad:</strong> {{ $booking->alamat_akad }}</p>
                    <p><strong>Alamat Resepsi:</strong> {{ $booking->alamat_resepsi }}</p>
                    @if($booking->package)
                        <p><strong>Paket Dipesan:</strong> {{ $booking->package->nama }}</p>
                        <p><strong>Harga Paket:</strong> Rp{{ number_format($booking->package->harga_total, 0, ',', '.') }}</p>
                    @endif

                    @php
                        $total = $booking->package->harga_total ?? 0;
                        $dp = $booking->payments->where('jenis', 'dp')->first();
                        $pelunasan = $booking->payments->where('jenis', 'pelunasan')->first();
                        $full = $booking->payments->where('jenis', 'full')->first();
                        $totalPaid = $booking->payments->where('status', 'berhasil')->sum('jumlah');
                        $sisaTagihan = $total - $totalPaid;
                    @endphp

                    {{-- Rincian Pembayaran --}}
                    <h5 class="fw-bold mt-4 mb-2">Rincian Pembayaran</h5>
                    <ul>
                        <li><strong>Total Pesanan:</strong> Rp{{ number_format($total) }}</li>
                        @if($dp)<li>DP: Rp{{ number_format($dp->jumlah, 0, ',', '.') }} ({{ ucfirst($dp->status) }})</li>@endif
                        @if($pelunasan)<li>Pelunasan: Rp{{ number_format($pelunasan->jumlah, 0, ',', '.') }} ({{ ucfirst($pelunasan->status) }})</li>@endif
                        @if($full)<li>Full Payment: Rp{{ number_format($full->jumlah, 0, ',', '.') }} ({{ ucfirst($full->status) }})</li>@endif
                        <li><strong>Total Dibayar:</strong> Rp{{ number_format($totalPaid, 0, ',', '.') }}</li>
                        @if($sisaTagihan > 0)
                        <li><strong>Sisa Tagihan:</strong> Rp{{ number_format($sisaTagihan, 0, ',', '.') }}</li>
                        @endif
                    </ul>

                    {{-- Daftar Pembayaran --}}
                    <h5 class="fw-bold mt-4 mb-2">Daftar Pembayaran</h5>
                    <table class="table table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Order ID</th>
                                <th>Jenis</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->payments as $i => $pay)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $pay->order_id ?? '-' }}</td>
                                <td>{{ ucfirst($pay->jenis) }}</td>
                                <td>{{ ucfirst($pay->metode) }}</td>
                                <td>Rp{{ number_format($pay->jumlah, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $pay->status === 'berhasil' ? 'bg-success' : 
                                            ($pay->status === 'pending' ? 'bg-warning text-dark' : 
                                            ($pay->status === 'menunggu_verifikasi_admin' ? 'bg-info text-dark' : 'bg-danger')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $pay->status)) }}
                                    </span>
                                </td>
                                <td>{{ $pay->tanggal_bayar ? \Carbon\Carbon::parse($pay->tanggal_bayar)->translatedFormat('d M Y H:i') : '-' }}</td>
                                <td>
                                    @php
                                        $canPay = $pay->snap_token && $pay->order_id;
                                        $shouldPayNow = in_array($pay->jenis, ['dp','full']) && $pay->status === 'pending' && $pay->metode === 'transfer';
                                        $canRemakePayment = $pay->jenis === 'pelunasan' && $pay->metode === 'transfer' && $pay->status === 'pending';
                                    @endphp

                                    @if ($pay->status === 'berhasil')
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#invoiceModal{{ $pay->id }}">
                                            Lihat Invoice
                                        </button>
                                    @elseif ($pay->jenis === 'pelunasan' && $pay->metode === 'cash' && $pay->status === 'menunggu_verifikasi_admin')
                                        <span class="text-muted">Menunggu Verifikasi Admin (Cash)</span>
                                    @elseif ($shouldPayNow && $canPay)
                                        <button class="btn btn-primary btn-sm" onclick="payWithSnap('{{ $pay->snap_token }}', '{{ route('klien.pembayaran.sukses') }}', '{{ route('klien.pembayaran.pending') }}')">
                                            Bayar Sekarang
                                        </button>
                                    @elseif ($canRemakePayment)
                                        <a href="{{ route('klien.pembayaran.pelunasan', $pay->id) }}" class="btn btn-warning btn-sm">
                                            Buat Ulang Pembayaran
                                        </a>
                                    @else
                                        <span class="text-muted">Menunggu pembayaran...</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- MODAL INVOICE --}}
                            <div class="modal fade" id="invoiceModal{{ $pay->id }}" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Invoice - {{ $pay->order_id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nama Pasangan:</strong> {{ $booking->nama_pasangan }}</p>
                                            <p><strong>Tanggal Acara:</strong> {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</p>
                                            <p><strong>Jenis Pembayaran:</strong> {{ ucfirst($pay->jenis) }}</p>
                                            <p><strong>Metode:</strong> {{ ucfirst($pay->metode) }}</p>
                                            <p><strong>Status:</strong> {{ ucfirst($pay->status) }}</p>
                                            <p><strong>Jumlah Dibayar:</strong> Rp{{ number_format($pay->jumlah, 0, ',', '.') }}</p>
                                            <p><strong>Tanggal Pembayaran:</strong> {{ $pay->tanggal_bayar ? \Carbon\Carbon::parse($pay->tanggal_bayar)->translatedFormat('d M Y H:i') : '-' }}</p>
                                            <hr>
                                            <h6>Produk Dipesan:</h6>
                                            <ul>
                                                @if($booking->package)
                                                    <li>{{ $booking->package->nama }} - 1x</li>
                                                @else
                                                    <li>Layanan/Paket tidak tersedia</li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button onclick="window.print()" class="btn btn-primary">Cetak</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info text-center">
            Belum ada booking atau pembayaran ditemukan.
        </div>
    @endif
</div>

{{-- Midtrans & SweetAlert2 --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function payWithSnap(token, successUrl, pendingUrl) {
        if (!token) {
            Swal.fire('Token tidak tersedia', 'Pembayaran tidak dapat dilanjutkan.', 'error');
            return;
        }

        Swal.fire({
            title: 'Menghubungkan ke Midtrans...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        snap.pay(token, {
            onSuccess: () => window.location.href = successUrl,
            onPending: () => window.location.href = pendingUrl,
            onError: () => Swal.fire('Pembayaran Gagal', 'Silakan coba lagi.', 'error'),
            onClose: () => Swal.fire('Dibatalkan', 'Anda menutup jendela pembayaran.', 'info')
        });
    }
</script>
@endsection
