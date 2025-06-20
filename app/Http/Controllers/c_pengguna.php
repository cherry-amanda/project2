<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class c_pengguna extends Controller
{
    public function index()
    {
        $data = Pengguna::all();
        return view('admin.pengguna.v_pengguna', compact('data'));
    }

    public function create()
    {
        return view('admin.pengguna.v_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pengguna,email',
            'no_hp' => 'required',
            'alamat' => 'required',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Pengguna::findOrFail($id);
        return view('admin.pengguna.v_edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pengguna,email,' . $id,
            'no_hp' => 'required',
            'alamat' => 'required',
            'role' => 'required'
        ]);

        $data = Pengguna::findOrFail($id);
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->no_hp = $request->no_hp;
        $data->alamat = $request->alamat;
        $data->role = $request->role;

        // Jika ada password baru
        if ($request->filled('password')) {
            $data->password = Hash::make($request->password);
        }

        $data->save();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = Pengguna::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
