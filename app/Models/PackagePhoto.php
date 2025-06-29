<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePhoto extends Model
{
    protected $table = 'package_photos';

    protected $fillable = ['package_id', 'filename'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
