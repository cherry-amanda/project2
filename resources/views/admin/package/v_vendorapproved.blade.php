@extends('layout.v_template')

@section('title', 'Layanan Vendor Disetujui')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Daftar Layanan Vendor yang Disetujui</h4>

    @if($services->isEmpty())
        <div class="alert alert-warning">Belum ada layanan vendor yang disetujui.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Vendor</th>
                        <th>Nama Item</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $i => $service)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $service->vendor->pengguna->nama ?? '-' }}</td>
                            <td>{{ $service->nama_item }}</td>
                            <td>{{ $service->kategori }}</td>
                            <td>Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
                            <td>
                                @if($service->foto && file_exists(public_path('images/vendorservices/' . $service->foto)))
                                    <img src="{{ asset('images/vendorservices/' . $service->foto) }}" width="70" class="img-thumbnail">
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td><span class="badge bg-success">Disetujui</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
