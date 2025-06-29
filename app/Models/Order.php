<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'bookings'; // tetap ambil dari tabel bookings

    protected $fillable = [
        'pengguna_id',
        'package_id',
        'tanggal',
        'nama_pasangan',
        'no_ktp',
        'alamat_akad',
        'alamat_resepsi',
        'status', // pending, confirmed, rejected
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
