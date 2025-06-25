@extends('layout.v_template4')

@section('title', 'Bayar Sekarang')

@section('content')
<div class="container py-5 text-center">
    <h3>Menyiapkan Pembayaran...</h3>
    <p>Jika jendela pembayaran tidak muncul, silakan refresh halaman atau coba lagi.</p>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ("{{ $snap_token }}") {
            snap.pay("{{ $snap_token }}", {
                onSuccess: function(result){
                    window.location.href = "{{ $redirect_success }}";
                },
                onPending: function(result){
                    window.location.href = "{{ $redirect_pending }}";
                },
                onError: function(result){
                    alert("Pembayaran gagal. Silakan coba lagi.");
                    window.location.href = "{{ $redirect_pending }}";
                },
                onClose: function(){
                    alert("Pembayaran dibatalkan.");
                    window.location.href = "{{ $redirect_pending }}";
                },
            });
        } else {
            alert("Snap Token tidak ditemukan. Tidak dapat melanjutkan pembayaran.");
            window.location.href = "{{ route('klien.pembayaran.list') }}";
        }
    });
</script>
@endsection