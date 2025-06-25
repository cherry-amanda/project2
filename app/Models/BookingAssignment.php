<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class m_booking_assignment extends Model
{
    protected $table = 'booking_assignments';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'booking_id',
        'pengguna_id',
        'vendor_service_id',
        'status',
        'catatan_admin'
    ];

    // Relasi opsional
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function service()
    {
        return $this->belongsTo(VendorService::class, 'vendor_service_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
