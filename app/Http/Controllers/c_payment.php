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

    public function sukses(Request $request)
    {
        $order_id = session('order_id');
        $payment = Payment::where('order_id', $order_id)->first();

        if ($payment && $payment->status !== 'berhasil') {
            $payment->status = 'berhasil';
            $payment->tanggal_bayar = now();
            $payment->save();

            $booking = $payment->booking;

            if ($payment->jenis === 'dp') {
                Payment::where('booking_id', $booking->id)
                    ->where('jenis', 'pelunasan')
                    ->delete();

                $sisa = $booking->total_harga - $payment->jumlah;
                if ($sisa > 0) {
                    $order_id_pelunasan = 'ORDER-' . strtoupper(Str::random(10));

                    $pelunasan = Payment::create([
                        'booking_id' => $booking->id,
                        'order_id' => $order_id_pelunasan,
                        'jumlah' => $sisa,
                        'status' => 'pending',
                        'jenis' => 'pelunasan',
                        'metode' => 'transfer',
                    ]);

                    try {
                        $snapTokenPelunasan = Snap::getSnapToken([
                            'transaction_details' => [
                                'order_id' => $order_id_pelunasan,
                                'gross_amount' => $sisa,
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
                        Log::error('Midtrans Pelunasan Error: ' . $e->getMessage());
                    }
                } else {
                    $booking->update(['status' => 'booked']);
                }

            } elseif (in_array($payment->jenis, ['full', 'pelunasan'])) {
                $booking->update(['status' => 'booked']);
            }
        }

        $request->session()->forget('order_id');

        return redirect()->route('klien.pembayaran.list')->with([
            'status' => 'sukses',
            'message' => 'Pembayaran berhasil!'
        ]);
    }

    public function pending(Request $request)
    {
        $order_id = session('order_id');
        $payment = Payment::where('order_id', $order_id)->first();
        if ($payment) {
            $payment->status = 'pending';
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
