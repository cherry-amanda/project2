@extends('layout.v_template')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold text-dark">Kelola Kegiatan</h4>
        <a href="{{ route('admin.event.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">+ Tambah Kegiatan</a>
    </div>

    <div class="row">
        @forelse($events as $event)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff, #f7f5f5);">
                <div class="card-body p-4">
                    {{-- Info Utama --}}
                    <div class="mb-3">
                        <div class="text-muted small mb-1">
                            <i class="bi bi-calendar-heart me-1 text-danger"></i>
                            <strong>{{ \Carbon\Carbon::parse($event->booking->tanggal)->translatedFormat('l, d F Y') }}</strong>
                        </div>
                        <div class="text-dark fw-semibold">{{ $event->booking->nama_pasangan ?? '-' }}</div>
                        <div class="text-muted small"><i class="bi bi-geo-alt-fill me-1 text-success"></i>{{ $event->location }}</div>
                    </div>

                    {{-- Jadwal --}}
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-borderless table-hover align-middle mb-0">
                            <thead class="text-muted small">
                                <tr>
                                    <th>Jam</th>
                                    <th>Aktivitas</th>
                                    <th>Staff</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($event->details->sortBy('time') as $detail)
                                <tr>
                                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($detail->time)->format('H:i') }}</td>
                                    <td class="text-nowrap">{{ $detail->activity->nama ?? '-' }}</td>
                                    <td class="text-nowrap">{{ $detail->staff->nama ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted small">Belum ada jadwal.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Switch Publikasi --}}
                    <div class="form-check form-switch small mb-3">
                        <input class="form-check-input" type="checkbox" id="switch{{ $event->id }}"
                            onchange="togglePublish({{ $event->id }})"
                            {{ $event->is_published ? 'checked' : '' }}>
                        <label class="form-check-label" for="switch{{ $event->id }}">
                            Tampilkan ke Klien
                        </label>
                    </div>

                    {{-- Status --}}
                    @php
                        $status = $event->status ?? ($event->is_published ? 'published' : 'draft');
                        $badgeColor = match ($status) {
                            'pending' => 'warning',
                            'approved' => 'primary',
                            'rejected' => 'danger',
                            'published' => 'success',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $badgeColor }} px-3 py-1 rounded-pill text-uppercase small">
                        {{ ucfirst($status) }}
                    </span>

                    {{-- Tombol Aksi --}}
                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.event.show', $event->id) }}" class="btn btn-outline-info btn-sm rounded-pill">Detail</a>
                        <a href="{{ route('admin.event.edit', $event->id) }}" class="btn btn-outline-warning btn-sm rounded-pill">Edit</a>
                        <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm rounded-pill">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">Belum ada kegiatan yang ditambahkan.</div>
        </div>
        @endforelse
    </div>
</div>

<script>
    function togglePublish(eventId) {
        fetch(`/admin/event/${eventId}/toggle-publish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert("Gagal memperbarui status.");
            }
        })
        .catch(() => alert("Terjadi kesalahan."));
    }
</script>
@endsection
