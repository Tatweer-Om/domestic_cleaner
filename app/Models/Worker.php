<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
       protected $fillable = ['worker_name', 'worker_image', 'location_id'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
