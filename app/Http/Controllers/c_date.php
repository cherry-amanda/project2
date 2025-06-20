<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Date;
use App\Models\Booking;


class c_date extends Controller
{
    public function index()
    {
        $dates = Date::all();
        $bookings = Booking::all();

        return view('admin.date.v_kelolatanggal', compact('dates', 'bookings'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|in:booked,cancelled,pending,holiday,available',
        ]);

        try {
            if ($request->status === 'available') {
                // Jika status tersedia, hapus dari tabel
                Date::where('tanggal', $request->tanggal)->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Tanggal berhasil dihapus dari status (tersedia).',
                ]);
            } else {
                // Simpan atau update status tanggal
                $date = Date::updateOrCreate(
                    ['tanggal' => $request->tanggal],
                    ['status' => $request->status]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Status tanggal berhasil diupdate',
                    'data' => $date
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: '.$e->getMessage()
            ], 500);
        }
    }
}
