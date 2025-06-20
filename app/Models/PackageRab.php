<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageRab extends Model
{
    protected $table = 'package_rabs';

    protected $fillable = [
        'package_id',
        'vendor_service_id',
        'harga_item',
        'deskripsi',
    ];

    // Relasi ke Package (many to one)
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    // Relasi ke VendorService (many to one)
    public function vendorService()
    {
        return $this->belongsTo(VendorService::class, 'vendor_service_id');
    }

    // Accessor supaya bisa langsung ambil nama item dari vendorService
    public function getNamaItemAttribute()
    {
        return $this->vendorService ? $this->vendorService->nama_item : null;
    }
}
