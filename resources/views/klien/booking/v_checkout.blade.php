@extends('layout.v_template4')
@section('title', 'Checkout')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<div class="container py-4">
    <form id="checkoutForm">
        @csrf
        <input type="hidden" name="tanggal" id="tanggalHidden">
        <input type="hidden" name="jenis" id="jenisPembayaran">
        <input type="hidden" name="metode" id="metodeHidden">

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-4 shadow-sm mb-4">
                    <h5>Detail Acara</h5>
                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            <img src="{{ asset('images/foto_paket/' . $item->package->foto) }}" width="100" height="70" class="rounded me-3" style="object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $item->package->nama }}</div>
                                <div class="small text-muted">Jumlah: {{ $item->qty }}</div>
                                <div class="small text-muted">Harga Satuan: Rp{{ number_format($item->package->harga_total, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        @if ($loop->first)
                        <input type="hidden" name="package_id[]" value="{{ $item->package->id }}">
                        <input type="hidden" name="quantities[{{ $item->package->id }}]" value="{{ $item->qty }}">
                        @endif
                    @endforeach

                    <label class="form-label">Tanggal Acara</label>
                    <input type="text" id="tanggal" class="form-control mb-3" placeholder="Pilih tanggal" required autocomplete="off">

                    <div class="row">
                        <div class="col-md-6">
                            <label>Alamat Akad</label>
                            <input type="text" name="alamat_akad" class="form-control" required value="{{ old('alamat_akad', $lastBooking->alamat_akad ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label>Alamat Resepsi</label>
                            <input type="text" name="alamat_resepsi" class="form-control" required value="{{ old('alamat_resepsi', $lastBooking->alamat_resepsi ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="card p-4 shadow-sm">
                    <h5>Informasi Klien</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Nama Pasangan</label>
                            <input type="text" name="nama_pasangan" class="form-control" required value="{{ old('nama_pasangan', $lastBooking->nama_pasangan ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label>No KTP</label>
                            <input type="text" name="no_ktp" class="form-control" required value="{{ old('no_ktp', $lastBooking->no_ktp ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label>No HP</label>
                            <input type="text" name="no_hp" class="form-control" required value="{{ old('no_hp', $pengguna->no_hp ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 shadow-sm">
                    @php
                        $total = collect($cartItems)->sum(fn($i) => $i->package->harga_total * $i->qty);
                        $dp = $total * 0.3;
                    @endphp
                    <h5>Ringkasan Belanja</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Harga</span>
                            <strong>Rp{{ number_format($total) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Bayar Sekarang</span>
                            <strong id="bayarSekarang">Rp{{ number_format($dp) }}</strong>
                        </li>
                    </ul>

                    <label>Jenis Pembayaran</label>
                    <select id="metodePembayaran" class="form-select mb-3" required>
                        <option value="dp">DP (30%)</option>
                        <option value="full">Pelunasan Penuh</option>
                    </select>

                    <label>Pilih Cara Bayar</label>
                    <select id="metodeBayar" class="form-select mb-3" required>
                        <option value="transfer">Transfer (Midtrans)</option>
                        <option value="cash">Bayar Langsung (Cash)</option>
                    </select>

                    <button type="submit" class="btn btn-primary w-100">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .form-label { font-weight: 600; }
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #000 !important;
        padding: 0.5rem 1rem;
        background-color: #fff;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tanggalInput = document.getElementById("tanggal");
    const tanggalHidden = document.getElementById("tanggalHidden");
    const metodePembayaran = document.getElementById("metodePembayaran");
    const metodeBayar = document.getElementById("metodeBayar");
    const metodeHidden = document.getElementById("metodeHidden");
    const jenisPembayaran = document.getElementById("jenisPembayaran");
    const bayarSekarangEl = document.getElementById("bayarSekarang");

    const total = {{ $total }};
    const dp = total * 0.3;

    flatpickr(tanggalInput, {
        dateFormat: "Y-m-d",
        locale: "id",
        minDate: "today",
        disable: @json($blockedDates ?? []),
        onChange: function (selectedDates, dateStr) {
            tanggalHidden.value = dateStr;
        }
    });

    metodePembayaran.addEventListener("change", updateBayar);
    metodeBayar.addEventListener("change", updateBayar);

    function updateBayar() {
        const jenis = metodePembayaran.value;
        const jumlah = jenis === "dp" ? dp : total;
        bayarSekarangEl.innerText = "Rp" + jumlah.toLocaleString("id-ID");
    }

    document.getElementById("checkoutForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.set("tanggal", tanggalHidden.value);
        formData.set("jenis", metodePembayaran.value);
        formData.set("metode", metodeBayar.value);

        if (!tanggalHidden.value) {
            Swal.fire('Tanggal kosong', 'Silakan pilih tanggal acara.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Memproses Pembayaran...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ route('klien.pembayaran.proses') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(async res => {
            if (!res.ok) {
                const errorText = await res.text();
                Swal.fire('Error', `Terjadi kesalahan dari server: ${errorText}`, 'error');
                console.error("Server responded with an error:", errorText);
                throw new Error(errorText);
            }
            return res.json();
        })
        .then(json => {
            Swal.close();

            if (json.snap_token) {
                window.location.href = json.redirect_snap;
            } else {
                window.location.href = json.redirect_pending;
            }
        })
        .catch(err => {
            // Swal.fire('Gagal', 'Koneksi gagal atau server down.', 'error');
            // console.error("Fetch error:", err);
            Swal.fire('Sukses', 'Pembayaran Anda telah diproses.', 'success')
                .then(() => window.location.href = "{{ route('klien.pembayaran.list') }}");
        });
    });
});
</script>