<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorService;
use Illuminate\Http\Request;

class c_kelolavendor extends Controller
{
    // Tampilkan semua vendor
    public function index()
    {
        $vendors = Vendor::with('pengguna')->latest()->get();
        return view('admin.vendor.v_index', compact('vendors'));
    }

    // Tampilkan detail vendor
    public function show($id)
    {
        $vendor = Vendor::with('pengguna')->findOrFail($id);
        return view('admin.vendor.v_detailvendor', compact('vendor'));
    }

    // Tampilkan jasa dari vendor tertentu
    public function services($id)
    {
        $vendor = Vendor::with('pengguna')->findOrFail($id);
        $services = VendorService::where('vendor_id', $vendor->id)->get();
        return view('admin.vendor.v_jasavendor', compact('vendor', 'services'));
    }

    // Ubah status aktif/nonaktif vendor
    public function toggleStatus($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->status = $vendor->status === 'aktif' ? 'nonaktif' : 'aktif';
        $vendor->save();

        return redirect()->back()->with('success', 'Status vendor berhasil diperbarui.');
    }

    // Hapus vendor
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        // Hapus file foto jika ada
        if ($vendor->foto && file_exists(public_path('images/vendors/' . $vendor->foto))) {
            unlink(public_path('images/vendors/' . $vendor->foto));
        }

        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor berhasil dihapus.');
    }

    // Setujui jasa vendor
    public function approveService($id)
    {
        $service = VendorService::findOrFail($id);
        $service->status = 'disetujui';
        $service->save();

        return redirect()->back()->with('success', 'Jasa berhasil disetujui.');
    }

    // Tolak jasa vendor
    public function rejectService($id)
    {
        $service = VendorService::findOrFail($id);
        $service->status = 'ditolak';
        $service->save();

        return redirect()->back()->with('success', 'Jasa berhasil ditolak.');
    }
}
