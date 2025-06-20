<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Tampilkan form registrasi
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi user baru
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:admin,vendor,klien'
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login!');
    }

    // Proses login user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

       if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'vendor':
                    // Cek apakah vendor sudah isi profil
                    $vendor = \App\Models\Vendor::where('id_pengguna', $user->id)->first();
                    if ($vendor) {
                        return redirect()->route('vendor.dashboard');
                    } else {
                        return redirect()->route('vendor.profile.create');
                    }
                case 'klien':
                    return redirect()->route('klien.dashboard');
            }
        }


        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->withInput($request->only('email'));
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
