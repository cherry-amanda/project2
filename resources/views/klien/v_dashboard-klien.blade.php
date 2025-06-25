@extends('layout.v_template4')

@section('content')
<div class="container mt-4">
    <h2>Halo, {{ $pengguna->nama }} 👋</h2>
    <p>Selamat datang di dashboard persiapan pernikahanmu!</p>

    {{-- NOTIFIKASI PEMBAYARAN CASH --}}
    @if($cashPayment)
        @php
            $deadline = \Carbon\Carbon::parse($cashPayment->created_at)->addDay();
            $now = \Carbon\Carbon::now();
            $diffInHours = $now->diffInHours($deadline, false); // negatif kalau lewat
            $isWarning = $diffInHours <= 6 && $diffInHours > 0;
            $isExpired = $diffInHours <= 0;
        @endphp

        <div class="p-3 rounded-4 mt-4 text-center shadow-sm"
             style="background: {{ $isExpired ? '#ffe3e3' : ($isWarning ? '#fff8e1' : '#f0f4f8') }}; border: 1px solid #ddd;">
            @if($isExpired)
                <strong class="text-danger">⚠️ Waktu Pembayaran Cash Telah Melewati Batas</strong>
                <p class="mb-0">Silakan hubungi admin untuk konfirmasi atau lakukan pemesanan ulang.</p>
            @else
                <strong class="{{ $isWarning ? 'text-warning' : 'text-dark' }}">
                    💵 Menunggu Verifikasi Pembayaran Cash
                </strong><br>
                <p class="mb-0">Harap selesaikan pembayaran sebelum:</p>
                <div class="fw-semibold text-primary">
                    {{ $deadline->translatedFormat('l, d F Y H:i') }} WIB
                </div>
                <p class="text-muted small mt-2 mb-0" id="cashCountdownText"></p>
            @endif
        </div>
    @endif

    {{-- COUNTDOWN MENUJU HARI-H --}}
    @if($event)
        @if($countdown)
        <div class="text-center my-5">
            <h4 class="fw-semibold mb-3">⏳ Menuju Hari-H</h4>
            <div id="countdown" class="d-flex justify-content-center gap-3 flex-wrap">
                <div class="count-box shadow-sm">
                    <div id="days" class="count-number">0</div>
                    <div class="count-label">Hari</div>
                </div>
                <div class="count-box shadow-sm">
                    <div id="hours" class="count-number">0</div>
                    <div class="count-label">Jam</div>
                </div>
                <div class="count-box shadow-sm">
                    <div id="minutes" class="count-number">0</div>
                    <div class="count-label">Menit</div>
                </div>
                <div class="count-box shadow-sm">
                    <div id="seconds" class="count-number">0</div>
                    <div class="count-label">Detik</div>
                </div>
            </div>
            <p class="mt-3 text-muted">{{ $countdown['tanggal_formatted'] ?? '' }}</p>
        </div>
        @else
        <div class="alert alert-warning text-center mt-4">
            Acara sudah lewat atau belum ditentukan.
        </div>
        @endif

        {{-- INFO ACARA --}}
        <div class="card p-3 mt-4">
            <h5>💍 Pernikahan: {{ $event->booking->nama_pasangan ?? '-' }}</h5>
            <p>📍 Lokasi: {{ $event->location }}</p>
            <p>📅 Tanggal: {{ $countdown['tanggal_formatted'] ?? '-' }}</p>
        </div>

        {{-- JADWAL --}}
        <div class="card mt-4 p-3">
            <h5>📋 Jadwal Kegiatan</h5>
            @if($event->details->count())
                <table class="table mt-2">
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Aktivitas</th>
                            <th>Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->details->sortBy('time') as $detail)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($detail->time)->format('H:i') }}</td>
                                <td>{{ $detail->activity->nama ?? '-' }}</td>
                                <td>{{ $detail->staff->nama ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Belum ada kegiatan yang ditampilkan oleh admin.</p>
            @endif
        </div>
    @else
        <p class="mt-3">Belum ada event yang ditampilkan oleh admin untuk Anda.</p>
    @endif
</div>

{{-- STYLE --}}
<style>
    .count-box {
        background: #f6f8fa;
        border-radius: 16px;
        padding: 20px 16px;
        width: 90px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .count-number {
        font-size: 32px;
        font-weight: bold;
        color: #333;
    }

    .count-label {
        font-size: 14px;
        color: #777;
        margin-top: 4px;
    }

    #countdown .count-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>

{{-- SCRIPT COUNTDOWN --}}
<script>
    const eventTime = new Date("{{ $event_datetime }}").getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = eventTime - now;

        if (distance <= 0) {
            document.getElementById("countdown").innerHTML = "<h5>Hari-H telah tiba 🎉</h5>";
            clearInterval(interval);
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
        const minutes = Math.floor((distance / (1000 * 60)) % 60);
        const seconds = Math.floor((distance / 1000) % 60);

        document.getElementById("days").innerText = days;
        document.getElementById("hours").innerText = hours;
        document.getElementById("minutes").innerText = minutes;
        document.getElementById("seconds").innerText = seconds;
    }

    const interval = setInterval(updateCountdown, 1000);
    updateCountdown();
</script>

@if($cashPayment && !$isExpired)
<script>
    const cashDeadline = new Date("{{ $deadline->toIso8601String() }}").getTime();
    const cashCountdownText = document.getElementById("cashCountdownText");

    function updateCashCountdown() {
        const now = new Date().getTime();
        const distance = cashDeadline - now;

        if (distance <= 0) {
            cashCountdownText.innerHTML = "⏰ Waktu pembayaran telah habis.";
            clearInterval(cashCountdownInterval);
            return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        let alertMsg = "";
        if (hours < 6) {
            alertMsg = `<br><span class='text-danger fw-bold'>⚠️ Sisa waktu kurang dari ${hours} jam</span>`;
        }

        cashCountdownText.innerHTML = `Sisa waktu: ${hours} jam ${minutes} menit ${seconds} detik.` + alertMsg;
    }

    const cashCountdownInterval = setInterval(updateCashCountdown, 1000);
    updateCashCountdown();
</script>
@endif
@endsection
