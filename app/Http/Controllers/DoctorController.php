<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function doctor_list(){
        return view ('doctors.doctors_list');
    }

    public function doctor_profile(){
        return view ('doctors.doctor_profile');
    }
}
