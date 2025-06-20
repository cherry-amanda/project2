@extends('layout.v_template3')

@section('content')
<h4>Daftar Pesanan Masuk</h4>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Lokasi</th>
            <th>Layanan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->event_date }}</td>
            <td>{{ $item->start_time }} - {{ $item->end_time }}</td>
            <td>{{ $item->location }}</td>
            <td>{{ $item->service->nama_layanan ?? '-' }}</td>
            <td>
                @php
                    $badge = match($item->status) {
                        'pending' => 'secondary',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'primary',
                        default => 'dark'
                    };
                @endphp
                <span class="badge bg-{{ $badge }}">{{ ucfirst($item->status) }}</span>
            </td>
            <td>
                <a href="{{ route('vendor.pesanan.detail', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Belum ada pesanan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
