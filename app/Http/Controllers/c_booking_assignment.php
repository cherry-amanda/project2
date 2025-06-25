<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingAssignment;
use Illuminate\Support\Facades\Auth;

class c_booking_assignment extends Controller
{
    public function index()
    {
        $penggunaId = Auth::id(); // id vendor yang login
        $tugas = BookingAssignment::with(['booking', 'service'])
                    ->where('pengguna_id', $penggunaId)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('vendor.pesanan.v_index', compact('tugas'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected,done',
        ]);

        $tugas = BookingAssignment::findOrFail($id);
        if ($tugas->pengguna_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $tugas->status = $request->status;
        $tugas->save();

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui.');
    }
}
