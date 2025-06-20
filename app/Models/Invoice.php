<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'id_invoice';
    protected $fillable = [
        'id_klien',
        'nomor_invoice',
        'tanggal_invoice',
        'deskripsi',
        'total_harga',
        'status'
    ];

    public function klien()
    {
        return $this->belongsTo(\App\Models\Klien::class, 'id_klien', 'id_klien');
    }
}
