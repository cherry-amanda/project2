<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';           // Nama tabel di database
    protected $primaryKey = 'id';         // Primary key default, bisa dihapus jika memang 'id'
    public $timestamps = false;           // Matikan jika tabel tidak menggunakan created_at & updated_at

    protected $fillable = [
        'nama',
        'email',
        'posisi',
        // Tambah kolom lain sesuai kebutuhan
    ];

    // Relasi ke assignments (jika ada tabel penugasan tersendiri)
    public function assignments()
    {
        return $this->hasMany(EventAssignment::class, 'staff_id');
    }

    // Relasi ke event_schedules jika staff_id jadi penanggung jawab
    public function schedulesAsPj()
    {
        return $this->hasMany(EventSchedule::class, 'staff_id');
    }
}
