<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Payment;
use App\Models\Pengguna;
use App\Models\Keuangan;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class c_payment extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function list()
    {
        $booking = Booking::with(['payments', 'bookingDetails.vendorService'])
            ->where('pengguna_id', Auth::id())
            ->latest()
            ->first();

        return view('klien.pembayaran.v_list', compact('booking'));
    }

    public function proses(Request $r)
    {
        $order_id = 'ORDER-' . Str::upper(Str::random(10));

        $total = 0;
        if ($r->has('package_id')) {
            foreach ($r->package_id as $pid) {
                $qty = $r->quantities[$pid] ?? 1;
                $package = Package::find($pid);
                if ($package) {
                    $total += $package->harga_total * $qty;
                }
            }
        }

        $amount = ($r->jenis === 'dp') ? $total * 0.5 : $total;

        $booking = Booking::create([
            'pengguna_id'     => auth()->id(),
            'nama_pasangan'   => $r->nama_pasangan,
            'no_ktp'          => $r->no_ktp,
            'no_hp'           => $r->no_hp,
            'tanggal'         => $r->tanggal,
            'alamat_akad'     => $r->alamat_akad,
            'alamat_resepsi'  => $r->alamat_resepsi,
            'status'          => 'pending',
            'total_harga'     => $total,
        ]);

        foreach ($r->package_id as $pid) {
            BookingDetail::create([
                'booking_id'         => $booking->id,
                'vendor_service_id'  => $pid,
                'qty'                => $r->quantities[$pid] ?? 1,
            ]);
        }

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'order_id'   => $order_id,
            'jumlah'     => $amount,
            'status'     => 'pending',
            'jenis'      => $r->jenis === 'dp' ? 'dp' : 'full',
        ]);

        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $r->nama_pasangan,
                'email'      => auth()->user()->email,
                'phone'      => $r->no_hp,
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $payment->snap_token = $snapToken;
            $payment->save();

            session(['order_id' => $order_id]);

            return response()->json([
                'snap_token' => $snapToken,
                'redirect_success' => route('klien.pembayaran.sukses'),
                'redirect_pending' => route('klien.pembayaran.pending'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sukses()
    {
        $order_id = session('order_id');
        $payment = Payment::where('order_id', $order_id)->first();
        if ($payment) {
            $payment->status = 'berhasil';
            $payment->tanggal_bayar = now();
            $payment->save();

            $payment->booking->update(['status' => 'booked']);
        }

        return redirect()->route('klien.pembayaran.list')->with([
            'status' => 'sukses',
            'message' => 'Pembayaran berhasil!'
        ]);
    }

    public function pending()
    {
        $order_id = session('order_id');
        $payment = Payment::where('order_id', $order_id)->first();
        if ($payment) {
            $payment->status = 'pending';
            $payment->save();
        }

        return redirect()->route('klien.pembayaran.list')->with([
            'status' => 'pending',
            'message' => 'Pembayaran masih diproses.'
        ]);
    }
}
