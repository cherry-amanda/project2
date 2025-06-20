@if($latestBooking)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Info Booking Terbaru
        </div>
        <div class="card-body">
            <p><strong>Paket:</strong> {{ $latestBooking->package->nama }}</p>
            <p><strong>Total Harga:</strong> Rp{{ number_format($latestBooking->package->harga_total, 0, ',', '.') }}</p>

            @php
                $lastPayment = $latestBooking->payments->sortByDesc('created_at')->first();
            @endphp

            <p><strong>Status Pembayaran:</strong>
                <span class="badge bg-{{ $lastPayment->status === 'berhasil' ? 'success' : ($lastPayment->status === 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($lastPayment->status) }}
                </span>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">
            Riwayat Pembayaran
        </div>
        <div class="card-body">
            @if($latestBooking->payments->count())
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestBooking->payments->sortByDesc('created_at') as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                                <td>{{ strtoupper($payment->jenis) }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'berhasil' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Belum ada pembayaran yang tercatat.</p>
            @endif
        </div>
    </div>
@else
    <div class="alert alert-info">
        Belum ada data booking yang tersedia.
    </div>
@endif
