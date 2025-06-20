<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingDetail;
use Illuminate\Support\Facades\Auth;

class c_vendorBooking extends Controller
{
    public function index()
    {
        $vendorId = Auth::user()->id; // Ganti jika vendor_id disimpan di relasi lain

        $data = BookingDetail::with(['service', 'booking'])
            ->where('vendor_id', $vendorId)
            ->orderBy('event_date', 'asc')
            ->get();

        return view('vendor.pesanan.v_index', compact('data'));
    }

    public function detail($id)
    {
        $vendorId = Auth::user()->id;

        $pesanan = BookingDetail::with(['service', 'booking'])
            ->where('vendor_id', $vendorId)
            ->where('id', $id)
            ->firstOrFail();

        return view('vendor.pesanan.v_detail', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = BookingDetail::where('id', $id)->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak,selesai'
        ]);

        $pesanan->status = $request->status;
        $pesanan->save();

        return redirect()->route('vendor.pesanan.detail', $id)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
