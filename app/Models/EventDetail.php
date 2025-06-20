<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    protected $table = 'event_details';

    protected $fillable = [
        'event_id',
        'activity_id',
        'staff_id',
        'time',
        'role',
    ];

    // Relasi
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

}
