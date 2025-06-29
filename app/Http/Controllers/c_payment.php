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
        $userId = auth()->id();

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
        $amount = $r->jenis === 'dp' ? $total * 0.5 : $total;

        try {
            $booking = Booking::create([
                'pengguna_id' => auth()->id(),
                'nama_pasangan' => $r->nama_pasangan,
                'no_ktp' => $r->no_ktp,
                'tanggal' => $r->tanggal,
                'alamat_akad' => $r->alamat_akad,
                'alamat_resepsi' => $r->alamat_resepsi,
                'status' => 'pending',
                'package_id' => $selectedPackageId,
            ]);

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'order_id' => $r->metode === 'cash' ? null : $order_id,
                'jumlah' => $amount,
                'status' => $r->metode === 'cash' ? 'menunggu_verifikasi_admin' : 'pending',
                'jenis' => $r->jenis,
                'metode' => $r->metode,
            ]);

            if ($r->metode === 'cash') {
                return response()->json([
                    'snap_token' => null,
                    'redirect_success' => route('klien.pembayaran.list'),
                    'redirect_pending' => route('klien.pembayaran.list'),
                ]);
            }

            $customer_email = auth()->user()->email ?? 'noemail@example.com';
            $customer_phone = auth()->user()->no_hp ?? '0811111111';

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
            ];

            // Add notification URL to the transaction details for Snap to send webhook
            $params['callbacks'] = [
                'finish' => route('klien.pembayaran.list'),
                'error' => route('klien.pembayaran.list'),
                'pending' => route('klien.pembayaran.list'),
            ];


            Log::info('Midtrans Snap Params:', $params);

            $snapToken = Snap::getSnapToken($params);
            $payment->update(['snap_token' => $snapToken]);

            session(['order_id' => $order_id]);

            return response()->json([
                'snap_token' => $snapToken,
                'redirect_snap' => route('klien.pembayaran.snap') . '?token=' . $snapToken,
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

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                // TODO set payment status to 'challenge'
                $payment->status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                // TODO set payment status to 'success'
                $payment->status = 'berhasil';
                $payment->tanggal_bayar = now();
            }
        } else if ($transactionStatus == 'settlement') {
            // TODO set payment status to 'success'
            $payment->status = 'berhasil';
            $payment->tanggal_bayar = now();
        } else if ($transactionStatus == 'pending') {
            // TODO set payment status to 'pending'
            $payment->status = 'pending';
        } else if ($transactionStatus == 'deny') {
            // TODO set payment status to 'deny'
            $payment->status = 'gagal';
        } else if ($transactionStatus == 'expire') {
            // TODO set payment status to 'expire'
            $payment->status = 'kadaluarsa';
        } else if ($transactionStatus == 'cancel') {
            // TODO set payment status to 'cancel'
            $payment->status = 'dibatalkan';
        }

        $payment->save();

        // Update booking status based on payment status
        $booking = $payment->booking;
        if ($payment->status === 'berhasil') {
            if ($payment->jenis === 'dp') {
                // If DP is successful, delete existing pelunasan and create a new one if total_harga > paid amount
                Payment::where('booking_id', $booking->id)
                    ->where('jenis', 'pelunasan')
                    ->delete();

                $totalPaidAmount = $booking->payments()->where('status', 'berhasil')->sum('jumlah');
                $remainingAmount = $booking->total_harga - $totalPaidAmount;

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
                } else {
                    $booking->update(['status' => 'booked']);
                }
            } elseif (in_array($payment->jenis, ['full', 'pelunasan'])) {
                $booking->update(['status' => 'booked']);
            }
        } elseif (in_array($payment->status, ['gagal', 'kadaluarsa', 'dibatalkan'])) {
            // If payment fails, you might want to set booking status to something like 'failed' or 'cancelled'
            // or just leave it as 'pending' for retry. For now, let's keep it 'pending' unless explicitly cancelled by user.
            // $booking->update(['status' => 'cancelled']);
        }

        Log::info('Payment and Booking status updated for order_id: ' . $orderId);
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

        Payment::create([
            'booking_id' => $booking->id,
            'order_id' => null,
            'jumlah' => $booking->total_harga - $booking->payments()->where('status', 'berhasil')->sum('jumlah'),
            'status' => 'menunggu_verifikasi_admin',
            'jenis' => 'pelunasan',
            'metode' => 'cash',
            'tanggal_bayar' => now()
        ]);

        return redirect()->route('klien.pembayaran.list')->with([
            'status' => 'pending',
            'message' => 'Pelunasan via cash berhasil dibuat. Tunggu verifikasi admin.'
        ]);
    }
    
}