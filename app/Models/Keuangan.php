<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangan';
    protected $fillable = [
        'jenis', 'kategori', 'keterangan', 'nominal', 'tanggal', 'relasi_id', 'bukti'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'relasi_id', 'id');
    }

    public static function getSummaryByMonth()
    {
        return self::selectRaw('MONTH(tanggal) as bulan, jenis, SUM(nominal) as total')
            ->groupBy('bulan', 'jenis')
            ->orderBy('bulan')
            ->get();
    }
}