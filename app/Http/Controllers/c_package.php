<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageRab;
use App\Models\VendorService;
use App\Models\PackagePhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class c_package extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');

        $packages = Package::with(['photos', 'packageRabs.vendorService.vendor']);

        if ($type) {
            $packages->where('type', $type);
        }

        return view('admin.package.v_kelolapaket', ['packages' => $packages->get()]);
    }

    public function create()
    {
        $vendorServices = VendorService::with('vendor')->get();
        return view('admin.package.v_createpaket', compact('vendorServices'));
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
        ]);

        DB::beginTransaction();
        try {
            $package = Package::create([
                'type' => $validated['type'],
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'],
                'harga_total' => $validated['harga_total'],
            ]);

            // Simpan banyak foto
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->extension();
                    $file->move(public_path('images/foto_paket'), $filename);
                    $package->photos()->create(['fililename]);
                }
            }

            // Simpan RAB
            if ($validated['type'] === 'paket' && isset($validated['packageRabs']['vendor_service_id'])) {
                foreach ($validated['packageRabs']['vendor_service_id'] as $i => $vs_id) {
                    PackageRab::create([
                        'package_id' => $package->id,
                        'vendor_service_id' => $vs_id,
                        'harga_item' => $validated['packageRabs']['harga_item'][$i] ?? 0,
                        'deskripsi' => $validated['packageRabs']['deskripsi'][$i] ?? null,
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
        $package = Package::with(['photos', 'packageRabs.vendorService.vendor'])->findOrFail($id);
        return view('admin.package.v_detailpaket', compact('package'));
    }

    public function edit($id)
    {
        $package = Package::with(['photos', 'packageRabs.vendorService.vendor'])->findOrFail($id);
        $vendorServices = VendorService::with('vendor')->get();
        return view('admin.package.v_editpaket', compact('package', 'vendorServices'));
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

        // Upload dan simpan tambahan foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->extension();
                $file->move(public_path('images/foto_paket'), $filename);
                $package->photos()->create(['filename' => $filename]);
            }
        }

        // Update RAB
        if ($request->type == 'paket') {
            $existingIds = collect($request->packageRabs['id'])->filter();
            PackageRab::where('package_id', $package->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            foreach ($request->packageRabs['vendor_service_id'] as $i => $vs_id) {
                $rabId = $request->packageRabs['id'][$i] ?? null;
                $dataRab = [
                    'package_id' => $package->id,
                    'vendor_service_id' => $vs_id,
                    'harga_item' => $request->packageRabs['harga_item'][$i],
                    'deskripsi' => $request->packageRabs['deskripsi'][$i] ?? null,
                ];

                if ($rabId) {
                    PackageRab::where('id', $rabId)->update($dataRab);
                } else {
                    PackageRab::create($dataRab);
                }
            }
        } else {
            PackageRab::where('package_id', $package->id)->delete();
        }

        return redirect()->route('admin.package.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $package = Package::with('photos')->findOrFail($id);

        // Hapus foto-fotonya
        foreach ($package->photos as $photo) {
            $filepath = public_path('images/foto_paket/' . $photo->filename);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            $photo->delete();
        }

        // Hapus RAB dan paket
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
