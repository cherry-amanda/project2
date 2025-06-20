<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';       // nama tabel
    protected $primaryKey = 'id';           // primary key, biasanya 'id' default tapi boleh dipastikan


    protected $fillable = [
        'nama',
    ];

    // Relasi ke jadwal/event schedule
    public function schedules()
    {
        return $this->hasMany(EventSchedule::class, 'activity_id');
    }
}
