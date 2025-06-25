@extends('layout.v_template') 
@include('layout.v_nav')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4 text-muted">Daftar Pesanan & Pembayaran Klien</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="bg-light text-dark fw-semibold">
                        <tr class="align-middle small text-uppercase">
                            <th>No</th>
                            <th>Nama Pasangan</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->booking->nama_pasangan ?? '-' }}</td>
                            <td>{{ $p->created_at->format('d/m/Y') }}</td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($p->jenis) }}</span></td>
                            <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge 
                                    {{ $p->status === 'berhasil' ? 'bg-success' : 
                                       ($p->status === 'menunggu_verifikasi_admin' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.pesanan.show', $p->id) }}" class="btn btn-outline-primary btn-sm">
                                        Detail
                                    </a>
                                    @if(in_array($p->status, ['pending', 'menunggu_verifikasi_admin']))
                                    <form action="{{ route('admin.pesanan.konfirmasi', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin konfirmasi pembayaran ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            Konfirmasi
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-muted py-3">Belum ada data pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
