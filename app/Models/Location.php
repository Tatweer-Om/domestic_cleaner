<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
   protected $fillable = [
        'location_name',
        'location_fare',
        'driver_availabe', // Note: typo in your code ('driver_availabe' should be 'driver_available')
        'notes',
        'added_by',
        'user_id',
        'polygon',
        'lat_min',
        'lat_max',
        'lon_min',
        'lon_max',
    ];

    protected $casts = [
        'polygon' => 'array', // Automatically handles array-to-JSON and JSON-to-array conversion
        'lat_min' => 'float',
        'lat_max' => 'float',
        'lon_min' => 'float',
        'lon_max' => 'float',
        'driver_availabe' => 'boolean', // Optional: if this is a boolean field
    ];
}
