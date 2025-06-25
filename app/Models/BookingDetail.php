<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    protected $table = 'booking_details';
    protected $fillable = [
        'booking_id',
        'vendor_service_id', // Kolom ini ada di tabel Anda
        'qty', // Kolom ini ada di tabel Anda
        'harga_total', // Kolom ini ada di tabel Anda
        // 'vendor_id', // Ini tidak terlihat di gambar tabel booking_details yang Anda berikan
        // 'event_date', 'start_time', 'end_time', 'location', 'status' // Ini tidak terlihat di gambar tabel booking_details Anda
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // Jika vendor_service_id sebenarnya merujuk ke tabel 'packages',
    // maka relasi ini harusnya ke Package.
    // Karena Anda mengirim package_id dari cart, mari asumsikan ini merujuk ke Package.
    public function vendorService() // Nama fungsi ini misleading jika merujuk ke Package
    {
        return $this->belongsTo(Package::class, 'vendor_service_id');
    }

    public function klien()
    {
        return $this->hasOneThrough(Pengguna::class, Booking::class, 'id', 'id', 'booking_id', 'pengguna_id');
    }
}