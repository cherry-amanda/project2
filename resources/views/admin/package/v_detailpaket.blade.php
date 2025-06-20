@extends('layout.v_template')

@section('content')
<div class="container">
    <h1>Detail Paket</h1>
    <div class="card">
        <div class="card-body">
            <h4>{{ $package->nama }}</h4>
            <p>{{ $package->deskripsi }}</p>
            <p>Tipe: <strong>{{ ucfirst($package->type) }}</strong></p>
            <p>Total Harga: Rp {{ number_format($package->harga_total, 0, ',', '.') }}</p>

            @if($package->foto)
                <img src="{{ asset('images/foto_paket/' . $package->foto) }}" width="300">
            @endif

            @if($package->type == 'paket')
                <hr>
                <h5>Detail RAB</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th>Harga</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($package->packageRabs as $rab)
                            <tr>
                                <td>{{ $rab->vendorService->nama_item ?? '-' }}</td>
                                <td>Rp {{ number_format($rab->harga_item, 0, ',', '.') }}</td>
                                <td>{{ $rab->deskripsi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
