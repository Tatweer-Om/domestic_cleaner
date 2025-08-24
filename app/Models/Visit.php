<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
     protected $fillable = [
        'booking_id',
        'visit_date',
        'shift',
        'duration',
        'visit_name',
        'user_id',
        'added_by',
        'customer_id',
        'worker_id',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
