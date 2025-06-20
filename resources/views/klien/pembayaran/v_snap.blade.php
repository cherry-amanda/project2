@extends('layout.v_template4')
@section('title', 'Pembayaran')

@section('content')
<div class="container py-5 text-center">
    <h2 class="mb-4">Konfirmasi Pembayaran</h2>

    <p class="lead mb-4">
        Silakan klik tombol di bawah untuk melanjutkan pembayaran kamu via Midtrans.
    </p>

    <button id="pay-button" class="btn btn-primary btn-lg">
        Bayar Sekarang
    </button>

    <p class="text-muted mt-3">
        Jangan tutup atau refresh halaman ini selama proses pembayaran berlangsung.
    </p>
</div>

{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    const payButton = document.getElementById('pay-button');
    const snapToken = "{{ $snapToken }}";

    payButton.addEventListener('click', function () {
        window.snap.pay(snapToken, {
            onSuccess: function(result){
                window.location.href = "{{ route('klien.pembayaran.sukses', $payment->id) }}";
            },
            onPending: function(result){
                window.location.href = "{{ route('klien.pembayaran.pending', $payment->id) }}";
            },
            onError: function(result){
                alert("Pembayaran gagal. Silakan coba lagi.");
                console.error(result);
            },
            onClose: function(){
                alert("Pembayaran dibatalkan.");
            }
        });
    });
</script>
@endsection
