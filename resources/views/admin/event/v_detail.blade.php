@extends('layout.v_template')

@section('content')
<div class="container">
    <h2 class="mb-4">Detail Kegiatan</h2>

    <table class="table table-bordered">
        <tr>
            <th width="30%">Booking</th>
            <td>{{ $event->booking->nama_pasangan ?? '-' }} ({{ $event->booking->tanggal ?? '-' }})</td>
        </tr>
        <tr>
            <th>Lokasi</th>
            <td>{{ $event->location }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @php
                    $status = $event->status;
                    $badgeColor = [
                        'pending' => 'warning',
                        'approved' => 'primary',
                        'rejected' => 'danger'
                    ][$status] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $badgeColor }}">{{ ucfirst($status) }}</span>
            </td>
        </tr>
    </table>

    <h5 class="mt-4 mb-3">Daftar Item Kegiatan</h5>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="25%">Item</th>
                    <th width="15%">Jam</th>
                    <th width="20%">Penanggung Jawab</th>
                    <th width="20%">Peran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($event->details as $d)
                <tr>
                    <td>{{ $d->activity->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->time)->format('H:i') }}</td>
                    <td>{{ $d->staff->nama ?? '-' }}</td>
                    <td>{{ $d->role ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada jadwal ditambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
