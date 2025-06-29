<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Booking;
use App\Models\Keuangan;
use App\Models\Event;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class C_Admin extends Controller
{
    public function dashboard()
    {
        try {
            $dataTipePaket = Package::select('type as tipe', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get();

            $upcomingEvents = Event::with(['booking.pengguna'])
                ->whereHas('booking', function($q) {
                    $q->where('tanggal', '>=', now());
                })
                ->orderBy('created_at', 'asc')
                ->limit(5)
                ->get();

            return view('admin.v_dashboard-admin', [
                'todaysOrders' => Booking::whereDate('created_at', today())->count(),
                'totalBooking' => Booking::count(),
                'totalPembayaran' => Keuangan::where('jenis', 'pemasukan')->sum('nominal'),
                'newClientsThisWeek' => Pengguna::where('role', 'klien')
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
                'upcomingEvents' => $upcomingEvents,
                'latestTransactions' => Keuangan::latest()->limit(5)->get(),
                'pendingVerifications' => Booking::where('status', '!=', 'confirmed')->count(),
                'dataTipePaket' => $dataTipePaket
            ]);
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Admin Dashboard Error: ' . $e->getMessage());
            
            // Return view with default values if there's an error
            return view('admin.v_dashboard-admin', [
                'todaysOrders' => 0,
                'totalBooking' => 0,
                'totalPembayaran' => 0,
                'newClientsThisWeek' => 0,
                'upcomingEvents' => collect(),
                'latestTransactions' => collect(),
                'pendingVerifications' => 0,
                'dataTipePaket' => collect()
            ]);
        }
    }

        
}
