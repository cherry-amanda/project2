<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Keuangan;

class c_pesanan extends Controller
{
    public function index()
    {
        $data = Payment::with('booking.pengguna')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pesanan.v_list', compact('data'));
    }

    public function show($id)
    {
        $payment = Payment::with('booking.bookingDetails.vendorService', 'booking.pengguna')
            ->findOrFail($id);

        return view('admin.pesanan.v_detail', compact('payment'));
    }

    public function verifikasiPembayaran($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        if ($payment->status === 'berhasil') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah berhasil.');
        }

        $payment->status = 'berhasil';
        $payment->save();

        Keuangan::create([
            'jenis' => 'pemasukan',
            'kategori' => 'Pembayaran Klien',
            'keterangan' => 'Verifikasi manual: ' . ($payment->booking->nama_pasangan ?? 'Tanpa nama'),
            'nominal' => $payment->jumlah,
            'tanggal' => now(),
            'relasi_id' => $payment->id,
        ]);

        return redirect()->route('admin.pesanan.index')->with('success', 'Pembayaran berhasil diverifikasi & dicatat sebagai pemasukan.');
    }
}
