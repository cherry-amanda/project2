@extends('layout.v_template')

@section('content')
<h4 class="mb-4">Edit Kegiatan</h4>
<form action="{{ route('admin.event.update', $event->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Booking --}}
    <div class="card mb-4 p-3 shadow-sm">
        <h6 class="mb-3">Pilih Booking</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Pasangan</label>
                <select name="booking_id" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    @foreach($bookings as $b)
                    <option value="{{ $b->id }}" {{ $event->booking_id == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_pasangan }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Lokasi --}}
    <div class="card mb-4 p-3 shadow-sm">
        <h6 class="mb-3">Lokasi Kegiatan</h6>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">Alamat Lokasi</label>
                <input type="text" name="location" class="form-control" value="{{ $event->location }}" required>
            </div>
        </div>
    </div>

    {{-- Detail Kegiatan --}}
    <div class="card mb-4 p-3 shadow-sm">
        <h6 class="mb-3">Detail Kegiatan</h6>
        <div id="detailContainer">
            @foreach($event->details as $detail)
            <div class="row g-3 kegiatan-item mb-3">
                <div class="col-md-3">
                    <label>Waktu</label>
                    <input type="time" name="time[]" class="form-control" value="{{ $detail->time }}" required>
                </div>
                <div class="col-md-4">
                    <label>Aktivitas</label>
                    <select name="activity_id[]" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($activities as $a)
                        <option value="{{ $a->id }}" {{ $detail->activity_id == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Staff</label>
                    <select name="staff_id[]" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($staffs as $s)
                        <option value="{{ $s->id }}" {{ $detail->staff_id == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-kegiatan">×</button>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-secondary" onclick="tambahKegiatan()">+ Tambah Kegiatan</button>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>

{{-- Script --}}
<script>
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
