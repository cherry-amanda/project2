<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorService;

class c_vendorServiceAdmin extends Controller
{
    public function index($vendor_id)
    {
        $vendor = Vendor::with('pengguna')->findOrFail($vendor_id);
        $services = $vendor->services; // relasi hasMany dari model Vendor

        return view('admin.vendor.v_jasavendor', compact('vendor', 'services'));
    }
    

    public function approve($id)
    {
        $service = VendorService::findOrFail($id);
        $service->status = 'disetujui'; // UBAH ke status
        $service->save();

        return back()->with('success', 'Jasa berhasil disetujui.');
    }

    public function reject($id)
    {
        $service = VendorService::findOrFail($id);
        $service->status = 'ditolak'; // UBAH ke status
        $service->save();

        return back()->with('success', 'Jasa berhasil ditolak.');
    }
}
