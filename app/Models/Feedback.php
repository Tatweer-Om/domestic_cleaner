<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
      protected $fillable = [
        'customer_id',
        'worker_id',
        'booking_id',
        'rating',
        'notes',
    ];
}
