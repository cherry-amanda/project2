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

        // Ambil data pengguna
        $pengguna = Pengguna::find(Auth::id());
        $pengguna->nama = $request->nama;
        $pengguna->email = $request->email;
        $pengguna->alamat = $request->alamat;
        $pengguna->no_hp = $request->no_hp;

        if ($request->filled('password')) {
            $pengguna->password = Hash::make($request->password);
        }

        $pengguna->save();

        // Ambil atau buat data klien
        $klien = Klien::firstOrNew(['id_pengguna' => $pengguna->id]);

        // Proses upload foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $ext = $foto->getClientOriginalExtension();
            $namaFile = 'foto_' . time() . '.' . $ext;

            // Buat folder kalau belum ada
            $folderPath = public_path('images/foto_klien');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            // Hapus foto lama kalau ada
            if ($klien->foto) {
                $oldPath = public_path('images/foto_klien/' . $klien->foto);
                if (file_exists($oldPath)) {
                    @unlink($oldPath); // pakai @ untuk hindari warning
                }
            }

            // Simpan foto baru
            $foto->move($folderPath, $namaFile);
            $klien->foto = $namaFile;
        }

        $klien->id_pengguna = $pengguna->id; // wajib untuk firstOrNew
        $klien->save();

        return redirect()->route('klien.profile')->with('success', 'Profil berhasil diperbarui.');
    }


}
