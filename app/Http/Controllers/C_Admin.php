<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Package; // Sudah sesuai!
use Carbon\Carbon;
use DB;

class C_Admin extends Controller
{
    
    public function dashboard()
    {
        $totalBooking = Booking::count();
        $totalPembayaran = Payment::where('status', 'berhasil')->sum('jumlah');
        $todaysOrders = Booking::whereDate('tanggal', Carbon::today())->count();

        // Ambil data jumlah berdasarkan tipe (paket / jasa)
        $dataTipePaket = Package::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->get();

        return view('admin.v_dashboard-admin', compact(
            'totalBooking',
            'totalPembayaran',
            'todaysOrders',
            'dataTipePaket'
        ));
    }
}
