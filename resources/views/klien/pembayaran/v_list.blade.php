@extends('layout.v_template4')
@include('layout.v_nav4')

@section('content')
<div class="container py-4">

    {{-- === TOAST ALERT === --}}
    @if(session('status') && session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '{{ session('status') === "sukses" ? "success" : (session("status") === "pending" ? "info" : "error") }}',
                title: '{{ session("message") }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    @endif

    @if ($booking)
    {{-- === RINGKASAN PESANAN === --}}
    <div class="card mb-4 shadow border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-2">Info Pesanan</h5>
            <p><strong>Nama Pasangan:</strong> {{ $booking->nama_pasangan }}</p>
            <p><strong>Tanggal Acara:</strong> {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</p>
            <p><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($booking->status) }}</span></p>
            <p><strong>Alamat Akad:</strong> {{ $booking->alamat_akad }}</p>
            <p><strong>Alamat Resepsi:</strong> {{ $booking->alamat_resepsi }}</p>
        </div>
    </div>

    {{-- === RINCIAN PEMBAYARAN === --}}
    @php
        $dp = $booking->payments->where('jenis', 'dp')->first();
        $pelunasan = $booking->payments->where('jenis', 'pelunasan')->first();
        $full = $booking->payments->where('jenis', 'pelunasan_full')->first();
        $totalPaid = $booking->payments->where('status', 'berhasil')->sum('jumlah');
        $sisaTagihan = $booking->total_harga - $totalPaid;
        $pelunasanPending = $booking->payments->where('jenis', 'pelunasan')->where('status', '!=', 'berhasil')->first();
    @endphp

    <div class="card mb-4 shadow border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Rincian Pembayaran</h5>
            <ul>
                @if($dp)<li>DP: Rp{{ number_format($dp->jumlah, 0, ',', '.') }} ({{ ucfirst($dp->status) }})</li>@endif
                @if($pelunasan)<li>Pelunasan: Rp{{ number_format($pelunasan->jumlah, 0, ',', '.') }} ({{ ucfirst($pelunasan->status) }})</li>@endif
                @if($full)<li>Full Payment: Rp{{ number_format($full->jumlah, 0, ',', '.') }} ({{ ucfirst($full->status) }})</li>@endif
                <li><strong>Total Bayar:</strong> Rp{{ number_format($totalPaid, 0, ',', '.') }}</li>
                @if($sisaTagihan > 0)<li><strong>Sisa Tagihan:</strong> Rp{{ number_format($sisaTagihan, 0, ',', '.') }}</li>@endif
            </ul>
        </div>
    </div>

    {{-- === TABEL PEMBAYARAN === --}}
    @if($booking->payments->count())
    <div class="card mb-4 shadow border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Daftar Pembayaran</h5>
            <table class="table table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Jenis</th>
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
                        <td>{{ strtoupper($pay->jenis) }}</td>
                        <td>Rp{{ number_format($pay->jumlah, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge 
                                {{ $pay->status == 'berhasil' ? 'bg-success' : 
                                    ($pay->status == 'pending' ? 'bg-warning text-dark' : 
                                    ($pay->status == 'menunggu_verifikasi_admin' ? 'bg-info text-dark' : 'bg-danger')) }}">
                                {{ ucfirst(str_replace('_', ' ', $pay->status)) }}
                            </span>
                        </td>
                        <td>{{ $pay->tanggal_bayar ? \Carbon\Carbon::parse($pay->tanggal_bayar)->translatedFormat('d M Y H:i') : '-' }}</td>
                        <td>
                            @php
                                $dpBerhasil = $booking->payments->where('jenis', 'dp')->where('status', 'berhasil')->count() > 0;
                                $isPelunasan = $pay->jenis === 'pelunasan';
                                $canPay = $pay->order_id && $pay->snap_token;
                                $shouldPayNow = (
                                    $pay->jenis === 'pelunasan' && $dpBerhasil && $pay->status === 'pending'
                                ) || (
                                    $pay->jenis === 'pelunasan_full' && $pay->status === 'pending'
                                );
                            @endphp

                            @if ($pay->status === 'berhasil')
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#invoiceModal{{ $pay->id }}">
                                    Lihat Invoice
                                </button>
                            @elseif ($isPelunasan && !$dpBerhasil)
                                <button class="btn btn-secondary btn-sm" disabled>Menunggu DP</button>
                            @elseif ($shouldPayNow && $canPay)
                                <button onclick="payWithSnap('{{ $pay->snap_token }}',
                                        '{{ route('klien.pembayaran.sukses', $pay->id) }}',
                                        '{{ route('klien.pembayaran.pending', $pay->id) }}')"
                                        class="btn btn-primary btn-sm">
                                    Bayar Sekarang
                                </button>
                            @else
                                <span class="text-muted">Menunggu pembayaran...</span>
                            @endif
                        </td>
                    </tr>

                    {{-- === MODAL INVOICE === --}}
                    <div class="modal fade" id="invoiceModal{{ $pay->id }}" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Invoice - {{ $pay->order_id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nama Pasangan:</strong> {{ $booking->nama_pasangan }}</p>
                                    <p><strong>Tanggal Acara:</strong> {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</p>
                                    <p><strong>Jenis Pembayaran:</strong> {{ strtoupper($pay->jenis) }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($pay->status) }}</p>
                                    <p><strong>Jumlah Dibayar:</strong> Rp{{ number_format($pay->jumlah, 0, ',', '.') }}</p>
                                    <p><strong>Tanggal Pembayaran:</strong> {{ $pay->tanggal_bayar ? \Carbon\Carbon::parse($pay->tanggal_bayar)->translatedFormat('d M Y H:i') : '-' }}</p>
                                    <hr>
                                    <h6>Produk Dipesan:</h6>
                                    <ul>
                                        @foreach ($booking->bookingDetails as $detail)
                                            <li>{{ $detail->vendorService->nama_item ?? '-' }} - {{ $detail->qty }}x</li>
                                        @endforeach
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
    @endif

    @else
        <div class="alert alert-info">
            Belum ada booking atau pembayaran ditemukan.
        </div>
    @endif
</div>

{{-- === MIDTRANS SNAP SCRIPT === --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function payWithSnap(token, successUrl, pendingUrl) {
        if (!token) {
            Swal.fire('Token tidak tersedia', 'Pembayaran tidak dapat dilanjutkan.', 'error');
            return;
        }

        snap.pay(token, {
            onSuccess: function(result) {
                window.location.href = successUrl;
            },
            onPending: function(result) {
                window.location.href = pendingUrl;
            },
            onError: function(result) {
                Swal.fire('Pembayaran Gagal', 'Silakan coba lagi.', 'error');
                console.error(result);
            },
            onClose: function() {
                Swal.fire('Dibatalkan', 'Anda menutup jendela pembayaran.', 'info');
            }
        });
    }
</script>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
