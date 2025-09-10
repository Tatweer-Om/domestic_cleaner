<?php

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


// function get_sms($params)
// {

//     // Default variables
//   $worker_name       = "";
// $booking_no        = "";
// $booking_date      = "";
// $booking_time      = "";
// $visit_date        = "";
// $visit_time        = "";
// $package           = "";
// $location          = "";
// $total_visits      = "";
// $remianing_visits  = ""; // ⚠️ typo kept same as your Blade/JS
// $next_visit_date   = "";
// $extention_time    = "";
// $extention_date    = "";
// $cancel_date       = "";
// $driver_no         = "";

//     // Template fetch
//     $sms_text = SMS::where('sms_status', $params['sms_status'])->first();

//     // Case: Appointment booking
//     if ($params['sms_status'] == 1) {




//     }

//     // Consultation done


//     // Case: Session done
//     // else if ($params['sms_status'] == 5) {
//     //     $session_done = SessionData::where('patient_id', $params['patient_id'])
//     //         ->where('status', 2)
//     //         ->where('session_date', $params['session_date'])
//     //         ->where('session_time', $params['session_time'])
//     //         ->first();

//     //     $patient = Patient::find($params['patient_id']);

//     //     $patient_name = $patient->full_name ?? '';
//     //     $hn = $patient->HN ?? '';
//     //     $doctor_id = $session_done->doctor_id ?? null;
//     //     $doctor = $doctor_id ? Doctor::where('id', $doctor_id)->value('doctor_name') ?? '' : '';

//     //     $session_time = $session_done->session_time ?? '';
//     //     $session_date = $session_done->session_date ?? '';
//     // }


//     // Define template replacement variables
//     $variables = [
//         'patient_id' => $params['patient_id'] ?? '',
//         'patient_name' => $patient_name,
//         'hn' => $hn,
//         'consultation_date' => $consultation_date,
//         'consultation_time' => $consultation_time,
//         'consultation_no' => $consultation_no,
//         'doctor' => $doctor,
//         'invoice_link' => $invoice_link,
//         'session_date' => $session_date,
//         'session_time' => $session_time,
//         'session_fee' => $session_fee,
//         'remaining_amount' => $remaining_amount,
//         'offer_name' => $offer_name,

//     ];

//     // Replace placeholders in base64 decoded template
//     $string = base64_decode($sms_text->sms);
//     foreach ($variables as $key => $value) {
//         $string = str_replace('{' . $key . '}', $value, $string);
//     }

//     return $string;
// }



// function sms_module($contact, $sms)
// {
//     if (!empty($contact)) {
//         $url = "http://myapp3.com/whatsapp_admin_latest/Api_pos/send_request";

//         $form_data = [
//             'status' => 1,
//             'sender_contact' => $contact,
//             // 'customer_id' => 'piyvatesys',
//             // 'instance_id' => 'mvkb2o6c',
//             'sms' => base64_encode($sms),
//         ];

//         $curl = curl_init($url);
//         curl_setopt($curl, CURLOPT_URL, $url);
//         curl_setopt($curl, CURLOPT_POST, true);
//         curl_setopt($curl, CURLOPT_POSTFIELDS, $form_data);
//         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//         $headers = array(
//             "Accept: application/json",
//         );
//         curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//         $resp = curl_exec($curl);
//         curl_close($curl);
//         $result = json_decode($resp, true);
//     }
// }
