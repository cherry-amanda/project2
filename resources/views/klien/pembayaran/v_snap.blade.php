@extends('layout.v_template4')
@include('layout.v_nav4')

@section('content')
<div class="container py-4">
    <div class="text-center">
        <h3 class="fw-bold mb-4">Pembayaran</h3>
        <p>Silakan lanjutkan pembayaran dengan menekan tombol di bawah ini:</p>

        <button id="pay-button" class="btn btn-lg btn-success px-5 py-2">Bayar Sekarang</button>

        <p class="mt-3 text-muted">Jika Anda menutup halaman ini, Anda masih dapat melanjutkan pembayaran dari halaman daftar pembayaran.</p>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay("{{ $snapToken }}", {
            onSuccess: function (result) {
                window.location.href = "{{ route('klien.pembayaran.sukses') }}";
            },
            onPending: function (result) {
                window.location.href = "{{ route('klien.pembayaran.pending') }}";
            },
            onError: function (result) {
                Swal.fire('Pembayaran Gagal', 'Silakan coba lagi.', 'error');
            },
            onClose: function () {
                Swal.fire('Dibatalkan', 'Anda menutup jendela pembayaran.', 'info');
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

{{-- FILE CETAK INVOICE (OPSIONAL) --}}
@if(isset($invoice))
@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice-section, #invoice-section * {
            visibility: visible;
        }
        #invoice-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endpush

<div class="container my-5" id="invoice-section">
    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">INVOICE PEMBAYARAN</h5>
            <small>{{ now()->format('d M Y H:i') }}</small>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Dari:</h6>
                    <p class="mb-0">Wedding Organizer</p>
                    <small>Jl. Cinta No. 88, Jakarta</small><br>
                    <small>Telp: 021-1234567</small>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Kepada:</h6>
                    <p class="mb-0">{{ $booking->nama_pasangan }}</p>
                    <small>Email: {{ $booking->pengguna->email ?? '-' }}</small><br>
                    <small>Tanggal Acara: {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</small>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking->bookingDetails as $detail)
                    <tr>
                        <td>{{ $detail->vendorService->nama_item ?? '-' }}</td>
                        <td class="text-center">{{ $detail->qty }}</td>
                        <td class="text-end">Rp{{ number_format($detail->vendorService->harga_total ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end">Rp{{ number_format(($detail->vendorService->harga_total ?? 0) * $detail->qty, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total Booking</strong></td>
                        <td class="text-end">Rp{{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Pembayaran ({{ ucfirst($payment->jenis) }})</strong></td>
                        <td class="text-end">Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <p class="mt-4">Terima kasih telah menggunakan layanan kami. Semoga acara Anda berjalan lancar dan berkesan.</p>
            <div class="text-end">
                <button onclick="window.print()" class="btn btn-outline-dark">Cetak Invoice</button>
            </div>
        </div>
    </div>
</div>
@endif
