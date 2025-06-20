<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    protected $table = 'booking_details';
    protected $fillable = [
        'booking_id', 'vendor_id', 'vendor_service_id',
        'event_date', 'start_time', 'end_time', 'location', 'status'
    ];

    
    
    // Model BookingDetail.php
        public function vendor()
    {
        return $this->belongsTo(\App\Models\Pengguna::class, 'vendor_id');
    }

    // BookingDetail.php
    public function vendorService() {
        return $this->belongsTo(VendorService::class, 'vendor_service_id');
    }
    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
    public function klien() {
        return $this->hasOneThrough(Pengguna::class, Booking::class, 'id', 'id', 'booking_id', 'pengguna_id');
    }



}