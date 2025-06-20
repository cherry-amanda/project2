<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'pengguna_id',
        'package_id',
        'date_id',
        'tanggal',
        'status',
        'catatan',
        'nama_pasangan',
        'no_ktp',
        'alamat_akad',
        'alamat_resepsi',
    ];


    // Optional: Jika tidak pakai Laravel timestamps
    public $timestamps = true;

    // Relasi ke User (Klien)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // Relasi ke Package
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    // Relasi ke Date
    public function date()
    {
        return $this->belongsTo(Date::class, 'date_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'booking_id');
    }
    public function packageRabs()
    {
        return $this->hasMany(PackageRab::class, 'package_id');
    }
    
}
