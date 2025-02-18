<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function patient_list(){
        return view ('patients.patients_list');
    }

    public function patient_profile(){
        return view ('patients.patient_profile');
    }
}
