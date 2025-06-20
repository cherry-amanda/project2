@extends('layout.v_template')
@include('layout.v_nav')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3">Daftar Pembayaran Klien</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
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
                    @foreach($data as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->booking->nama_pasangan ?? '-' }}</td>
                            <td>{{ $p->created_at->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($p->jenis) }}</td>
                            <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge 
                                    {{ $p->status == 'berhasil' ? 'bg-success' : 
                                        ($p->status == 'menunggu_verifikasi_admin' ? 'bg-warning' : 'bg-secondary') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pesanan.show', $p->id) }}" class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
