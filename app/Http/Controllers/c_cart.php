<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class c_cart extends Controller
{
    // Tampilkan daftar paket
    public function index(Request $request)
    {
        $packages = Package::query();

        if ($request->filled('min_harga')) {
            $packages->where('harga_total', '>=', $request->min_harga);
        }

        if ($request->filled('max_harga')) {
            $packages->where('harga_total', '<=', $request->max_harga);
        }

        if ($request->filled('type')) {
            $packages->whereIn('type', $request->type);
        }

        $packages = $packages->get();
        return view('klien.booking.v_index', compact('packages'));
    }

    // Tambah ke keranjang
    public function addToCart(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += 1;
        } else {
            $cart[$id] = [
                'package_id' => $package->id,
                'qty' => 1,
                'nama' => $package->nama,
                'harga_total' => $package->harga_total,
                'foto' => $package->foto,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('klien.cart')->with('success', 'Paket berhasil ditambahkan ke keranjang.');
    }

    // Hapus dari keranjang
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->route('klien.cart')->with('success', 'Paket dihapus dari keranjang.');
        }

        return redirect()->route('klien.cart')->with('error', 'Paket tidak ditemukan dalam keranjang.');
    }

    // Tampilkan isi keranjang
    public function cart()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];

        foreach ($cart as $packageId => $data) {
            $package = Package::find($packageId);
            if ($package) {
                $cartItems[] = (object)[
                    'id' => $packageId,
                    'qty' => $data['qty'],
                    'package' => $package,
                ];
            }
        }

        return view('klien.booking.v_cart', compact('cartItems'));
    }

    // Proses ke halaman checkout (dari keranjang)
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        $cartItems = [];

        if (!$request->has('selected')) {
            return redirect()->route('klien.cart')->with('error', 'Pilih minimal satu paket untuk checkout.');
        }

        $selectedIds = $request->input('selected', []);
        $quantities = $request->input('quantities', []);

        foreach ($selectedIds as $packageId) {
            if (!isset($cart[$packageId])) continue;

            $package = Package::find($packageId);
            if ($package) {
                $qty = isset($quantities[$packageId]) ? (int)$quantities[$packageId] : 1;

                $cartItems[] = (object)[
                    'id' => $packageId,
                    'qty' => $qty,
                    'package' => $package,
                    'total_harga' => $package->harga_total * $qty,
                ];
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('klien.cart')->with('error', 'Tidak ada paket yang valid untuk checkout.');
        }

        $blockedDates = Booking::pluck('tanggal')->toArray();
        $pengguna = Auth::user();

        return view('klien.booking.v_checkout', compact('cartItems', 'blockedDates', 'pengguna'));
    }

    // Checkout langsung dari tombol (bukan keranjang)
    public function checkoutNow($id)
    {
        $package = Package::findOrFail($id);

        $cartItems = [
            (object)[
                'id' => $package->id,
                'qty' => 1,
                'package' => $package,
                'total_harga' => $package->harga_total,
            ]
        ];

        $blockedDates = Booking::pluck('tanggal')->toArray();
        $pengguna = Auth::user();

        return view('klien.booking.v_checkout', compact('cartItems', 'blockedDates', 'pengguna'));
    }
}
