@extends('layout.v_template3')

@section('title', 'Daftar Layanan Vendor')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .btn-custom {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        border-radius: 8px;
        padding: 6px 12px;
    }

    .btn-icon-only {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 16px;
    }

    .img-thumbnail {
        border-radius: 8px;
        object-fit: cover;
    }

    .table td, .table th {
        vertical-align: middle !important;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2 class="animate__animated animate__fadeInDown">Daftar Layanan Vendor</h2>

    @if(session('success'))
        <div class="alert alert-success animate__animated animate__fadeInUp">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger animate__animated animate__fadeInUp">{{ session('error') }}</div>
    @endif

    <a href="{{ route('vendor.service.create') }}" class="btn btn-success btn-custom mb-3 animate__animated animate__fadeInUp">
        <i class="bi bi-plus-circle"></i> Tambah Layanan
    </a>

    @if($services->count())
    <div class="table-responsive animate__animated animate__fadeInUp">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Item</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $index => $service)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $service->nama_item }}</td>
                    <td>{{ $service->deskripsi ?? '-' }}</td>
                    <td>Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
                    <td>{{ $service->kategori ?? '-' }}</td>
                    <td>
                        @if($service->foto && file_exists(public_path('images/vendorservices/' . $service->foto)))
                            <img src="{{ asset('images/vendorservices/' . $service->foto) }}" width="80" class="img-thumbnail">
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td>
                        @if($service->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($service->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="{{ route('vendor.service.show', $service->id) }}" class="btn btn-info text-white btn-icon-only" title="Detail">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('vendor.service.edit', $service->id) }}" class="btn btn-warning text-white btn-icon-only" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('vendor.service.destroy', $service->id) }}" method="POST" class="d-inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-icon-only btn-delete" title="Hapus"
                                    data-nama="{{ $service->nama_item }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-muted">Belum ada layanan vendor yang ditambahkan.</p>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const namaItem = this.dataset.nama || 'item ini';

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: `Layanan "${namaItem}" akan dihapus secara permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Iya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif
@endpush



