<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Klien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class c_profile_klien extends Controller
{
    public function index()
    {
        $pengguna = Pengguna::with('klien')->find(Auth::id());
        return view('klien.profile.v_index', compact('pengguna'));
    }

    public function edit()
    {
        $pengguna = Pengguna::with('klien')->find(Auth::id());
        return view('klien.profile.v_edit', compact('pengguna'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'password' => 'nullable|confirmed|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pengguna = Pengguna::find(Auth::id());
        $pengguna->nama = $request->nama;
        $pengguna->email = $request->email;
        $pengguna->alamat = $request->alamat;
        $pengguna->no_hp = $request->no_hp;

        if ($request->filled('password')) {
            $pengguna->password = Hash::make($request->password);
        }

        $pengguna->save();

        // Cek atau buat data klien
        $klien = Klien::firstOrNew(['id_pengguna' => Auth::id()]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFile = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('images/foto_klien'), $namaFile);

            // Hapus foto lama jika ada
            if ($klien->foto && file_exists(public_path('images/foto_klien/' . $klien->foto))) {
                unlink(public_path('images/foto_klien/' . $klien->foto));
            }

            $klien->foto = $namaFile;
        }

        $klien->id_pengguna = Auth::id(); // wajib kalau datanya baru
        $klien->save();

        return redirect()->route('klien.profile')->with('success', 'Profil berhasil diperbarui.');
    }


}
