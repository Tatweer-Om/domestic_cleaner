<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function appointments(){
        return view ('appointments.appointments');
    }
}
