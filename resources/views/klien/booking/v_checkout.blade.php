@extends('layout.v_template4') 
@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <div class="row g-4">

        {{-- INFORMASI ACARA --}}
        <div class="col-lg-8">
            <div class="card p-4 shadow-sm mb-4">
                <h5 class="mb-3">Detail Acara</h5>
                <form id="checkoutForm" action="{{ route('klien.payment.proses') }}" method="POST">
                    @csrf

                    @if(isset($cartItems))
                        @foreach($cartItems as $item)
                            <input type="hidden" name="package_id[]" value="{{ $item->package->id }}">
                            <input type="hidden" name="quantities[{{ $item->package->id }}]" value="{{ $item->qty }}">
                        @endforeach
                    @elseif(isset($package))
                        <input type="hidden" name="package_id[]" value="{{ $package->id }}">
                        <input type="hidden" name="quantities[{{ $package->id }}]" value="1">
                    @endif

                    <input type="hidden" name="tanggal" id="tanggalHidden">
                    <input type="hidden" name="jenis" id="jenisPembayaran">

                    <label class="form-label">Tanggal Acara</label>
                    <input type="text" id="tanggal" class="form-control mb-3" placeholder="Pilih tanggal" required readonly autocomplete="off">

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Alamat Akad</label>
                            <input type="text" name="alamat_akad" class="form-control mb-2" placeholder="Alamat Akad" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Resepsi</label>
                            <input type="text" name="alamat_resepsi" class="form-control mb-2" placeholder="Alamat Resepsi" required>
                        </div>
                    </div>
            </div>

            {{-- INFORMASI PRIBADI --}}
            <div class="card p-4 shadow-sm">
                <h5 class="mb-3">Informasi Klien</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Nama Pasangan</label>
                        <input type="text" name="nama_pasangan" class="form-control mb-2" placeholder="Nama Pasangan" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No KTP</label>
                        <input type="text" name="no_ktp" class="form-control mb-2" placeholder="No KTP" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No HP</label>
                        <input type="text" name="no_hp" value="{{ $pengguna->no_hp ?? '' }}" class="form-control mb-2" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- RINGKASAN BELANJA --}}
        <div class="col-lg-4">
            <div class="card p-4 shadow-sm">
                <h5 class="mb-3">Ringkasan Belanja</h5>
                @php
                    $total = 0;
                    if (isset($cartItems)) {
                        foreach($cartItems as $item) {
                            $total += $item->package->harga_total * $item->qty;
                        }
                    } elseif (isset($package)) {
                        $total = $package->harga_total;
                    }
                    $dp = $total * 0.5;
                @endphp

                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total Harga</span>
                        <span id="totalHarga">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Bayar Sekarang</span>
                        <span id="bayarSekarang">Rp{{ number_format($dp, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between fw-bold">
                        <span>Sisa Pembayaran</span>
                        <span id="sisaPembayaran">Rp{{ number_format($dp, 0, ',', '.') }}</span>
                    </li>
                </ul>

                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-select" id="metodePembayaran" required>
                        <option value="dp">DP (50%)</option>
                        <option value="full">Pelunasan Penuh</option>
                    </select>
                </div>

                <div class="mb-3">
                    @if(isset($cartItems))
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="{{ asset('images/foto_paket/' . $item->package->foto) }}" width="60" height="50" style="object-fit: cover; border-radius: 8px;">
                                <div>
                                    <strong>{{ $item->package->nama }}</strong><br>
                                    <small>x{{ $item->qty }}</small>
                                </div>
                            </div>
                        @endforeach
                    @elseif(isset($package))
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="{{ asset('images/foto_paket/' . $package->foto) }}" width="60" height="50" style="object-fit: cover; border-radius: 8px;">
                            <div>
                                <strong>{{ $package->nama }}</strong><br>
                                <small>x1</small>
                            </div>
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary w-100" onclick="return setJenisPembayaran()">Bayar Sekarang</button>
            </div>
        </div>
        </form>
    </div>
</div>

{{-- Include Flatpickr --}}
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    const blockedDates = @json($blockedDates);
    const tanggalInput = document.getElementById("tanggal");
    const tanggalHidden = document.getElementById("tanggalHidden");

    flatpickr(tanggalInput, {
        dateFormat: "Y-m-d",
        locale: "id",
        minDate: "today",
        maxDate: new Date().fp_incr(365),
        disable: blockedDates,
        allowInput: false,
        onChange: function(selectedDates, dateStr) {
            tanggalHidden.value = dateStr;
        },
        onDayCreate: function(_, __, ___, dayElem) {
            const date = dayElem.dateObj.toISOString().split('T')[0];
            if (blockedDates.includes(date)) {
                dayElem.classList.add("flatpickr-blocked");
            } else {
                dayElem.classList.add("flatpickr-available");
            }
        }
    });

    // Isi tanggalHidden secara default saat halaman dibuka
    if (!tanggalHidden.value && tanggalInput.value) {
        tanggalHidden.value = tanggalInput.value;
    }

    const metodePembayaran = document.getElementById("metodePembayaran");
    const bayarSekarang = document.getElementById("bayarSekarang");
    const sisaPembayaran = document.getElementById("sisaPembayaran");

    const total = {{ $total }};
    const dp = total * 0.5;

    metodePembayaran.addEventListener("change", function() {
        if (this.value === "dp") {
            bayarSekarang.innerText = "Rp" + dp.toLocaleString("id-ID");
            sisaPembayaran.innerText = "Rp" + dp.toLocaleString("id-ID");
        } else {
            bayarSekarang.innerText = "Rp" + total.toLocaleString("id-ID");
            sisaPembayaran.innerText = "Rp0";
        }
    });

    function setJenisPembayaran() {
        const metode = document.getElementById("metodePembayaran").value;
        const jenisField = document.getElementById("jenisPembayaran");
        const tanggal = tanggalHidden.value;

        if (!tanggal) {
            alert("Silakan pilih tanggal acara terlebih dahulu.");
            return false;
        }

        jenisField.value = (metode === "dp") ? "dp" : "pelunasan_full";
        return true;
    }
</script>

<style>
    .flatpickr-blocked {
        background-color: #e74c3c !important;
        color: white !important;
        border-radius: 50% !important;
    }
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
        border-radius: 10px;
    }
    .form-label {
        font-weight: 500;
    }
    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        border: 1px solid #000 !important;
        background-color: #fff;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #000;
        box-shadow: 0 0 0 0.15rem rgba(0, 0, 0, 0.25);
    }
</style>
@endsection
