<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RabCategory extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    public function rabs()
    {
        return $this->hasMany(PackageRab::class, 'category_id');
    }
}
