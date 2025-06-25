<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorService;

class C_Vendor extends Controller
{
    public function dashboard()
    {
        $vendor = Auth::user();

        // Hitung jumlah layanan disetujui & menunggu
        $totalDisetujui = VendorService::where('vendor_id', $vendor->id)
            ->where('status', 'disetujui')
            ->count();

        $totalMenunggu = VendorService::where('vendor_id', $vendor->id)
            ->where('status', 'pending')
            ->count();

        $profilLengkap = $vendor->nama && $vendor->email && $vendor->alamat && $vendor->no_hp;

        return view('vendor.v_dashboard-vendor', compact(
            'totalDisetujui', 'totalMenunggu', 'profilLengkap'
        ));
    }
}
