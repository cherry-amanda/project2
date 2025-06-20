<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class c_vendor_profile extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $profile = Vendor::where('id_pengguna', $userId)->first();
        return view('vendor.profile.v_profile', compact('profile'));
    }

    public function create()
    {
        return view('vendor.profile.v_createprofile');
    }

   
    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $vendor = Vendor::updateOrCreate(
            ['id_pengguna' => $userId],
            [
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
            ]
        );

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($vendor->foto && file_exists(public_path('images/vendors/' . $vendor->foto))) {
                unlink(public_path('images/vendors/' . $vendor->foto));
            }

            $fotoFile = $request->file('foto');
            $fotoName = time() . '_' . $fotoFile->getClientOriginalName();
            $fotoFile->move(public_path('images/vendors'), $fotoName);

            $vendor->foto = $fotoName;
            $vendor->save();
        }


        return redirect()->route('vendor.profile.index')->with('success', 'Profil vendor berhasil disimpan.');
    }


   
    public function edit()
    {
        // Ambil data vendor sesuai user yang login (misal user_id == id_pengguna di vendor)
        $vendor = Vendor::where('id_pengguna', Auth::id())->first();

        if (!$vendor) {
            // Jika vendor tidak ditemukan, bisa redirect atau buat vendor baru
            return redirect()->back()->with('error', 'Vendor tidak ditemukan.');
        }

        return view('vendor.profile.v_editprofile', compact('vendor'));
    }

    public function update(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $vendor = Vendor::where('id_pengguna', $userId)->firstOrFail();
        $vendor->kategori = $request->kategori;
        $vendor->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto')) {
            if ($vendor->foto) {
                Storage::delete('public/images/vendors/' . $vendor->foto);
            }
            $fotoName = $request->file('foto')->store('images/vendors', 'public');
            $vendor->foto = basename($fotoName);
        }

        $vendor->save();

        return redirect()->route('vendor.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
