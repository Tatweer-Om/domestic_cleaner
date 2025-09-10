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
        'status',
    ];

    // public function booking()
    // {
    //     return $this->belongsTo(Booking::class);
    // }

    public function booking() { return $this->belongsTo(Booking::class, 'booking_id'); }
public function worker()  { return $this->belongsTo(Worker::class,  'worker_id'); }

public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


protected $casts = [
    'visit_date' => 'date',
    'status' => 'integer',
];
}
