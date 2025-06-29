<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageRab;
use App\Models\VendorService;
use App\Models\PackagePhoto;
use App\Models\RabCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class c_package extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $packages = Package::with(['photos', 'packageRabs.vendorService.vendor', 'packageRabs.category']);

        if ($type) {
            $packages->where('type', $type);
        }

        return view('admin.package.v_kelolapaket', ['packages' => $packages->get()]);
    }

    public function create()
    {
        $vendorServices = VendorService::with('vendor')->get();
        $rabCategories = RabCategory::orderBy('nama_kategori')->get();
        return view('admin.package.v_createpaket', compact('vendorServices', 'rabCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:paket,jasa',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_total' => 'required|numeric|min:0',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jasa_vendor_service_id' => 'nullable|exists:vendor_services,id',
            'packageRabs.vendor_service_id' => 'nullable|array',
            'packageRabs.harga_item' => 'nullable|array',
            'packageRabs.deskripsi' => 'nullable|array',
            'packageRabs.category_id' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $package = Package::create([
                'type' => $validated['type'],
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'],
                'harga_total' => $validated['harga_total'],
            ]);

            // Upload banyak foto
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->extension();
                    $file->move(public_path('images/foto_paket'), $filename);
                    $package->photos()->create(['filename' => $filename]);
                }
            }

            // Simpan RAB (jika tipe paket)
            if ($validated['type'] === 'paket' && isset($validated['packageRabs']['vendor_service_id'])) {
                foreach ($validated['packageRabs']['vendor_service_id'] as $i => $vs_id) {
                    PackageRab::create([
                        'package_id' => $package->id,
                        'vendor_service_id' => $vs_id,
                        'harga_item' => $validated['packageRabs']['harga_item'][$i] ?? 0,
                        'deskripsi' => $validated['packageRabs']['deskripsi'][$i] ?? null,
                        'category_id' => $validated['packageRabs']['category_id'][$i] ?? null,
                    ]);
                }
            }

            // Simpan jasa tunggal
            if ($validated['type'] === 'jasa' && $validated['jasa_vendor_service_id']) {
                PackageRab::create([
                    'package_id' => $package->id,
                    'vendor_service_id' => $validated['jasa_vendor_service_id'],
                    'harga_item' => $validated['harga_total'],
                    'deskripsi' => 'Item jasa tunggal',
                    'category_id' => null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.package.index')->with('success', 'Paket berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menyimpan paket: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $package = Package::with(['photos', 'packageRabs.vendorService.vendor', 'packageRabs.category'])->findOrFail($id);
        return view('admin.package.v_detailpaket', compact('package'));
    }

    public function edit($id)
    {
        $package = Package::with(['photos', 'packageRabs.vendorService.vendor', 'packageRabs.category'])->findOrFail($id);
        $vendorServices = VendorService::with('vendor')->get();
        $rabCategories = RabCategory::orderBy('nama_kategori')->get();
        return view('admin.package.v_editpaket', compact('package', 'vendorServices', 'rabCategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:paket,jasa',
            'nama' => 'required|string|max:255',
            'harga_total' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $package = Package::with('photos')->findOrFail($id);
        $package->update($request->only(['type', 'nama', 'harga_total', 'deskripsi']));

        // Upload tambahan foto
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->extension();
                $file->move(public_path('images/foto_paket'), $filename);
                $package->photos()->create(['filename' => $filename]);
            }
        }

        // Update RAB (hapus & simpan ulang)
        PackageRab::where('package_id', $package->id)->delete();

        if ($request->type === 'paket' && isset($request->packageRabs['vendor_service_id'])) {
            foreach ($request->packageRabs['vendor_service_id'] as $i => $vs_id) {
                PackageRab::create([
                    'package_id' => $package->id,
                    'vendor_service_id' => $vs_id,
                    'harga_item' => $request->packageRabs['harga_item'][$i],
                    'deskripsi' => $request->packageRabs['deskripsi'][$i] ?? null,
                    'category_id' => $request->packageRabs['category_id'][$i] ?? null,
                ]);
            }
        }

        if ($request->type === 'jasa' && $request->jasa_vendor_service_id) {
            PackageRab::create([
                'package_id' => $package->id,
                'vendor_service_id' => $request->jasa_vendor_service_id,
                'harga_item' => $request->harga_total,
                'deskripsi' => 'Item jasa tunggal',
                'category_id' => null,
            ]);
        }

        return redirect()->route('admin.package.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $package = Package::with('photos')->findOrFail($id);

        foreach ($package->photos as $photo) {
            $filepath = public_path('images/foto_paket/' . $photo->filename);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            $photo->delete();
        }

        PackageRab::where('package_id', $package->id)->delete();
        $package->delete();

        return redirect()->route('admin.package.index')->with('success', 'Paket berhasil dihapus.');
    }

    public function vendorApprovedList()
    {
        $services = VendorService::with('vendor')
            ->where('status', 'disetujui')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.package.v_vendorapproved', compact('services'));
    }
}
