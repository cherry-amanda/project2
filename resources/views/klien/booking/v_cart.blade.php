@extends('layout.v_template4')
@section('title', 'Keranjang')

@section('content')
<style>
    .cart-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }

    .cart-content {
        flex: 1;
        background-color: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .cart-summary {
        width: 320px;
        background-color: #fff;
        padding: 25px 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    .cart-title {
        font-size: 1.7rem;
        font-weight: 600;
        margin-bottom: 30px;
        color: #343a40;
    }

    .cart-empty {
        text-align: center;
        padding: 50px 20px;
        font-size: 1.2rem;
        border: 1px dashed #ccc;
        border-radius: 12px;
        color: #6c757d;
        background-color: #ffffff;
    }

    .btn-checkout {
        width: 100%;
        background-color: #c77dff;
        color: white;
        font-weight: 600;
        border: none;
        padding: 12px;
        border-radius: 8px;
        transition: 0.3s ease;
        margin-top: 25px;
    }

    .btn-checkout:hover {
        background-color: #9d4edd;
    }

    .cart-item {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        align-items: center;
    }

    .cart-item img {
        width: 120px;
        height: 90px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .cart-item-info {
        flex-grow: 1;
    }

    .cart-item-info label {
        font-weight: 600;
        font-size: 1rem;
    }

    .cart-item-info .text-muted {
        font-size: 0.9rem;
    }

    .qty-input {
        width: 70px;
        padding: 4px 8px;
        font-size: 0.9rem;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .cart-summary h6 {
        font-weight: 600;
        margin-bottom: 15px;
    }

    .text-danger.small {
        font-size: 0.85rem;
    }
</style>

<div class="container py-4">
    <h4 class="mb-4 fw-bold text-dark">🛒 Keranjang Paket & Jasa</h4>

    <form action="{{ route('klien.checkout') }}" method="GET" class="cart-wrapper">

        <div class="cart-content">
            <h5 class="cart-title">Paket yang Kamu Pilih</h5>

            @forelse($cartItems as $item)
            <div class="cart-item">
                <img src="{{ asset('images/foto_paket/' . $item->package->foto) }}" class="img-thumbnail">

                <div class="cart-item-info">
                    <div class="form-check">
                        <input class="form-check-input item-check" type="checkbox" name="selected[]" value="{{ $item->id }}">
                        <label class="form-check-label">{{ $item->package->nama }}</label>
                        <span class="badge bg-secondary ms-2">{{ ucfirst($item->package->type) }}</span>
                    </div>
                    <div class="text-muted mt-1">Jumlah: 
                        <input type="number" name="quantities[{{ $item->id }}]" 
                            class="qty-input" data-id="{{ $item->id }}" 
                            value="{{ $item->qty }}" min="1">
                    </div>
                </div>

                <div class="text-end">
                    <span class="harga-item fw-semibold d-block mb-2" data-harga="{{ $item->package->harga_total }}" id="harga-{{ $item->id }}">
                        Rp{{ number_format($item->package->harga_total * $item->qty, 0, ',', '.') }}
                    </span>
                    <a href="{{ route('klien.cart.remove', $item->id) }}" class="text-danger small">Hapus</a>
                </div>
            </div>
            @empty
            <div class="cart-empty">
                Keranjang kamu masih kosong nih...<br>
                <a href="{{ route('klien.booking.index') }}" class="btn btn-outline-primary mt-3">Pilih Paket Sekarang</a>
            </div>
            @endforelse
        </div>

        @if(count($cartItems))
        <div class="cart-summary">
            <h6 class="text-uppercase mb-3">🧾 Ringkasan Belanja</h6>
            
            <div class="d-flex justify-content-between mb-2">
                <span class="text-dark">Dipilih</span>
                <span><strong id="jumlahDipilih">0</strong> item</span>
            </div>

            <div class="d-flex justify-content-between mb-3 border-top pt-2">
                <span class="text-dark">Total</span>
                <span class="fw-bold text-success">Rp<span id="totalHargaDipilih">0</span></span>
            </div>

            <button type="submit" class="btn-checkout">
                Checkout Sekarang
            </button>
        </div>
        @endif
    </form>
</div>

<script>
    const checkboxes = document.querySelectorAll('.item-check');
    const qtyInputs = document.querySelectorAll('.qty-input');
    const totalHargaDipilih = document.getElementById('totalHargaDipilih');
    const jumlahDipilih = document.getElementById('jumlahDipilih');

    function formatRupiah(angka) {
        return angka.toLocaleString('id-ID');
    }

    function hitungTotal() {
        let total = 0;
        let count = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const id = cb.value;
                const qty = parseInt(document.querySelector(`.qty-input[data-id="${id}"]`).value);
                const hargaPerItem = parseInt(document.getElementById(`harga-${id}`).dataset.harga);
                total += hargaPerItem * qty;
                count++;
            }
        });
        totalHargaDipilih.innerText = formatRupiah(total);
        jumlahDipilih.innerText = count;
    }

    qtyInputs.forEach(input => {
        input.addEventListener('input', () => {
            const id = input.dataset.id;
            const qty = input.value;
            const hargaPerItem = parseInt(document.getElementById(`harga-${id}`).dataset.harga);
            const totalHarga = hargaPerItem * qty;
            document.getElementById(`harga-${id}`).innerText = 'Rp' + formatRupiah(totalHarga);
            hitungTotal();
        });
    });

    checkboxes.forEach(cb => cb.addEventListener('change', hitungTotal));
    hitungTotal();
</script>
@endsection
