<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings'; // Pastikan nama tabelnya 'bookings'
    protected $fillable = [
        'pengguna_id',
        'nama_pasangan',
        'no_ktp',
        'tanggal',
        'alamat_akad',
        'alamat_resepsi',
        'status',
        // 'total_harga',
        'package_id', // Pastikan package_id ada di $fillable jika disimpan di tabel bookings
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }

    // Relasi baru untuk mengambil detail Package
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}