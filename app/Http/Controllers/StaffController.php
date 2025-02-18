<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function staff_list(){
        return view ('staff.staf_list');
    }

    public function staff_profile(){
        return view ('staff.staf_profile');
    }
}
