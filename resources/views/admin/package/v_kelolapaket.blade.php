@extends('layout.v_template')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h2 class="mb-0">Kelola Paket</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.package.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Paket
            </a>
            <a href="{{ route('admin.vendorservices.approved') }}" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Vendor Disetujui
            </a>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter --}}
    <form method="GET" id="filterForm" class="mb-4">
        <div class="input-group w-auto">
            <label class="input-group-text" for="filterType"><i class="bi bi-funnel"></i></label>
            <select name="type" id="filterType" class="form-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Jenis Paket</option>
                <option value="paket" {{ request('type') == 'paket' ? 'selected' : '' }}>Paket</option>
                <option value="jasa" {{ request('type') == 'jasa' ? 'selected' : '' }}>Jasa</option>
            </select>
        </div>
    </form>

    {{-- Kartu Paket --}}
    <div class="row" id="packageContainer">
        @foreach($packages as $package)
        <div class="col-md-4 mb-4 package-card" data-type="{{ $package->type }}">
            <div class="card h-100 shadow-sm border-0">
                @php
                    $fotoPath = $package->foto && file_exists(public_path('images/foto_paket/' . $package->foto))
                        ? asset('images/foto_paket/' . $package->foto)
                        : asset('images/default.jpg');
                @endphp

                <div class="img-container" style="aspect-ratio: 16/9; overflow:hidden; background:#f1f1f1;">
                    <img src="{{ $fotoPath }}" alt="Foto Paket"
                         class="img-preview w-100"
                         style="height:100%; object-fit:cover; cursor:pointer;"
                         data-bs-toggle="modal"
                         data-bs-target="#fotoModal"
                         data-src="{{ $fotoPath }}">
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $package->nama }}</h5>
                    <p class="card-text text-muted" style="min-height: 60px;">{{ Str::limit($package->deskripsi, 100) }}</p>
                    <h6 class="fw-bold">Rp {{ number_format($package->harga_total, 0, ',', '.') }}</h6>

                    @if($package->type === 'paket')
                        <table class="table table-sm table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Komponen</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($package->packageRabs as $rab)
                                    <tr>
                                        <td>{{ $rab->vendorService->nama_item ?? '-' }}</td>
                                        <td>Rp {{ number_format($rab->harga_item, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <span class="badge bg-info mt-3">Jasa - Tidak Ada RAB</span>
                    @endif

                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <a href="{{ route('admin.package.show', $package->id) }}" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.package.edit', $package->id) }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" title="Edit Paket">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('admin.package.destroy', $package->id) }}" method="POST" class="form-delete d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Hapus Paket">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Modal Foto Preview --}}
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <img src="" alt="Foto Paket" id="modalImage" class="w-100"
             style="object-fit: contain; max-height: 80vh; background-color: #000;">
      </div>
      <div class="modal-footer p-2">
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    // Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Filter jenis paket
    document.getElementById('filterType').addEventListener('change', function() {
        const selected = this.value;
        const cards = document.querySelectorAll('.package-card');
        cards.forEach(card => {
            card.style.display = !selected || card.dataset.type === selected ? '' : 'none';
        });
    });

    // Modal foto preview
    document.querySelectorAll('.img-preview').forEach(img => {
        img.addEventListener('click', function() {
            document.getElementById('modalImage').src = this.dataset.src;
        });
    });

    // Konfirmasi hapus
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin hapus paket ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Kelola Paket WO</li>
@endsection
