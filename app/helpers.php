<?php

use App\Models\SMS;
use App\Models\Visit;
use App\Models\Driver;
use App\Models\Worker;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\App;

if (! function_exists('current_locale')) {
    function current_locale(): string
    {
        return App::getLocale();
    }
}

if (! function_exists('is_rtl')) {
    function is_rtl(): bool
    {
        return in_array(current_locale(), ['ar', 'ur', 'he', 'fa'], true);
    }
}

if (! function_exists('dir_attr')) {
    function dir_attr(): string
    {
        return is_rtl() ? 'rtl' : 'ltr';
    }
}

if (! function_exists('text_dir_class')) {
    function text_dir_class(): string
    {
        return is_rtl() ? 'rtl' : 'ltr';
    }
}

function get_sms($params)
{
    // Default variables
    $worker_name       = "";
    $booking_no        = "";
    $booking_id        = "";
    $booking_date      = "";
    $booking_time      = "";
    $visit_date        = "";
    $visit_time        = "";
    $package           = "";
    $location          = "";
    $total_visits      = "";
    $remianing_visits  = "";
    $next_visit_date   = "";
    $extention_time    = "";
    $extention_date    = "";
    $cancel_date       = "";
    $driver_no         = "";
    $customer_name     = "";
    $customer_no       = "";
    $worker_no         = "";
    $shift             = "";
    $duration          = "";
    $total_visit       = "";
    $first_visit       = "";
    $driver_name       = "";

    // Template fetch
    $sms_text = SMS::where('sms_status', $params['sms_status'])->first();

    // Case: booking (sms_status = 1)
    if ($params['sms_status'] == 1) {
        $booking = Booking::where('id', $params['booking_id'])
            ->where('payment_status', 1)
            ->whereNotNull('transactionId')
            ->first();

        if ($booking) {
            $customer       = Customer::find($booking->customer_id);
            $customer_no    = $customer->phone_number ?? '';
            $customer_name  = $customer->customer_name ?? '';

            $driver         = Driver::find($booking->driver_id);
            $driver_no      = $driver->phone ?? '';
            $driver_name    = $driver->driver_name ?? '';

            $worker         = Worker::find($booking->worker_id);
            $worker_no      = $worker->phone ?? '';
            $worker_name    = $worker->worker_name ?? '';
            $shift     = $booking->shift ?? '';
            $duration= $booking->duration ?? '';
            $booking_no     = $booking->booking_no ?? '';
            $booking_date   = $booking->booking_date ?? '';
            $booking_time   = $booking->booking_time ?? '';
        }
    }
    // Case: visit done (sms_status = 2)
    else if ($params['sms_status'] == 2) {
        $visit_done = Visit::where('id', $params['visit_id'])
            ->where('status', 2)
            ->where('visit_date', $params['visit_date'])
            ->where('shift', $params['shift'])
            ->first();

        if ($visit_done) {
            $worker         = Worker::find($visit_done->worker_id);
            $worker_name    = $worker->worker_name ?? '';
            $worker_no      = $worker->phone ?? '';

            $customer       = Customer::find($visit_done->customer_id);
            $customer_no    = $customer->phone_number ?? '';
            $customer_name  = $customer->customer_name ?? '';

            $visit_date     = $visit_done->visit_date ?? '';
            $shift          = $visit_done->shift ?? '';
            $duration       = $visit_done->duration ?? '';
        }
    }

    // Define template replacement variables (aligned with upper vars)
    $variables = [
        'worker_name'       => $worker_name,
        'booking_no'        => $booking_no,
        'booking_id'        => $booking_id,
        'booking_date'      => $booking_date,
        'booking_time'      => $booking_time,
        'visit_date'        => $visit_date,
        'visit_time'        => $visit_time,
        'package'           => $package,
        'location'          => $location,
        'total_visits'      => $total_visits,
        'remianing_visits'  => $remianing_visits,
        'next_visit_date'   => $next_visit_date,
        'extention_time'    => $extention_time,
        'extention_date'    => $extention_date,
        'cancel_date'       => $cancel_date,
        'driver_no'         => $driver_no,
        'driver_name'       => $driver_name,
        'customer_name'     => $customer_name,
        'customer_no'       => $customer_no,
        'worker_no'         => $worker_no,
        'shift'             => $shift,
        'duration'          => $duration,
        'total_visit'       => $total_visit,
        'first_visit'       => $first_visit,
    ];

    // Replace placeholders in base64 decoded template
    $string = base64_decode($sms_text->sms);
    foreach ($variables as $key => $value) {
        $string = str_replace('{' . $key . '}', $value, $string);
    }

    return $string;
}




function sms_module($contact, $sms)
{
    if (!empty($contact)) {
     
        $url = "http://myapp3.com/whatsapp_admin_latest/Api_pos/send_request";

        $form_data = [
            'status' => 1,
            'sender_contact' => $contact,
            'customer_id' => 'tatweeersoftweb',
            'instance_id' => '1xwaxr8k',
            'sms' => base64_encode($sms),
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $form_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($resp, true);
    }
}
