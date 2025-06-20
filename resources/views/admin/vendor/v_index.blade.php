@extends('layout.v_template')

@section('content')
<div class="container py-4">
    <h3 class="text-center mb-5 fw-bold" style="color: #4B4B4B;">Daftar Vendor Terdaftar</h3>

    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded-3 text-center">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @forelse($vendors as $vendor)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background-color: #fefefe;">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-2" style="color: #2E2E2E;">{{ $vendor->pengguna->nama ?? '-' }}</h5>
                    <span class="badge rounded-pill px-3 py-1 mb-3"
                        style="background-color: {{ $vendor->status == 'aktif' ? '#CDEAC0' : '#E0E0E0' }};
                               color: #4B4B4B; font-size: 0.75rem;">
                        {{ ucfirst($vendor->status) }}
                    </span>

                    <ul class="list-unstyled small text-muted mb-4">
                        <li><i class="bi bi-briefcase-fill me-2 text-secondary"></i>{{ ucfirst($vendor->kategori) }}</li>
                        <li><i class="bi bi-envelope me-2 text-secondary"></i>{{ $vendor->pengguna->email }}</li>
                        <li><i class="bi bi-telephone-fill me-2 text-secondary"></i>{{ $vendor->pengguna->no_hp }}</li>
                    </ul>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.vendor.show', $vendor->id) }}" class="btn btn-sm btn-outline-dark rounded-pill">Detail</a>
                        <a href="{{ route('admin.vendor.services', $vendor->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">Jasa</a>

                        <form action="{{ route('admin.vendor.toggleStatus', $vendor->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="btn btn-sm {{ $vendor->status == 'aktif' ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-pill"
                                onclick="return confirm('Yakin ingin mengubah status vendor ini?')">
                                {{ $vendor->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.vendor.destroy', $vendor->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus vendor ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">Belum ada data vendor yang tersedia.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
