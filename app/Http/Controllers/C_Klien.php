<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class C_Klien extends Controller
{
    public function dashboard()
    {
        return view('klien.v_dashboard-klien');
    }

    public function dashboardData()
    {
        $user = Auth::user();

        $latestBooking = Booking::with(['package', 'payments'])
            ->where('pengguna_id', $user->id)
            ->latest()
            ->first();

        return view('klien._dashboard_content', compact('latestBooking'));
    }
}
