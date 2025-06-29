@extends('layout.v_template')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Edit Paket</h4>

    <form action="{{ route('admin.package.update', $package->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="type" class="form-label">Tipe Paket</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="paket" {{ $package->type == 'paket' ? 'selected' : '' }}>Paket</option>
                <option value="jasa" {{ $package->type == 'jasa' ? 'selected' : '' }}>Jasa</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Paket</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $package->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="harga_total" class="form-label">Harga Total</label>
            <input type="number" name="harga_total" class="form-control" value="{{ old('harga_total', $package->harga_total) }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $package->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="foto[]" class="form-label">Upload Foto Baru (bisa lebih dari 1)</label>
            <input type="file" name="foto[]" class="form-control" accept="image/*" multiple>
        </div>

        @if($package->photos->count())
        <div class="mb-3">
            <label class="form-label">Foto Sekarang:</label>
            <div class="row">
                @foreach($package->photos as $photo)
                <div class="col-md-3 mb-2 position-relative">
                    <img src="{{ asset('images/foto_paket/' . $photo->filename) }}" class="img-thumbnail" style="height: 150px; object-fit: cover;">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- RAB SECTION --}}
        <div id="rab-section" style="{{ $package->type == 'paket' ? '' : 'display:none' }}">
            <h5>Detail RAB</h5>
            <table class="table table-bordered" id="rabTable">
                <thead>
                    <tr>
                        <th>Item Vendor</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($package->packageRabs as $rab)
                    <tr>
                        <td>
                            <input type="hidden" name="packageRabs[id][]" value="{{ $rab->id }}">
                            <select name="packageRabs[vendor_service_id][]" class="form-select" required>
                                <option value="">-- Pilih Item --</option>
                                @foreach($vendorServices as $service)
                                    <option value="{{ $service->id }}" {{ $rab->vendor_service_id == $service->id ? 'selected' : '' }}>
                                        {{ $service->nama_item }} ({{ $service->vendor->kategori ?? '-' }}) - {{ $service->nama_jasa ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="packageRabs[harga_item][]" class="form-control" value="{{ $rab->harga_item }}" min="0" required>
                        </td>
                        <td>
                            <input type="text" name="packageRabs[deskripsi][]" class="form-control" value="{{ $rab->deskripsi }}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeRabBtn">&times;</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary btn-sm" id="addRabBtn">+ Tambah Item</button>
        </div>

        {{-- Tombol --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('admin.package.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const rabSection = document.getElementById('rab-section');
        const rabTableBody = document.querySelector('#rabTable tbody');
        const addRabBtn = document.getElementById('addRabBtn');

        const vendorServices = @json($vendorServices);

        function toggleRabSection() {
            rabSection.style.display = typeSelect.value === 'paket' ? '' : 'none';
        }

        function addRabRow(selectedId = '', harga = '', deskripsi = '') {
            let row = document.createElement('tr');
            let options = `<option value="">-- Pilih Item --</option>`;
            vendorServices.forEach(service => {
                const selected = selectedId == service.id ? 'selected' : '';
                options += `<option value="${service.id}" ${selected}>${service.nama_item} (${service.vendor?.kategori ?? '-'})</option>`;
            });

            row.innerHTML = `
                <td>
                    <input type="hidden" name="packageRabs[id][]" value="">
                    <select name="packageRabs[vendor_service_id][]" class="form-select">${options}</select>
                </td>
                <td>
                    <input type="number" name="packageRabs[harga_item][]" class="form-control" value="${harga}" min="0">
                </td>
                <td>
                    <input type="text" name="packageRabs[deskripsi][]" class="form-control" value="${deskripsi}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removeRabBtn">&times;</button>
                </td>
            `;

            rabTableBody.appendChild(row);

            row.querySelector('.removeRabBtn').addEventListener('click', () => row.remove());
        }

        addRabBtn.addEventListener('click', () => addRabRow());
        typeSelect.addEventListener('change', toggleRabSection);
        document.querySelectorAll('.removeRabBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                this.closest('tr').remove();
            });
        });

        toggleRabSection();
    });
</script>



<style>
.form-label { font-weight: 600; }
.form-control, .form-select {
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    background-color: #fff;
    border: 1px solid #000;
}
.form-control:focus, .form-select:focus {
    border-color: #000;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    outline: none;
}
.img-thumbnail {
    border-radius: 0.5rem;
    object-fit: cover;
}
</style>
@endsection
