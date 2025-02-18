<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/switch-language/{locale}', [HomeController::class, 'switchLanguage'])->name('switch_language');
Route::get('login_page', [HomeController::class, 'login_page'])->name('login_page');




//PatientController

Route::get('patient_list', [PatientController::class, 'patient_list'])->name('patient_list');
Route::get('patient_profile', [PatientController::class, 'patient_profile'])->name('patient_profile');


//doctorController

Route::get('doctor_list', [DoctorController::class, 'doctor_list'])->name('doctor_list');
Route::get('doctor_profile', [DoctorController::class, 'doctor_profile'])->name('doctor_profile');

//staffController

Route::get('staff_list', [StaffController::class, 'staff_list'])->name('staff_list');
Route::get('staff_profile', [StaffController::class, 'staff_profile'])->name('staff_profile');

//appointmentController

Route::get('appointments', [AppointmentController::class, 'appointments'])->name('appointments');
