<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'booking_id',
        'no_transaksi',
        'order_id',
        'jenis',
        'jumlah',
        'metode',
        'status',
        'snap_token',
        'tanggal_bayar',
        'keterangan',
        'bukti_bayar',
        'cash_notified_1',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
