@extends('layout.v_template')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h2 class="mb-0">Kelola Paket</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.package.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Paket
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($packages as $package)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="position-relative" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $package->id }}">
                    @php
                        $firstPhoto = $package->photos->first();
                        $imgSrc = $firstPhoto ? asset('images/foto_paket/' . $firstPhoto->filename) : asset('images/default.jpg');
                    @endphp
                    <img src="{{ $imgSrc }}" class="card-img-top" style="aspect-ratio:16/9; object-fit:cover;" alt="Foto Paket">
                    <div class="position-absolute top-0 end-0 bg-dark text-white px-2 py-1 small">Lihat Foto</div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $package->nama }}</h5>
                    <p class="text-muted" style="min-height: 60px;">{{ Str::limit($package->deskripsi, 100) }}</p>
                    <h6 class="fw-bold">Rp {{ number_format($package->harga_total, 0, ',', '.') }}</h6>

                    @if($package->type === 'paket')
                        <table class="table table-sm mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
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
                        <span class="badge bg-info mt-3">Jasa - Tanpa RAB</span>
                    @endif

                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="{{ route('admin.package.show', $package->id) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.package.edit', $package->id) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.package.destroy', $package->id) }}" class="d-inline form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL FOTO SLIDER --}}
        <div class="modal fade" id="modalFoto{{ $package->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark">
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
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Konfirmasi hapus
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus paket ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    });
</script>
@endsection
