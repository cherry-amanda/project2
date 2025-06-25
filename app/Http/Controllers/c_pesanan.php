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
    public function konfirmasi($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        if ($payment->status === 'berhasil') {
            return back()->with('success', 'Pembayaran sudah dikonfirmasi sebelumnya.');
        }

        // Update status jadi berhasil
        $payment->status = 'berhasil';
        $payment->tanggal_bayar = now();
        $payment->save();

        // Masukkan ke tabel keuangan
        \App\Models\Keuangan::create([
            'jenis' => 'pemasukan',
            'kategori' => ucfirst($payment->jenis),
            'keterangan' => 'Pembayaran oleh ' . ($payment->booking->nama_pasangan ?? 'klien'),
            'nominal' => $payment->jumlah,
            'tanggal' => $payment->tanggal_bayar,
            'relasi_id' => $payment->id,
            'bukti' => null
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi dan dicatat di keuangan.');
    }
    public function show($id)
    {
        $payment = Payment::with([
            'booking.pengguna',
            'booking.package.vendorServices.vendorService.vendor'
        ])->findOrFail($id);

        return view('admin.pesanan.v_detail', compact('payment'));
    }


}
