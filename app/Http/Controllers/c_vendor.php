<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorService;
use Illuminate\Support\Facades\Storage;

class c_vendor extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('pengguna')->get(); // Pastikan eager loading relasi
        return view('admin.vendor.v_index', compact('vendors'));
    }

    public function show($id)
    {
        $vendor = Vendor::with('pengguna')->findOrFail($id);
        return view('admin.vendor.v_detailvendor', compact('vendor'));
    }

    public function toggleStatus($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->status = $vendor->status == 'aktif' ? 'nonaktif' : 'aktif';
        $vendor->save();

        return redirect()->route('admin.vendor.index')->with('success', 'Status vendor diperbarui.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return redirect()->route('admin.vendor.index')->with('success', 'Vendor berhasil dihapus.');
    }

    

    public function services($id)
    {
        $vendor = Vendor::with('pengguna')->findOrFail($id);

        $services = VendorService::where('vendor_id', $id)->get();

        return view('admin.vendor.v_jasavendor', compact('vendor', 'services'));
    }

}
