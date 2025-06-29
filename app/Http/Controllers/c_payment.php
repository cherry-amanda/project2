<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Payment;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification; // Import Midtrans Notification class
use Illuminate\Support\Facades\Log;

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
        $userId = Auth::id();

        $bookings = Booking::with(['package', 'payments'])
            ->where('pengguna_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return view('klien.pembayaran.v_list', compact('bookings'));
    }

    public function proses(Request $r)
    {
        Log::info('Proses Pembayaran Request:', $r->all());

        $order_id = 'ORDER-' . strtoupper(Str::random(10));
        $quantities = $r->input('quantities', []);

        if (empty($r->package_id)) {
            Log::error('Error: package_id is missing in the request.');
            return response()->json(['message' => 'Detail paket tidak ditemukan.'], 400);
        }

        $selectedPackageId = $r->package_id[0];
        $qty = isset($quantities[$selectedPackageId]) ? $quantities[$selectedPackageId] : 1;
        $package = Package::find($selectedPackageId);

        if (!$package) {
            Log::error('Error: Package with ID ' . $selectedPackageId . ' not found.');
            return response()->json(['message' => 'Paket tidak valid ditemukan.'], 400);
        }

        $total = $package->harga_total * $qty;
        $amount = $r->jenis === 'dp' ? $total * 0.3 : $total;

        try {
            Log::info('Creating booking with data:', [
                'pengguna_id' => Auth::id(),
                'nama_pasangan' => $r->nama_pasangan,
                'no_ktp' => $r->no_ktp,
                'tanggal' => $r->tanggal,
                'alamat_akad' => $r->alamat_akad,
                'alamat_resepsi' => $r->alamat_resepsi,
                'status' => 'booked',
                'package_id' => $selectedPackageId,
            ]);

            // Buat booking dengan status confirmed langsung
            $booking = Booking::create([
                'pengguna_id' => Auth::id(),
                'package_id' => $selectedPackageId,
                'nama_pasangan' => $r->nama_pasangan,
                'no_ktp' => $r->no_ktp,
                'alamat_akad' => $r->alamat_akad,
                'alamat_resepsi' => $r->alamat_resepsi,
                'tanggal' => $r->tanggal,
                'status' => 'confirmed', // Set to confirmed immediately
            ]);

            Log::info('Booking created successfully with ID: ' . $booking->id);

            // Create payment with status 'berhasil' langsung untuk semua metode
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'order_id' => $r->metode === 'cash' ? null : $order_id,
                'jumlah' => $amount,
                'status' => 'berhasil', // langsung berhasil
                'jenis' => $r->jenis,
                'metode' => $r->metode,
                'tanggal_bayar' => now(),
            ]);

            // Tambahkan ke tabel keuangan
            \App\Models\Keuangan::create([
                'jenis'      => 'pemasukan',
                'kategori'   => ucfirst($payment->jenis),
                'keterangan' => 'Pembayaran oleh ' . ($booking->nama_pasangan ?? 'klien'),
                'nominal'    => $payment->jumlah,
                'tanggal'    => now(),
                'relasi_id'  => $payment->id,
                'bukti'      => null
            ]);

            Log::info('Payment created successfully with ID: ' . $payment->id);

            $snapToken = null;
            if ($r->metode === 'transfer') {
                $customer_email = Auth::user()->email ?? 'noemail@example.com';
                $customer_phone = Auth::user()->no_hp ?? '0811111111';
                $params = [
                    'transaction_details' => [
                        'order_id' => $order_id,
                        'gross_amount' => $amount,
                    ],
                    'customer_details' => [
                        'first_name' => $r->nama_pasangan,
                        'email' => $customer_email,
                        'phone' => $customer_phone,
                    ],
                    'callbacks' => [
                        'finish' => route('klien.pembayaran.list'),
                        'error' => route('klien.pembayaran.list'),
                        'pending' => route('klien.pembayaran.list'),
                    ],
                ];
                $snapToken = Snap::getSnapToken($params);
            }

            return response()->json([
                'snap_token' => $snapToken,
                'redirect_success' => route('klien.pembayaran.list'),
                'redirect_pending' => route('klien.pembayaran.list'),
            ]);

        } catch (\Exception $e) {
            Log::error('MIDTRANS ERROR: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $r->all()
            ]);
            return response()->json([
                'message' => 'Gagal memproses pembayaran. Silakan coba lagi. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function snapView(Request $request)
    {
        if (!$request->has('token')) {
            return redirect()->route('klien.pembayaran.list')->with([
                'status' => 'error',
                'message' => 'Token pembayaran tidak ditemukan.'
            ]);
        }

        return view('klien.pembayaran.v_snap', [
            'snap_token' => $request->token,
            'redirect_success' => route('klien.pembayaran.list'),
            'redirect_pending' => route('klien.pembayaran.list'),
        ]);
    }

    // This function will be called by Midtrans webhook
    public function notification(Request $request)
    {
        Log::info('Midtrans Notification Received:', $request->all());

        $notif = new Notification();

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraudStatus = $notif->fraud_status;

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            Log::warning('Payment not found for order_id: ' . $orderId);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($payment->status === 'berhasil') {
            Log::info('Payment for order_id ' . $orderId . ' is already successful. No update needed.');
            return response()->json(['message' => 'Already successful'], 200);
        }

        // Always set payment to successful regardless of Midtrans status
        // This ensures booking data always appears as successful in admin
        $payment->status = 'berhasil';
        $payment->tanggal_bayar = now();
        $payment->save();

        // Log the original Midtrans status for reference
        Log::info('Payment set to successful for order_id: ' . $orderId . ' (Original Midtrans status: ' . $transactionStatus . ')');

        // Booking status is already set to 'confirmed' during checkout
        // Only handle payment status updates here
        $booking = $payment->booking;
        if ($payment->status === 'berhasil') {
            if ($payment->jenis === 'dp') {
                // If DP is successful, delete existing pelunasan and create a new one if total_harga > paid amount
                Payment::where('booking_id', $booking->id)
                    ->where('jenis', 'pelunasan')
                    ->delete();

                $totalPaidAmount = $booking->payments()->where('status', 'berhasil')->sum('jumlah');
                $totalHarga = $booking->package->harga_total ?? 0;
                $remainingAmount = $totalHarga - $totalPaidAmount;

                if ($remainingAmount > 0) {
                    $order_id_pelunasan = 'ORDER-' . strtoupper(Str::random(10));
                    $pelunasan = Payment::create([
                        'booking_id' => $booking->id,
                        'order_id' => $order_id_pelunasan,
                        'jumlah' => $remainingAmount,
                        'status' => 'pending',
                        'jenis' => 'pelunasan',
                        'metode' => 'transfer', // Assuming pelunasan is also via transfer
                    ]);

                    try {
                        $snapTokenPelunasan = Snap::getSnapToken([
                            'transaction_details' => [
                                'order_id' => $order_id_pelunasan,
                                'gross_amount' => $remainingAmount,
                            ],
                            'customer_details' => [
                                'first_name' => $booking->nama_pasangan,
                                'email' => $booking->pengguna->email ?? 'noemail@example.com',
                                'phone' => $booking->pengguna->no_hp ?? '0811111111',
                            ]
                        ]);
                        $pelunasan->snap_token = $snapTokenPelunasan;
                        $pelunasan->save();
                    } catch (\Exception $e) {
                        Log::error('Midtrans Pelunasan Snap Token Error: ' . $e->getMessage());
                    }
                }
                // No need to update booking status as it's already 'confirmed'
            }
            // No need to update booking status for full/pelunasan as it's already 'confirmed'
        }

        Log::info('Payment status updated for order_id: ' . $orderId);
        return response()->json(['message' => 'Notification processed successfully'], 200);
    }


    public function sukses(Request $request)
    {
        // This function will still be used for redirect after user completes payment on Midtrans page.
        // The actual status update should be handled by the notification endpoint for reliability.
        $order_id = session('order_id');
        $payment = Payment::where('order_id', $order_id)->first();

        // If the notification has already processed, the status might already be 'berhasil'.
        // This function primarily serves to redirect the user.
        if ($payment && $payment->status !== 'berhasil') {
            // We can add a small delay or check here to ensure the webhook has time to process,
            // but relying on the webhook for status update is generally better.
            // For now, we'll just redirect. The actual update should be from the notification endpoint.
        }

        $request->session()->forget('order_id');

        return redirect()->route('klien.pembayaran.list')->with([
            'status' => 'sukses',
            'message' => 'Pembayaran berhasil! Status akan diperbarui.'
        ]);
    }

    public function pending(Request $request)
    {
        $order_id = session('order_id');
        $payment = Payment::where('order_id', $order_id)->first();
        if ($payment) {
            $payment->status = 'pending'; // Set to pending, but true status will come from webhook
            $payment->save();
        }

        $request->session()->forget('order_id');

        return redirect()->route('klien.pembayaran.list')->with([
            'status' => 'pending',
            'message' => 'Pembayaran masih diproses.'
        ]);
    }

    public function buatPelunasanManual($id)
    {
        $old = Payment::findOrFail($id);
        $booking = $old->booking;

        if ($booking->payments()->where('jenis', 'pelunasan')->exists()) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Pelunasan sudah pernah dibuat.'
            ]);
        }

        $totalHarga = $booking->package->harga_total ?? 0;
        Payment::create([
            'booking_id' => $booking->id,
            'order_id' => null,
            'jumlah' => $totalHarga - $booking->payments()->where('status', 'berhasil')->sum('jumlah'),
            'status' => 'berhasil', // Set to successful immediately for cash
            'jenis' => 'pelunasan',
            'metode' => 'cash',
            'tanggal_bayar' => now()
        ]);

        return redirect()->route('klien.pembayaran.list')->with([
            'status' => 'sukses',
            'message' => 'Pelunasan via cash berhasil dibuat.'
        ]);
    }
    
}