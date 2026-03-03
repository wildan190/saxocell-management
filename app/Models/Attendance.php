<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'location_in',
        'location_out',
        'image_in',
        'image_out',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
