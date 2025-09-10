<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
       protected $fillable = [
        'worker_name', 'phone', 'worker_user_id', 'location_id', 'shift', 'status', 'worker_image', 'notes', 'user_id', 'added_by'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
