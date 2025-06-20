<?php

namespace App\Http\Controllers;

use App\Models\Package;

class LandingController extends Controller
{
    public function index()
    {
        $packages = Package::all(); // ambil semua paket

        return view('klien.v_landing-page', compact('packages'));
    }
}