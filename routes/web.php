<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureGuestToken;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ExpensecatController;
use App\Http\Controllers\Website\CronjobController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('home', [HomeController::class, 'index'])->name('home');
Route::get('/switch-language', [HomeController::class, 'switchLanguage'])->name('switch_language');
Route::get('login_page', [HomeController::class, 'login_page'])->name('login_page');
Route::get('login_error', [HomeController::class, 'login_error'])->name('login_error');


//driverController

Route::get('driver', [DriverController::class, 'index'])->name('driver');
Route::post('add_driver', [DriverController::class, 'add_driver'])->name('add_driver');
Route::get('show_driver', [DriverController::class, 'show_driver'])->name('show_driver');
Route::post('edit_driver', [DriverController::class, 'edit_driver'])->name('edit_driver');
Route::post('update_driver', [DriverController::class, 'update_driver'])->name('update_driver');
Route::post('delete_driver', [DriverController::class, 'delete_driver'])->name('delete_driver');

Route::post('login', [UserController::class, 'login'])->name('login');
Route::get('logout', [UserController::class, 'logout'])->name('logout');


//Worker
Route::get('worker', [WorkerController::class, 'index'])->name('worker');
Route::post('add_worker', [WorkerController::class, 'add_worker'])->name('add_worker');
Route::get('show_worker', [WorkerController::class, 'show_worker'])->name('show_worker');
Route::post('edit_worker', [WorkerController::class, 'edit_worker'])->name('edit_worker');
Route::post('update_worker', [WorkerController::class, 'update_worker'])->name('update_worker');
Route::post('delete_worker', [WorkerController::class, 'delete_worker'])->name('delete_worker');
Route::get('worker_list', [WorkerController::class, 'workers_list'])->name('worker_list');
Route::get('worker_page/{id}', [WorkerController::class, 'worker_page'])->name('worker_page');

//locations
Route::get('location', [LocationController::class, 'index'])->name('location');
Route::post('add_location', [LocationController::class, 'add_location'])->name('add_location');
Route::get('show_location', [LocationController::class, 'show_location'])->name('show_location');
Route::post('edit_location', [LocationController::class, 'edit_location'])->name('edit_location');
Route::post('update_location', [LocationController::class, 'update_location'])->name('update_location');
Route::post('delete_location', [LocationController::class, 'delete_location'])->name('delete_location');


//locations
Route::get('driver_page/{id}', [DriverController::class, 'driver_page'])->name('driver_page');
Route::get('driver', [DriverController::class, 'index'])->name('driver');
Route::post('add_driver', [DriverController::class, 'add_driver'])->name('add_driver');
Route::get('show_driver', [DriverController::class, 'show_driver'])->name('show_driver');
Route::post('edit_driver', [DriverController::class, 'edit_driver'])->name('edit_driver');
Route::post('update_driver', [DriverController::class, 'update_driver'])->name('update_driver');
Route::post('delete_driver', [DriverController::class, 'delete_driver'])->name('delete_driver');
Route::get('drivers/{driver}/visits', [DriverController::class, 'visitsPage'])->name('driver.visits.page');
Route::get('drivers/{driver}/visits/today', [DriverController::class, 'todayVisitsdriver'])->name('driver.visits.today');
Route::get('drivers/{driver}/visits/this_week', [DriverController::class, 'thisWeekVisitsdriver'])->name('driver.visits.this_week');
Route::get('drivers/{driver}/visits/all', [DriverController::class, 'allVisitsdriver'])->name('driver.visits.all');
Route::post('driver/visits/complete', [DriverController::class, 'completeVisitdriver'])->name('driver.visits.complete');
Route::get('driver/{id}/visits/next24hours', [DriverController::class, 'upcomingVisitsDriver'])
    ->name('driver.visits.next24hours');
//usercontroller

Route::get('user', [UserController::class, 'index'])->name('user');
Route::get('worker_users', [UserController::class, 'worker_users'])->name('worker_users');
Route::get('driver_users', [UserController::class, 'driver_users'])->name('driver_users');
Route::get('general_users', [UserController::class, 'general_users'])->name('general_users');
Route::get('show_general_users', [UserController::class, 'show_general_users'])->name('show_general_users');
Route::get('show_driver_users', [UserController::class, 'show_driver_users'])->name('show_driver_users');
Route::get('show_worker_users', [UserController::class, 'show_worker_users'])->name('show_worker_users');
Route::post('add_user', [UserController::class, 'add_user'])->name('add_user');
Route::get('show_user', [UserController::class, 'show_user'])->name('show_user');
Route::post('edit_user', [UserController::class, 'edit_user'])->name('edit_user');
Route::post('update_user', [UserController::class, 'update_user'])->name('update_user');
Route::post('delete_user', [UserController::class, 'delete_user'])->name('delete_user');
Route::get('user_bookings', [UserController::class, 'user_bookings'])->name('user_bookings');
Route::get('user_visits', [UserController::class, 'user_visits'])->name('user_visits');
Route::get('user_feedback', [UserController::class, 'user_feedback'])->name('user_feedback');
Route::post('visits/update/{visit}', [UserController::class, 'update'])
     ->name('visits.update');
Route::post('feedback/worker', [UserController::class, 'store'])->name('feedback.worker.store');


Route::get('sms', [SmsController::class, 'index'])->name('sms');
Route::post('get_sms_status', [SmsController::class, 'get_sms_status'])->name('get_sms_status');
Route::match(['get', 'post'], 'add_status_sms', [SmsController::class, 'add_status_sms'])->name('add_status_sms');


Route::post('/register-ajax', [UserController::class, 'register'])
    ->name('register.ajax');
   Route::match(['get', 'post'], '/login-ajax', [UserController::class, 'loginAjax'])
    ->name('login.ajax');

Route::post('/logout-ajax', [UserController::class, 'logoutAjax'])->name('logout.ajax');
Route::get('user_profile/{id}', [UserController::class, 'user_profile'])->name('user_profile');
Route::get('debug/check-driver-worker', [UserController::class, 'checkDriverWorkerRecord'])->name('debug.check_driver_worker');
Route::post('debug/test-csrf', [UserController::class, 'testCsrf'])->name('debug.test_csrf');
//aCCOUNT

Route::get('account', [AccountController::class, 'index'])->name('account');
Route::post('add_account', [AccountController::class, 'add_account'])->name('add_account');
Route::post('add_balance', [AccountController::class, 'add_balance'])->name('add_balance');

Route::get('show_account', [AccountController::class, 'show_account'])->name('show_account');
Route::get('all_balance/{id}', [AccountController::class, 'all_balance'])->name('all_balance');
Route::get('show_balance', [AccountController::class, 'show_balance'])->name('show_balance');
Route::post('edit_account', [AccountController::class, 'edit_account'])->name('edit_account');
Route::post('update_account', [AccountController::class, 'update_account'])->name('update_account');
Route::post('delete_account', [AccountController::class, 'delete_account'])->name('delete_account');
Route::get('/detail/{id}', [AccountController::class, 'getAccountDetail']);
Route::get('download_expense_image/{filename}', [AccountController::class, 'downloadExpenseImage']);
Route::get('download_balance_image/{filename}', [AccountController::class, 'downloadBalanceImage']);


Route::get('expense', [ExpenseController::class, 'index'])->name('expense');
Route::post('add_expense', [ExpenseController::class, 'add_expense'])->name('add_expense');
Route::get('show_expense', [ExpenseController::class, 'show_expense'])->name('show_expense');
Route::post('edit_expense', [ExpenseController::class, 'edit_expense'])->name('edit_expense');
Route::post('update_expense', [ExpenseController::class, 'update_expense'])->name('update_expense');
Route::post('delete_expense', [ExpenseController::class, 'delete_expense'])->name('delete_expense_category');
Route::get('download_expense_image/{id}', [ExpenseController::class, 'download_expense_image'])->name('download_expense_image');


Route::get('expense_category', [ExpensecatController::class, 'index'])->name('expense_category');
Route::post('add_expense_category', [ExpensecatController::class, 'add_expense_category'])->name('add_expense_category');
Route::get('show_expense_category', [ExpensecatController::class, 'show_expense_category'])->name('show_expense_category');
Route::post('edit_expense_category', [ExpensecatController::class, 'edit_expense_category'])->name('edit_expense_category');
Route::post('update_expense_category', [ExpensecatController::class, 'update_expense_category'])->name('update_expense_category');
Route::post('delete_expense_category', [ExpensecatController::class, 'delete_expense_category'])->name('delete_expense_category');



Route::get('package', [PackageController::class, 'index'])->name('package');
Route::post('add_package', [PackageController::class, 'add_package'])->name('add_package');
Route::get('show_package', [PackageController::class, 'show_package'])->name('show_package');
Route::post('edit_package', [PackageController::class, 'edit_package'])->name('edit_package');
Route::post('update_package', [PackageController::class, 'update_package'])->name('update_package');
Route::post('delete_package', [PackageController::class, 'delete_package'])->name('delete_package');


Route::get('voucher', [VoucherController::class, 'index'])->name('voucher');
Route::post('add_voucher', [VoucherController::class, 'add_voucher'])->name('add_voucher');
Route::get('show_voucher', [VoucherController::class, 'show_voucher'])->name('show_voucher');
Route::post('edit_voucher', [VoucherController::class, 'edit_voucher'])->name('edit_voucher');
Route::post('update_voucher', [VoucherController::class, 'update_voucher'])->name('update_voucher');
Route::post('delete_voucher', [VoucherController::class, 'delete_voucher'])->name('delete_voucher');


Route::get('booking', [BookingController::class, 'index'])->name('booking');


Route::get('PaymentError', [BookingController::class, 'PaymentError'])->name('PaymentError');
Route::get('PaymentCompletew', [BookingController::class, 'PaymentCompletew'])->name('PaymentCompletew');
Route::get('PaymentSuccessAmwalw', [BookingController::class, 'PaymentSuccessAmwalw'])->name('PaymentSuccessAmwalw');
Route::post('add_payment', [BookingController::class, 'add_payment'])->name('add_payment');

//website_routes

Route::get('/', [WebController::class, 'index'])->name('index');
Route::get('worker_section', [WebController::class, 'worker_section'])->name('worker_section');
Route::get('worker_profile/{id}', [WebController::class, 'worker_profile'])->name('worker_profile');
Route::get('/worker-slides',  [WebController::class, 'worker_slides'])->name('worker.slides'); // AJAX
Route::get('service_page',  [WebController::class, 'service_page'])->name('service_page'); // AJAX
Route::get('service_section',  [WebController::class, 'service_section'])->name('service_section'); // AJAX
Route::post('/check-package-price', [WebController::class, 'check_package_price'])->name('check_package_price');

Route::get('about',  [WebController::class, 'about'])->name('about'); // AJAX
Route::get('contact',  [WebController::class, 'contact'])->name('contact'); // AJAX
Route::get('policy',  [WebController::class, 'policy'])->name('policy'); // AJAX
Route::match(['get', 'post'], '/save_location', [WebController::class, 'save_location'])
    ->name('save_location');
    Route::match(['get', 'post'], '/confirm_map', [WebController::class, 'confirm_map'])
     ->middleware(EnsureGuestToken::class);
    Route::get('/polygons/{id}', [WebController::class, 'getPolygon']);
Route::get('service', [ServiceController::class, 'index'])->name('service');
Route::post('add_service', [ServiceController::class, 'add_service'])->name('add_service');
Route::get('show_service', [ServiceController::class, 'show_service'])->name('show_service');
Route::post('edit_service', [ServiceController::class, 'edit_service'])->name('edit_service');
Route::post('update_service', [ServiceController::class, 'update_service'])->name('update_service');
Route::post('delete_service', [ServiceController::class, 'delete_service'])->name('delete_service');

Route::get('/packages/{package}/visits', [ServiceController::class, 'visits'])
     ->name('packages.visits');

Route::post('/generate-booking', [WorkerController::class, 'generate'])->name('generate.booking');
Route::post('/check-availability', [WorkerController::class, 'checkAvailability'])->name('check.availability');

Route::post('save_booking', [BookingController::class, 'save_booking'])->name('save_booking');
Route::get('checkout/{id}', [BookingController::class, 'checkout'])->name('checkout');
Route::post('voucher_apply', [BookingController::class, 'voucher_apply'])->name('voucher_apply');
Route::get('show_booking', [BookingController::class, 'show_booking'])->name('show_booking');
Route::get('all_bookings', [BookingController::class, 'all_bookings'])->name('all_bookings');
Route::post('cancel_booking', [BookingController::class, 'cancel_booking'])->name('cancel_booking');
Route::post('condition', [BookingController::class, 'condition'])->name('condition');
Route::post('edit_condition', [BookingController::class, 'edit_condition'])->name('edit_condition');

//visitroutes
Route::get('show_visit', [BookingController::class, 'show_visit'])->name('show_visit');
Route::get('all_visits', [BookingController::class, 'all_visits'])->name('all_visits');
Route::post('cancel_visits', [BookingController::class, 'cancel_visits'])->name('cancel_visits');
Route::post('add_visit2', [BookingController::class, 'add_visit2'])->name('add_visit2');
Route::post('edit_visit2', [BookingController::class, 'edit_visit2'])->name('edit_visit2');
Route::post('update_visit2', [BookingController::class, 'update_visit2'])->name('update_visit2');
Route::post('delete_visit', [BookingController::class, 'delete_visit'])->name('delete_visit');
Route::get('show_booking_visits', [BookingController::class, 'show_booking_visits'])->name('show_booking_visits');

Route::get('booking_profile/{id}', [BookingController::class, 'booking_profile'])->name('booking_profile');

Route::get('/locale/{locale}', function (string $locale) {
    $supported = ['en','ar'];
    if (! in_array($locale, $supported, true)) $locale = 'en';
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return back();
})->name('locale.switch');

Route::get('workers/{worker}/visits', [WorkerController::class, 'visitsPage'])->name('worker.visits.page');
Route::get('workers/{worker}/visits/today', [WorkerController::class, 'todayVisits'])->name('worker.visits.today');
Route::get('workers/{worker}/visits/this_week', [WorkerController::class, 'thisWeekVisits'])->name('worker.visits.this_week');
Route::get('workers/{worker}/visits/all', [WorkerController::class, 'allVisits'])->name('worker.visits.all');
Route::post('worker/visits/complete', [WorkerController::class, 'completeVisit'])->name('worker.visits.complete');
Route::get('drivers/{driver}/visits', [DriverController::class, 'visitsPage'])->name('driver.visits.page');
Route::get('drivers/{driver}/visits/today', [DriverController::class, 'todayVisitsdriver'])->name('driver.visits.today');
Route::get('drivers/{driver}/visits/this_week', [DriverController::class, 'thisWeekVisitsdriver'])->name('driver.visits.this_week');
Route::get('drivers/{driver}/visits/all', [DriverController::class, 'allVisitsdriver'])->name('driver.visits.all');
Route::post('driver/visits/complete', [DriverController::class, 'completeVisitdriver'])->name('driver.visits.complete');


// cronjobamwal
Route::get('AmwalCancelSession', [CronjobController::class, 'AmwalCancelSession'])->name('AmwalCancelSession');
Route::get('customer', [CustomerController::class, 'index'])->name('customer');
Route::get('show_customer', [CustomerController::class, 'show_customer'])->name('show_customer');