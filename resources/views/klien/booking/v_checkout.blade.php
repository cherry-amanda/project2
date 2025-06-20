@extends('layout.v_template4')  
@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <form id="checkoutForm">
        @csrf
        <div class="row g-4">
            {{-- INFORMASI ACARA --}}
            <div class="col-lg-8">
                <div class="card p-4 shadow-sm mb-4">
                    <h5 class="mb-3">Detail Acara</h5>

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

                    <button type="submit" class="btn btn-primary w-100">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Midtrans & JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const blockedDates = @json($blockedDates);
    const tanggalInput = document.getElementById("tanggal");
    const tanggalHidden = document.getElementById("tanggalHidden");
    const metodePembayaran = document.getElementById("metodePembayaran");
    const bayarSekarangEl = document.getElementById("bayarSekarang");
    const sisaPembayaran = document.getElementById("sisaPembayaran");
    const total = {{ $total }};
    const dp = total * 0.5;

    flatpickr(tanggalInput, {
        dateFormat: "Y-m-d",
        locale: "id",
        minDate: "today",
        disable: blockedDates,
        onChange: function(selectedDates, dateStr) {
            tanggalHidden.value = dateStr;
        }
    });

    metodePembayaran.addEventListener("change", function () {
        if (this.value === "dp") {
            bayarSekarangEl.innerText = "Rp" + dp.toLocaleString("id-ID");
            sisaPembayaran.innerText = "Rp" + dp.toLocaleString("id-ID");
        } else {
            bayarSekarangEl.innerText = "Rp" + total.toLocaleString("id-ID");
            sisaPembayaran.innerText = "Rp0";
        }
    });

    document.getElementById("checkoutForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const metode = metodePembayaran.value;
        document.getElementById("jenisPembayaran").value = metode === "dp" ? "dp" : "full";

        if (!tanggalHidden.value) {
            Swal.fire('Tanggal kosong', 'Silakan pilih tanggal acara.', 'warning');
            return;
        }

        const formData = new FormData(this);

        fetch("{{ route('klien.pembayaran.proses') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function () {
                        window.location.href = data.redirect_success;
                    },
                    onPending: function () {
                        window.location.href = data.redirect_pending;
                    },
                    onError: function () {
                        Swal.fire('Gagal', 'Pembayaran gagal.', 'error');
                    },
                    onClose: function () {
                        Swal.fire('Dibatalkan', 'Anda menutup jendela pembayaran.', 'info');
                    }
                });
            } else {
                Swal.fire('Gagal', data.message || 'Token tidak tersedia.', 'error');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Terjadi kesalahan.', 'error');
        });
    });
</script>

<style>
    .card { border: none; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.08); }
    .form-label { font-weight: 500; }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        border: 1px solid #000 !important;
    }
</style>
@endsection
