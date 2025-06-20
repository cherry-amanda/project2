@extends('layout.v_template3')

@section('content')
<h4>Detail Pesanan</h4>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <tr><th>Tanggal</th><td>{{ $pesanan->event_date }}</td></tr>
    <tr><th>Jam</th><td>{{ $pesanan->start_time }} - {{ $pesanan->end_time }}</td></tr>
    <tr><th>Lokasi</th><td>{{ $pesanan->location }}</td></tr>
    <tr><th>Layanan</th><td>{{ $pesanan->service->nama_layanan ?? '-' }}</td></tr>
    <tr><th>Status</th><td>{{ ucfirst($pesanan->status) }}</td></tr>
</table>

<form method="POST" action="{{ route('vendor.pesanan.status', $pesanan->id) }}">
    @csrf
    <div class="form-group">
        <label>Ubah Status:</label>
        <select name="status" class="form-control">
            <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="disetujui" {{ $pesanan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak" {{ $pesanan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Update</button>
</form>

<a href="{{ route('vendor.pesanan') }}" class="btn btn-secondary mt-2">Kembali</a>
@endsection
