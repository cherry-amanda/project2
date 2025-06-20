@extends('layout.v_template')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-center mb-4" style="color: #3A3A3A;">Jasa Vendor: {{ $vendor->pengguna->nama ?? '-' }}</h3>

    <a href="{{ route('admin.vendor.index') }}" class="btn btn-outline-dark mb-3 rounded-pill">← Kembali ke Data Vendor</a>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    @if($services->count())
    <div class="table-responsive rounded-4 shadow-sm">
        <table class="table table-hover align-middle bg-white rounded-4">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Item</th>
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
                            <img src="{{ asset('images/vendorservices/' . $service->foto) }}" width="80" class="img-thumbnail rounded-3">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusBadge = [
                                'disetujui' => 'success',
                                'ditolak' => 'danger',
                                'pending' => 'secondary',
                            ][$service->status] ?? 'warning';
                        @endphp
                        <span class="badge bg-{{ $statusBadge }}">{{ ucfirst($service->status) }}</span>
                    </td>
                    <td>
                        @if($service->status != 'disetujui')
                        <form action="{{ route('admin.vendor.services.approve', $service->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-success btn-sm rounded-pill"
                                onclick="return confirm('Setujui jasa ini?')">Setujui</button>
                        </form>
                        @endif

                        @if($service->status != 'ditolak')
                        <form action="{{ route('admin.vendor.services.reject', $service->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill"
                                onclick="return confirm('Tolak jasa ini?')">Tolak</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="alert alert-info text-center">Belum ada layanan dari vendor ini.</div>
    @endif
</div>
@endsection
