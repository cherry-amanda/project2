<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Keuangan;
use App\Models\Package;
use App\Models\Date;
use App\Models\BookingDetail;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    public function bayar($id)
    {
        $payment = Payment::findOrFail($id);

        if (!$payment->snap_token || !$payment->order_id) {
            // Generate Order ID & Snap Token via Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');
            \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is3ds');

            $order_id = 'ORDER-' . time(); // atau gunakan $payment->id

            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $payment->jumlah,
                ],
                'customer_details' => [
                    'first_name' => $payment->booking->nama_pasangan,
                    'email' => 'test@example.com', // isi jika ada
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $payment->order_id = $order_id;
            $payment->snap_token = $snapToken;
            $payment->save();
        }

        return view('klien.v_bayar', compact('payment'));
    }


    public function proses(Request $request)
    {
        $request->validate([
            'package_id' => 'required|array',
            'package_id.*' => 'exists:packages,id',
            'quantities' => 'required|array',
            'tanggal' => 'required|date',
            'alamat_akad' => 'required|string',
            'alamat_resepsi' => 'required|string',
            'jenis' => 'required|in:dp,pelunasan_full',
        ]);

        $pengguna = Auth::user();
        $totalHarga = 0;

        foreach ($request->package_id as $id) {
            $paket = Package::findOrFail($id);
            $qty = $request->quantities[$id] ?? 1;
            $totalHarga += $paket->harga_total * $qty;
        }

        $booking = Booking::create([
            'pengguna_id' => $pengguna->id,
            'nama_pasangan' => $request->nama_pasangan ?? $pengguna->nama,
            'tanggal' => $request->tanggal,
            'alamat_akad' => $request->alamat_akad,
            'alamat_resepsi' => $request->alamat_resepsi,
            'total_harga' => $totalHarga,
            'status' => 'pending',
        ]);

        Date::updateOrCreate(
            ['tanggal' => $booking->tanggal],
            ['status' => 'booked', 'note' => $booking->nama_pasangan]
        );

        foreach ($request->package_id as $id) {
            $qty = $request->quantities[$id] ?? 1;
            BookingDetail::create([
                'booking_id' => $booking->id,
                'package_id' => $id,
                'qty' => $qty,
                'status' => 'pending',
            ]);
        }

        $jenis = $request->jenis;
        $jumlahBayar = ($jenis == 'dp') ? ($totalHarga * 0.5) : $totalHarga;
        $orderId = 'ORDER-' . strtoupper(uniqid());

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'jumlah' => $jumlahBayar,
            'status' => 'pending',
            'jenis' => $jenis,
            'tanggal_bayar' => now(),
        ]);

        session()->forget('cart');

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $jumlahBayar,
            ],
            'customer_details' => [
                'first_name' => $pengguna->nama,
                'email' => $pengguna->email,
                'phone' => $request->no_hp ?? $pengguna->no_hp,
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $payment->update(['snap_token' => $snapToken]);

            return view('klien.pembayaran.v_snap', compact('snapToken', 'payment', 'booking'));
        } catch (\Exception $e) {
            return redirect()->route('klien.pembayaran.list')
                ->with('error', 'Gagal terhubung ke Midtrans. Silakan konfirmasi pembayaran manual.');
        }
    }

    public function sukses()
    {
        return redirect()->route('klien.pembayaran.list')
            ->with('status', 'sukses')
            ->with('message', 'Terima kasih! Pembayaran Anda sedang diproses.');
    }

    public function midtransCallback(Request $request)
    {
        \Log::info('✅ Webhook masuk:', $request->all()); // Tambahan log
        $notif = new Notification();
        $transaction = strtolower($notif->transaction_status);
        $order_id = $notif->order_id;
        $payment_type = $notif->payment_type;

        $payment = Payment::where('order_id', $order_id)->first();

        if ($payment) {
            $booking = $payment->booking;

            if ($transaction === 'settlement') {
                $payment->status = 'berhasil';

                $sudahAda = Keuangan::where('relasi_id', $payment->id)->exists();
                if (!$sudahAda) {
                    Keuangan::create([
                        'jenis' => 'pemasukan',
                        'kategori' => $payment->jenis,
                        'keterangan' => 'Pembayaran dari ' . ($booking->nama_pasangan ?? 'Klien'),
                        'nominal' => $payment->jumlah,
                        'tanggal' => now(),
                        'relasi_id' => $payment->id,
                    ]);
                }

                if ($payment->jenis === 'dp') {
                    $existingPelunasan = Payment::where('booking_id', $booking->id)
                        ->where('jenis', 'pelunasan')
                        ->exists();

                    if (!$existingPelunasan) {
                        $sisa = $booking->total_harga - $payment->jumlah;
                        $orderIdPelunasan = 'ORDER-' . strtoupper(Str::random(8)) . '-' . time();
                        $snapToken = Snap::getSnapToken([
                            'transaction_details' => [
                                'order_id' => $orderIdPelunasan,
                                'gross_amount' => (int) $sisa,
                            ],
                            'customer_details' => [
                                'first_name' => $booking->pengguna->nama,
                                'email' => $booking->pengguna->email,
                                'phone' => $booking->pengguna->no_hp,
                            ]
                        ]);

                        Payment::create([
                            'booking_id' => $booking->id,
                            'order_id' => $orderIdPelunasan,
                            'snap_token' => $snapToken,
                            'jumlah' => $sisa,
                            'status' => 'pending',
                            'jenis' => 'pelunasan',
                            'tanggal_bayar' => null,
                        ]);
                    }
                }
            } else {
                $payment->status = $transaction;
            }

            $payment->jenis_pembayaran = $payment_type;
            $payment->save();
        }

        return response()->json(['message' => 'Callback diterima'], 200);
    }

    public function pending()
    {
        return redirect()->route('klien.pembayaran.list')
            ->with('status', 'pending')
            ->with('message', 'Pembayaran sedang menunggu konfirmasi.');
    }

    public function gagal()
    {
        return redirect()->route('klien.pembayaran.list')
            ->with('status', 'gagal')
            ->with('message', 'Pembayaran gagal.');
    }

    public function batal()
    {
        return redirect()->route('klien.pembayaran.list')
            ->with('status', 'batal')
            ->with('message', 'Pembayaran dibatalkan.');
    }




    public function pelunasan($payment_id)
    {
        $payment = Payment::with('booking')->findOrFail($payment_id);
        $booking = $payment->booking;
        $pengguna = Auth::user();

        $totalPaid = $booking->payments->where('status', 'berhasil')->sum('jumlah');
        $sisaTagihan = $booking->total_harga - $totalPaid;

        if ($payment->status != 'berhasil') {
            $payment->jumlah = $sisaTagihan;
        }

        if (!$payment->order_id) {
            $payment->order_id = 'ORDER-' . strtoupper(Str::random(8)) . '-' . time();
        }

        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $payment->order_id,
                    'gross_amount' => (int) $payment->jumlah,
                ],
                'customer_details' => [
                    'first_name' => $pengguna->nama,
                    'email' => $pengguna->email,
                    'phone' => $pengguna->no_hp,
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            $payment->snap_token = $snapToken;
            $payment->save(); // <-- disimpan di sini pastikan sudah lengkap

            return view('klien.pembayaran.v_snap', compact('snapToken', 'payment', 'booking'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }


    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $payment = Payment::findOrFail($id);

        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti'), $filename);
            $payment->bukti_bayar = $filename;
            $payment->status = 'menunggu_verifikasi_admin';
            $payment->save();
        }

        return redirect()->back()->with('status', 'pending')->with('message', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

}
