<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Booking;
use App\Models\Keuangan;
use App\Models\Event;
use App\Models\Package;
use Carbon\Carbon;
use DB;

class C_Admin extends Controller
{
    public function dashboard()
    {
        $dataTipePaket = Package::select('type as tipe', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        return view('admin.v_dashboard-admin', [
            'todaysOrders' => Booking::whereDate('created_at', today())->count(),
            'totalBooking' => Booking::count(),
            'totalPembayaran' => Keuangan::where('jenis', 'pemasukan')->sum('nominal'),
            'newClientsThisWeek' => Pengguna::where('role', 'klien')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'upcomingEvents' => Event::with(['booking.pengguna'])
                ->whereHas('booking', fn($q) => $q->where('tanggal', '>=', now()))
                ->orderByRaw('(select tanggal from bookings where bookings.id = events.booking_id) asc')
                ->limit(5)
                ->get(),
            'latestTransactions' => Keuangan::latest()->limit(5)->get(),
            'pendingVerifications' => Booking::where('status', '!=', 'pending',)->count(),
            'dataTipePaket' => $dataTipePaket
        ]);
    }

        
}
