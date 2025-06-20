<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageRab;
use App\Models\VendorService;
use Illuminate\Support\Str;

class c_package extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');

        $packages = Package::with(['packageRabs.vendorService.vendor']);

        if ($type) {
            $packages->where('type', $type);
        }

        $packages = $packages->get();

        return view('admin.package.v_kelolapaket', compact('packages'));
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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jasa_vendor_service_id' => 'nullable|exists:vendor_services,id',
            'packageRabs.vendor_service_id' => 'nullable|array',
            'packageRabs.harga_item' => 'nullable|array',
            'packageRabs.deskripsi' => 'nullable|array',
        ]);

        // Proses upload foto
        $fotoName = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $ext = $file->getClientOriginalExtension();
            $fotoName = Str::uuid() . '.' . $ext;
            $file->move(public_path('images/foto_paket'), $fotoName);
        }

        // Simpan data ke tabel packages
        $package = Package::create([
            'type' => $validated['type'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'harga_total' => $validated['harga_total'],
            'foto' => $fotoName,
        ]);

        // Jika tipe paket → simpan RAB
        if ($validated['type'] === 'paket' && isset($validated['packageRabs']['vendor_service_id'])) {
            $rabs = [];
            foreach ($validated['packageRabs']['vendor_service_id'] as $i => $vendor_service_id) {
                $rabs[] = [
                    'package_id' => $package->id,
                    'vendor_service_id' => $vendor_service_id,
                    'harga_item' => $validated['packageRabs']['harga_item'][$i] ?? 0,
                    'deskripsi' => $validated['packageRabs']['deskripsi'][$i] ?? null,
                ];
            }
            \App\Models\PackageRab::insert($rabs);
        }

        // Jika tipe jasa → relasi satu item jasa
        if ($validated['type'] === 'jasa' && $validated['jasa_vendor_service_id']) {
            \App\Models\PackageRab::create([
                'package_id' => $package->id,
                'vendor_service_id' => $validated['jasa_vendor_service_id'],
                'harga_item' => $validated['harga_total'],
                'deskripsi' => 'Item jasa tunggal',
            ]);
        }

        return redirect()->route('admin.package.index')->with('success', 'Paket berhasil ditambahkan.');
    }


    public function show($id)
    {
        $package = Package::with(['packageRabs.vendorService.vendor'])->findOrFail($id);
        return view('admin.package.v_detailpaket', compact('package'));
    }

    public function edit($id)
    {
        $package = Package::with(['packageRabs.vendorService.vendor'])->findOrFail($id);
        $vendorServices = VendorService::with('vendor')->get();
        return view('admin.package.v_editpaket', compact('package', 'vendorServices'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:paket,jasa',
            'nama' => 'required|string',
            'harga_total' => 'required|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        $package = Package::findOrFail($id);
        $package->update($request->only(['type', 'nama', 'harga_total', 'deskripsi']));

        // Hapus semua RAB lama, lalu simpan ulang
        if ($request->type == 'paket') {
            // Hapus yang tidak ada di input
            $existingIds = collect($request->packageRabs['id'])->filter();
            PackageRab::where('package_id', $package->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            if ($request->has('packageRabs')) {
                foreach ($request->packageRabs['vendor_service_id'] as $i => $vendorServiceId) {
                    $rabId = $request->packageRabs['id'][$i] ?? null;
                    $dataRab = [
                        'package_id' => $package->id,
                        'vendor_service_id' => $vendorServiceId,
                        'harga_item' => $request->packageRabs['harga_item'][$i],
                        'deskripsi' => $request->packageRabs['deskripsi'][$i] ?? null,
                    ];

                    if ($rabId) {
                        // Update
                        PackageRab::where('id', $rabId)->update($dataRab);
                    } else {
                        // Tambah baru
                        PackageRab::create($dataRab);
                    }
                }
            }
        } else {
            // Kalau type == jasa, hapus semua RAB
            PackageRab::where('package_id', $package->id)->delete();
        }

        return redirect()->route('admin.package.index')->with('success', 'Paket berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        PackageRab::where('package_id', $package->id)->delete();
        $package->delete();

        return redirect()->route('admin.package.index')->with('success', 'Paket berhasil dihapus.');
    }

    private function handleFotoUpload($fotoFile, $oldFoto = null)
    {
        if ($oldFoto && file_exists(public_path('images/foto_paket/' . $oldFoto))) {
            unlink(public_path('images/foto_paket/' . $oldFoto));
        }

        $filename = time() . '_' . Str::random(10) . '.' . $fotoFile->extension();
        $fotoFile->move(public_path('images/foto_paket'), $filename);
        return $filename;
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
