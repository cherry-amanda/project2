@extends('layout.v_template')

@section('content')
<div class="container">
    <h4>Daftar Invoice</h4>
    <a href="{{ route('admin.invoice.create') }}" class="btn btn-primary mb-3">+ Tambah Invoice</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Invoice</th>
                <th>Nama Klien</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice as $i => $inv)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $inv->nomor_invoice }}</td>
                    <td>{{ $inv->klien && $inv->klien->pengguna ? $inv->klien->pengguna->nama : '-' }}</td>
                    <td>{{ $inv->tanggal_invoice }}</td>
                    <td>Rp {{ number_format($inv->total_harga, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($inv->status) }}</td>
                    <td>
                        <a href="{{ route('admin.invoice.pdf', $inv->id_invoice) }}" class="btn btn-sm btn-success" target="_blank">PDF</a>
                        <a href="{{ route('admin.invoice.print', $inv->id_invoice) }}" class="btn btn-sm btn-info" target="_blank">Print</a>

                        <form action="{{ route('admin.invoice.destroy', $inv->id_invoice) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin hapus invoice ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>


                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
