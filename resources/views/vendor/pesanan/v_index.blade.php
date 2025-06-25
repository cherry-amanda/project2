@extends('layouts.vendor') {{-- sesuaikan layout kamu --}}

@section('content')
<div class="container mt-4">
    <h4>Daftar Pesanan Saya</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Klien</th>
                <th>Layanan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tugas as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->booking->nama_klien ?? '-' }}</td>
                <td>{{ $item->service->nama_layanan ?? '-' }}</td>
                <td><span class="badge bg-info text-dark">{{ $item->status }}</span></td>
                <td>
                    @if($item->status === 'assigned')
                    <form action="{{ route('vendor.pesanan.update', $item->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="accepted">
                        <button class="btn btn-success btn-sm">Terima</button>
                    </form>

                    <form action="{{ route('vendor.pesanan.update', $item->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button class="btn btn-danger btn-sm">Tolak</button>
                    </form>
                    @elseif($item->status === 'accepted')
                    <form action="{{ route('vendor.pesanan.update', $item->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="done">
                        <button class="btn btn-primary btn-sm">Selesai</button>
                    </form>
                    @else
                    <i>Tidak ada aksi</i>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
