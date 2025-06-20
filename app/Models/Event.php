<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'booking_id',
        'location',
        'tanggal',
        'is_published'
    ];


    // Relasi ke Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // Relasi ke EventDetail
    public function details()
    {
        return $this->hasMany(EventDetail::class, 'event_id');
    }
}
