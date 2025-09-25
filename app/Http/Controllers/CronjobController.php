<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Workshop; 
use Carbon\Carbon;
use App\Models\Visit;
use App\Models\Worker;
use App\Models\Driver;

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



 

public static function sendVisitNotifications()
{
    $now = Carbon::now();

    // Fetch all workers and drivers
    $workers = Worker::all();
    $drivers = Driver::all();

    // Fetch upcoming visits with status = 1
    $visits = Visit::with('worker')->where('status', 1)->get();

    foreach ($visits as $visit) {
        // Determine visit start time based on shift
        $shiftStartTime = ($visit->shift === 'morning') ? '08:00:00' : '16:00:00';
        $visitDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $visit->date . ' ' . $shiftStartTime);

        // Notification times: 24h, 10h, 2h before visit
        $notifyTimes = [
            $visitDateTime->copy()->subDay(),
            $visitDateTime->copy()->subHours(10),
            $visitDateTime->copy()->subHours(2),
        ];

        foreach ($notifyTimes as $notifyTime) {
            // Check if current time is within +/- 1 minute of notify time
            if ($now->between($notifyTime->copy()->subMinute(), $notifyTime->copy()->addMinute())) {

                $visitLocations = json_decode($visit->location_id, true) ?: [];

                // ---------------- Send to Worker ----------------
                if ($visit->worker) {
                    $worker = $visit->worker;
                    $workerLocations = json_decode($worker->location_id, true) ?: [];

                    $sendToWorker = false;

                    // Send if worker is assigned to visit
                    if ($visit->worker_id == $worker->id) {
                        $sendToWorker = true;
                    }

                    foreach ($drivers as $driver) {
                        $driverLocations = json_decode($driver->location_id, true) ?: [];
                        if (count(array_intersect($driverLocations, $visitLocations))) {
                            $sendToWorker = true;
                            break;
                        }
                    }

                    if ($sendToWorker) {
                      
                        $contact = $worker->phone;

                        $smsParams = [
                            'sms_status'  => 8, // visit reminder
                            'visit_id'    => $visit->id,
                            'visit_date'  => $visit->date,
                            'shift'       => $visit->shift,
                            'start_time'  => $shiftStartTime,
                            'duration'    => $visit->duration ?? '',
                            'worker_name' => $worker->worker_name ?? '',
                        ];

                        $sms = get_sms($smsParams);
                        sms_module($contact, $sms);
                    }
                }

                // ---------------- Send to Drivers ----------------
                foreach ($drivers as $driver) {
                    if ($driver->whatsapp_notification != 1) continue;

                    $driverLocations = json_decode($driver->location_id, true) ?: [];
                    if (count(array_intersect($driverLocations, $visitLocations))) {
                        $contact = $driver->phone;

                        $smsParams = [
                            'sms_status'  => 7, // visit reminder
                            'visit_id'    => $visit->id,
                            'visit_date'  => $visit->date,
                            'shift'       => $visit->shift,
                            'start_time'  => $shiftStartTime,
                            'duration'    => $visit->duration ?? '',
                            'worker_name' => $visit->worker->worker_name ?? '',
                        ];

                        $sms = get_sms($smsParams);
                        sms_module($contact, $sms);
                    }
                }

            } // end if notification time
        } // end foreach notifyTimes
    } // end foreach visits
}


}
