<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorService;
use Illuminate\Support\Facades\Auth;

class c_vendorservice extends Controller
{
    public function index()
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor tidak ditemukan.');
        }

        $services = $vendor->services()->latest()->get();

        return view('vendor.service.v_vendorservice', compact('services'));
    }

    public function create()
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->route('vendor.service.index')->with('error', 'Vendor tidak ditemukan.');
        }

        return view('vendor.service.v_create', compact('vendor'));
    }

    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->route('vendor.service.index')->with('error', 'Vendor tidak ditemukan.');
        }

        $request->validate([
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|numeric',
            'foto'      => 'nullable|image|max:2048',
        ]);

        $data = [
            'vendor_id' => $vendor->id,
            'kategori'  => $vendor->kategori,
            'nama_item' => $request->nama_item,
            'deskripsi' => $request->deskripsi,
            'harga'     => $request->harga,
            'status'    => 'pending',
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/vendorservices'), $filename);
            $data['foto'] = $filename;
        }

        VendorService::create($data);

        return redirect()->route('vendor.service.index')->with('success', 'Layanan berhasil ditambahkan dan menunggu persetujuan.');
    }

    public function show($id)
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->route('vendor.service.index')->with('error', 'Vendor tidak ditemukan.');
        }

        $service = VendorService::where('vendor_id', $vendor->id)->findOrFail($id);

        return view('vendor.service.v_detail', compact('service'));
    }

    public function edit($id)
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->route('vendor.service.index')->with('error', 'Vendor tidak ditemukan.');
        }

        $service = VendorService::where('vendor_id', $vendor->id)->findOrFail($id);

        return view('vendor.service.v_edit', compact('service', 'vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->route('vendor.service.index')->with('error', 'Vendor tidak ditemukan.');
        }

        $service = VendorService::where('vendor_id', $vendor->id)->findOrFail($id);

        $request->validate([
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|numeric',
            'foto'      => 'nullable|image|max:2048',
        ]);

        $data = [
            'kategori'     => $vendor->kategori,
            'nama_item'    => $request->nama_item,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
            'status'       => 'pending', // Reset status ke pending
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/vendorservices'), $filename);
            $data['foto'] = $filename;

            if ($service->foto && file_exists(public_path('images/vendorservices/' . $service->foto))) {
                unlink(public_path('images/vendorservices/' . $service->foto));
            }
        }

        $service->update($data);

        return redirect()->route('vendor.service.index')->with('success', 'Layanan berhasil diupdate dan menunggu persetujuan.');
    }

    public function destroy($id)
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->route('vendor.service.index')->with('error', 'Vendor tidak ditemukan.');
        }

        $service = VendorService::where('vendor_id', $vendor->id)->findOrFail($id);

        if ($service->foto && file_exists(public_path('images/vendorservices/' . $service->foto))) {
            unlink(public_path('images/vendorservices/' . $service->foto));
        }

        $service->delete();

        return back()->with('success', 'Layanan berhasil dihapus.');
    }
}
