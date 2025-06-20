@extends('layout.v_template')

@section('title', 'Buat Invoice Baru')

@section('content')
<div class="container mt-4">
    <h1>Buat Invoice Baru</h1>
        <form action="{{ route('admin.invoice.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="id_klien" class="form-label">Klien (Opsional)</label>
        <select name="id_klien" id="id_klien" class="form-select">
            <option value="">-- Tanpa Klien --</option>
            @foreach($klien as $k)
                <option value="{{ $k->id_klien }}">{{ $k->pengguna->nama ?? '-' }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="nomor_invoice" class="form-label">Nomor Invoice</label>
        <input type="text" name="nomor_invoice" id="nomor_invoice" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="tanggal_invoice" class="form-label">Tanggal Invoice</label>
        <input type="date" name="tanggal_invoice" value="{{ old('tanggal_invoice', date('Y-m-d')) }}" required>
    </div>

    <div class="mb-3">
        <select name="status" id="status" class="form-select" required>
            <option value="">-- Status --</option>
            <option value="pending">Pending</option>
            <option value="lunas">Lunas</option>
            <option value="batal">Batal</option>
        </select>
    </div>

    <!-- Items -->
    <hr>
    <h5>Item Invoice</h5>

    <div id="items-wrapper">
        <div class="row g-2 mb-2 item-row">
            <div class="col-md-4">
                <input type="text" name="items[0][deskripsi]" class="form-control" placeholder="Deskripsi" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[0][qty]" class="form-control" placeholder="Qty" required min="1">
            </div>
            <div class="col-md-3">
                <input type="number" name="items[0][harga_satuan]" class="form-control" placeholder="Harga Satuan" required min="0" step="0.01">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger remove-item">Hapus</button>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-secondary mb-3" id="add-item">Tambah Item</button>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Simpan Invoice</button>
        <a href="{{ route('admin.invoice.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</form>
   
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


@push('scripts')
<script>
    let index = 1;

    document.getElementById('add-item').addEventListener('click', function() {
        const wrapper = document.getElementById('items-wrapper');
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 item-row';
        row.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="items[${index}][deskripsi]" class="form-control" placeholder="Deskripsi" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${index}][qty]" class="form-control" placeholder="Qty" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[${index}][harga_satuan]" class="form-control" placeholder="Harga Satuan" required>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger remove-item">Hapus</button>
            </div>
        `;
        wrapper.appendChild(row);
        index++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>
@endpush
<style>
    /* Card style not used in this form, so removed */
    .form-label {
        font-weight: 600;
    }
    .form-control,
    .form-select {
        border-radius: 0.5rem;
        border: 1px solid #000 !important;
        padding: 0.5rem 1rem;
        background-color: #fff;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #000;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.25);
        outline: none;
    }
    .img-thumbnail {
        border-radius: 0.5rem;
        object-fit: cover;
    }
    /* Small spacing for buttons */
    #add-rab {
        margin-bottom: 1rem;
    }
</style>
@endsection
