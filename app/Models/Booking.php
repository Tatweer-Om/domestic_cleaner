<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'worker_id',
        'package_id',
        'location_id',
        'start_date',
        'duration',
        'status',
        'user_name',
        'added_by',
        'customer_id',
        'visits_count',
        'visits',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
