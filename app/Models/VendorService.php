<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorService extends Model
{
    use HasFactory;

    protected $table = 'vendor_services';

    protected $fillable = [
        'vendor_id',
        'kategori',
        'nama_item',
        'deskripsi',
        'harga',
        'foto',
        'status_jasa'
    ];

    


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function packageRabs()
    {
        return $this->hasMany(PackageRab::class, 'vendor_service_id');
    }

    public function getNamaJasaAttribute()
    {
        return $this->vendor->kategori ?? '-'; // sesuaikan dengan kolom vendor yang ingin ditampilkan
    }

    public function getFotoUrlAttribute()
    {
        return asset('images/vendorservices/' . $this->foto);
    }



    
}
