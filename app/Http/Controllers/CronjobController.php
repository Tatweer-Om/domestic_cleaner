<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Workshop; 
use Carbon\Carbon;

class CronJobController extends Controller
{
    
    public function AmwalCancelSession()
    {
        $orders = Booking::where('payment_status', 0)->get();
        
        foreach ($orders as $order) {
            $expirationTime = Carbon::parse($order->expiration_time);
            $now = Carbon::now();

            if ($now->greaterThan($expirationTime)) {
                // Update payment_status to 2 (cancelled) in orders table
                Booking::where('id', $order->id)->update(['payment_status' => 2]);
            }
        }
    }
}
