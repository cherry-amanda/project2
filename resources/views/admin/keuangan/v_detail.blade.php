@extends('layout.v_template')
@include('layout.v_nav')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Detail Transaksi Keuangan</h4>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Jenis</th>
                    <td><span class="badge {{ $data->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($data->jenis) }}</span></td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>{{ $data->kategori }}</td>
                </tr>
                <tr>
                    <th>Nominal</th>
                    <td>Rp {{ number_format($data->nominal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $data->tanggal }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $data->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Bukti</th>
                    <td>
                        @if($data->bukti)
                            <a href="{{ asset('storage/'.$data->bukti) }}" target="_blank">Lihat Bukti</a>
                        @else
                            Tidak Ada
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Relasi Pembayaran</th>
                    <td>
                        @if($data->payment)
                            Booking: {{ $data->payment->booking->nama_pasangan ?? '-' }}<br>
                            Jenis: {{ ucfirst($data->payment->jenis) }}<br>
                            Jumlah: Rp {{ number_format($data->payment->jumlah, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
            <a href="{{ route('admin.keuangan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
