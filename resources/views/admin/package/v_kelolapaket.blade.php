@extends('layout.v_template')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h2 class="mb-0">Kelola Paket</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.package.create') }}" class="btn btn-success shadow-sm rounded px-3">
                <i class="bi bi-plus-circle me-1"></i> Tambah Paket
            </a>
            <a href="{{ route('admin.vendorservices.approved') }}" class="btn btn-outline-dark shadow-sm rounded px-3">
                <i class="bi bi-box-seam me-1"></i> Produk Vendor Disetujui
            </a>
        </div>
    </div>

    <div class="row">
    @foreach($packages as $package)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="position-relative" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $package->id }}" style="cursor:pointer;">
                @php
                    $firstPhoto = $package->photos->first();
                    $imgSrc = $firstPhoto ? asset('images/foto_paket/' . $firstPhoto->filename) : asset('images/default.jpg');
                @endphp
                <img src="{{ $imgSrc }}" class="card-img-top" style="aspect-ratio:16/9; object-fit:cover;" alt="Foto Paket">
                <span class="badge bg-dark position-absolute top-0 end-0 m-2 rounded-pill px-3 py-1 small shadow">
                    <i class="bi bi-images me-1"></i> Lihat Foto
                </span>
            </div>

            <div class="card-body">
                <h5 class="card-title">{{ $package->nama }}</h5>
                <p class="text-muted small">{{ Str::limit($package->deskripsi, 100) }}</p>
                <h6 class="fw-bold text-success mb-3">Rp {{ number_format($package->harga_total, 0, ',', '.') }}</h6>

                @if($package->type === 'paket' && $package->packageRabs->count())
                    <h6 class="text-secondary fw-semibold">RAB Ringkas:</h6>
                    <ul class="list-group list-group-flush mb-2">
                        @foreach($package->packageRabs->take(2) as $rab)
                            <li class="list-group-item small">
                                <strong>{{ $rab->vendorService->nama_item ?? '-' }}</strong> - Rp {{ number_format($rab->harga_item, 0, ',', '.') }}
                            </li>
                        @endforeach
                        @if($package->packageRabs->count() > 2)
                            <li class="list-group-item text-center small text-muted">
                                +{{ $package->packageRabs->count() - 2 }} item lainnya...
                            </li>
                        @endif
                    </ul>
                @else
                    <span class="badge bg-info text-dark rounded-pill">Jasa Tunggal</span>
                @endif

                <div class="d-flex justify-content-between mt-3 gap-1">
                    <a href="{{ route('admin.package.show', $package->id) }}" class="btn btn-primary btn-sm w-100" title="Detail">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    <a href="{{ route('admin.package.edit', $package->id) }}" class="btn btn-warning btn-sm w-100" title="Edit">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.package.destroy', $package->id) }}" class="w-100 form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100" title="Hapus">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Foto --}}
    <div class="modal fade" id="modalFoto{{ $package->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-body p-0">
                    <div id="carouselFoto{{ $package->id }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($package->photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('images/foto_paket/' . $photo->filename) }}" class="d-block w-100" style="object-fit:contain; max-height:80vh;">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselFoto{{ $package->id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselFoto{{ $package->id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        icon: 'success',
        title: '{{ session("success") }}',
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });
</script>
@endif

<script>
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus paket ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    });
</script>
@endsection
