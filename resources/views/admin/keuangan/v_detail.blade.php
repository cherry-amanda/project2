@extends('layout.v_template')
@include('layout.v_nav')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4 text-secondary">
        <i class="bi bi-file-earmark-text-fill me-1"></i> Detail Transaksi Keuangan
    </h4>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-borderless mb-4">
                <tr>
                    <th class="w-25 text-muted">Jenis</th>
                    <td>
                        <span class="badge {{ $data->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($data->jenis) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Kategori</th>
                    <td>{{ $data->kategori }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Nominal</th>
                    <td class="fw-semibold text-dark">Rp {{ number_format($data->nominal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Tanggal</th>
                    <td>{{ $data->tanggal }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Keterangan</th>
                    <td>{{ $data->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Bukti Pembayaran</th>
                    <td>
                        @if($data->bukti)
                            @php $ext = pathinfo($data->bukti, PATHINFO_EXTENSION); @endphp

                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                <img src="{{ asset($data->bukti) }}" alt="Bukti Pembayaran"
                                    class="img-fluid rounded shadow-sm border" style="max-width: 400px;">
                            @elseif(strtolower($ext) === 'pdf')
                                <embed src="{{ asset($data->bukti) }}" type="application/pdf"
                                    width="100%" height="500px" class="rounded border">
                            @else
                                <a href="{{ asset($data->bukti) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    Lihat Bukti
                                </a>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Tidak Ada</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Relasi Pembayaran</th>
                    <td>
                        @if($data->payment)
                            <div class="mb-1">
                                <strong>Booking:</strong> {{ $data->payment->booking->nama_pasangan ?? '-' }}
                            </div>
                            <div class="mb-1">
                                <strong>Jenis:</strong> {{ ucfirst($data->payment->jenis) }}
                            </div>
                            <div>
                                <strong>Jumlah:</strong> Rp {{ number_format($data->payment->jumlah, 0, ',', '.') }}
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            </table>

            <a href="{{ route('admin.keuangan.index') }}" class="btn btn-outline-secondary mt-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
