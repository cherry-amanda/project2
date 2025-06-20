@extends('layout.v_template')
@section('content')

<h4 class="mb-4">Tambah Kegiatan</h4>
<form action="{{ route('admin.event.store') }}" method="POST">
    @csrf

    {{-- Booking --}}
    <div class="card mb-4 p-3 shadow-sm">
        <h6 class="mb-3">Pilih Booking</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Pasangan</label>
                <select name="booking_id" id="booking_id" class="form-control" onchange="isiBooking()" required>
                    <option value="">-- Pilih --</option>
                    @foreach($bookings as $b)
                    <option value="{{ $b->id }}"
                        data-akad="{{ $b->alamat_akad }}"
                        data-resepsi="{{ $b->alamat_resepsi }}"
                        data-tanggal="{{ $b->tanggal }}">
                        {{ $b->nama_pasangan }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Acara</label>
                <input type="text" id="tanggal_text" class="form-control" readonly>
            </div>
        </div>
    </div>

    {{-- Lokasi --}}
    <div class="card mb-4 p-3 shadow-sm">
        <h6 class="mb-3">Lokasi Kegiatan</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Pilih Lokasi</label>
                <select name="location" class="form-control" id="lokasi_option" required>
                    <option value="">-- Pilih Lokasi --</option>
                    <option value="" id="opt-akad">Alamat Akad</option>
                    <option value="" id="opt-resepsi">Alamat Resepsi</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat Dipilih</label>
                <input type="text" id="lokasi_preview" class="form-control bg-light" readonly>
            </div>
        </div>
    </div>

    {{-- Detail Kegiatan --}}
    <div class="card mb-4 p-3 shadow-sm">
        <h6 class="mb-3">Detail Kegiatan</h6>
        <div id="detailContainer">
            <div class="row g-3 kegiatan-item mb-3">
                <div class="col-md-3">
                    <label>Waktu</label>
                    <input type="time" name="time[]" class="form-control" required />
                </div>
                <div class="col-md-4">
                    <label>Aktivitas</label>
                    <select name="activity_id[]" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($activities as $a)
                        <option value="{{ $a->id }}">{{ $a->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Staff</label>
                    <select name="staff_id[]" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($staffs as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-kegiatan">×</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary" onclick="tambahKegiatan()">+ Tambah Kegiatan</button>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

{{-- Script --}}
<script>
    function isiBooking() {
        const selected = document.getElementById('booking_id').selectedOptions[0];
        const akad = selected.getAttribute('data-akad') || '';
        const resepsi = selected.getAttribute('data-resepsi') || '';
        const tanggal = selected.getAttribute('data-tanggal') || '';
        const pasangan = selected.textContent;

        document.getElementById('tanggal_text').value = tanggal;

        document.getElementById('opt-akad').value = akad;
        document.getElementById('opt-akad').innerText = "Alamat Akad - " + akad;

        document.getElementById('opt-resepsi').value = resepsi;
        document.getElementById('opt-resepsi').innerText = "Alamat Resepsi - " + resepsi;
    }

    document.getElementById('lokasi_option').addEventListener('change', function () {
        const alamat = this.value || '-';
        document.getElementById('lokasi_preview').value = alamat;
    });

    function tambahKegiatan() {
        const container = document.getElementById('detailContainer');
        const item = document.querySelector('.kegiatan-item');
        const clone = item.cloneNode(true);
        clone.querySelectorAll('input, select').forEach(el => el.value = '');
        container.appendChild(clone);
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-kegiatan')) {
            const allItems = document.querySelectorAll('.kegiatan-item');
            if (allItems.length > 1) {
                e.target.closest('.kegiatan-item').remove();
            } else {
                alert('Minimal satu kegiatan harus ada.');
            }
        }
    });
</script>

{{-- Styling --}}
<style>
    .form-label {
        font-weight: 600;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        border: 1px solid #ced4da;
    }

    .card h6 {
        font-weight: 600;
        border-bottom: 1px solid #ddd;
        padding-bottom: 8px;
        margin-bottom: 1rem;
    }
</style>

@endsection
