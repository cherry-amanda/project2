<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klien extends Model
{
    protected $table = 'klien';
    protected $primaryKey = 'id_klien';

    protected $fillable = [
        'id_pengguna',
        'foto'
    ];


    public $timestamps = true;

    // Relasi ke pengguna
    public function klien()
    {
        return $this->hasOne(Klien::class, 'id_pengguna', 'id');
    }

}
