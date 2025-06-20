<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id_pengguna');
    }

    public function klien()
    {
        return $this->hasOne(Klien::class, 'id_pengguna');
    }


}
