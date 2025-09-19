<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visit;
use App\Models\Driver;
use App\Models\Worker;
use App\Models\Booking;
use App\Models\History;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Googlelinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    public function index()
{
    
            if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('5', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }


    return view('users.user');
}

   public function worker_users()
{
    
            if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('5', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }


    return view('users.worker_users');
}

   public function driver_users()
{
    
            if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('5', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }


    return view('users.driver_users');
}

   public function general_users()
{
    
            if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('5', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }


    return view('users.general_users');
}



    public function show_user()
    {

        $sno = 0;


$view_authuser = User::whereIn('user_type', [1, 2])->get();

        if (count($view_authuser) > 0) {
            foreach ($view_authuser as $value) {

$user_name = '<a class="patient-info ps-0" href="user_profile/' . $value->id . '">' . $value->user_name . '</a>';

                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_user_modal" onclick=edit("' . $value->id . '")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("' . $value->id . '")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $user_image = asset('images/dummy_images/no_image.jpg');
                if (!empty($value->user_image)) {
                    $user_image = asset('images/user_images') . "/" . $value->user_image;
                }
                $src = '<img src="' . $user_image . '" class-"patient-info ps-0" style="max-width:40px">';

                $user_type = "";

                switch ($value->user_type) {
                    case 1:
                        $user_type = 'Admin';
                        break;
                    case 2:
                        $user_type = 'User';
                        break;
                    case 3:
                        $user_type = trans('messages.doctor', [], session('locale'));
                        break;
                    case 4:
                        $user_type = trans('messages.employee', [], session('locale'));
                        break;
                    default:
                        $user_type = trans('messages.unknown', [], session('locale'));
                        break;
                }


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $src . '  ' . $user_name . '</span>',
                    '<span class="text-primary">' . $value->user_phone . '</span>',
                    '<span >' . $user_type . '</span>',

                    '<span >' . $value->added_by . '</span>',
                    '<span >' . $add_data . '</span>',
                    $modal
                );
            }
            $response = array();
            $response['success'] = true;
            $response['aaData'] = $json;
            echo json_encode($response);
        } else {
            $response = array();
            $response['sEcho'] = 0;
            $response['iTotalRecords'] = 0;
            $response['iTotalDisplayRecords'] = 0;
            $response['aaData'] = [];
            echo json_encode($response);
        }
    }


     public function show_driver_users()
    {

        $sno = 0;


$view_authuser = User::where('user_type', 3)->get();

        if (count($view_authuser) > 0) {
            foreach ($view_authuser as $value) {

$user_name = '<a class="patient-info ps-0" href="user_profile/' . $value->id . '">' . $value->user_name . '</a>';

                

                $add_data = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $user_image = asset('images/dummy_images/no_image.jpg');
                if (!empty($value->user_image)) {
                    $user_image = asset('images/user_images') . "/" . $value->user_image;
                }
                $src = '<img src="' . $user_image . '" class-"patient-info ps-0" style="max-width:40px">';

                $user_type = "";

                switch ($value->user_type) {
                    case 1:
                        $user_type = 'Admin';
                        break;
                    case 2:
                        $user_type = 'User';
                        break;
                    case 3:
                        $user_type = trans('messages.doctor', [], session('locale'));
                        break;
                    case 4:
                        $user_type = trans('messages.employee', [], session('locale'));
                        break;
                    default:
                        $user_type = trans('messages.unknown', [], session('locale'));
                        break;
                }


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $src . '  ' . $user_name . '</span>',
                    '<span class="text-primary">' . $value->user_phone . '</span>',
                    '<span >' . $user_type . '</span>',

                    '<span >' . $value->added_by . '</span>',
                    '<span >' . $add_data . '</span>',
                   
                );
            }
            $response = array();
            $response['success'] = true;
            $response['aaData'] = $json;
            echo json_encode($response);
        } else {
            $response = array();
            $response['sEcho'] = 0;
            $response['iTotalRecords'] = 0;
            $response['iTotalDisplayRecords'] = 0;
            $response['aaData'] = [];
            echo json_encode($response);
        }
    }


     public function show_worker_users()
    {

        $sno = 0;


$view_authuser = User::where('user_type', 4)->get();

        if (count($view_authuser) > 0) {
            foreach ($view_authuser as $value) {

$user_name = '<a class="patient-info ps-0" href="user_profile/' . $value->id . '">' . $value->user_name . '</a>';

                

                $add_data = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $user_image = asset('images/dummy_images/no_image.jpg');
                if (!empty($value->user_image)) {
                    $user_image = asset('images/user_images') . "/" . $value->user_image;
                }
                $src = '<img src="' . $user_image . '" class-"patient-info ps-0" style="max-width:40px">';

                $user_type = "";

                switch ($value->user_type) {
                    case 1:
                        $user_type = 'Admin';
                        break;
                    case 2:
                        $user_type = 'User';
                        break;
                    case 3:
                        $user_type = trans('messages.doctor', [], session('locale'));
                        break;
                    case 4:
                        $user_type = trans('messages.employee', [], session('locale'));
                        break;
                    default:
                        $user_type = trans('messages.unknown', [], session('locale'));
                        break;
                }


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $src . '  ' . $user_name . '</span>',
                    '<span class="text-primary">' . $value->user_phone . '</span>',
                    '<span >' . $user_type . '</span>',

                    '<span >' . $value->added_by . '</span>',
                    '<span >' . $add_data . '</span>',
                   
                );
            }
            $response = array();
            $response['success'] = true;
            $response['aaData'] = $json;
            echo json_encode($response);
        } else {
            $response = array();
            $response['sEcho'] = 0;
            $response['iTotalRecords'] = 0;
            $response['iTotalDisplayRecords'] = 0;
            $response['aaData'] = [];
            echo json_encode($response);
        }
    }


     public function show_general_users()
    {

        $sno = 0;


            $view_authuser = User::where('user_type', 10)->get();


        if (count($view_authuser) > 0) {
            foreach ($view_authuser as $value) {

$user_name = '<a class="patient-info ps-0" href="user_profile/' . $value->id . '">' . $value->user_name . '</a>';

                

                $add_data = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $user_image = asset('images/dummy_images/no_image.jpg');
                if (!empty($value->user_image)) {
                    $user_image = asset('images/user_images') . "/" . $value->user_image;
                }
                $src = '<img src="' . $user_image . '" class-"patient-info ps-0" style="max-width:40px">';

                $user_type = "";

                switch ($value->user_type) {
                    case 1:
                        $user_type = 'Admin';
                        break;
                    case 2:
                        $user_type = 'User';
                        break;
                    case 3:
                        $user_type = trans('messages.doctor', [], session('locale'));
                        break;
                    case 4:
                        $user_type = trans('messages.employee', [], session('locale'));
                        break;
                    default:
                        $user_type = trans('messages.unknown', [], session('locale'));
                        break;
                }


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $src . '  ' . $user_name . '</span>',
                    '<span class="text-primary">' . $value->user_phone . '</span>',
                    '<span >' . $user_type . '</span>',

                    '<span >' . $value->added_by . '</span>',
                    '<span >' . $add_data . '</span>',
                   
                );
            }
            $response = array();
            $response['success'] = true;
            $response['aaData'] = $json;
            echo json_encode($response);
        } else {
            $response = array();
            $response['sEcho'] = 0;
            $response['iTotalRecords'] = 0;
            $response['iTotalDisplayRecords'] = 0;
            $response['aaData'] = [];
            echo json_encode($response);
        }
    }
    public function add_user(Request $request)
    {

        $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $username = $data->user_name;


        $user_image = "";

        if ($request->hasFile('user_image')) {
            $folderPath = public_path('images/user_images');

            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $user_image = time() . '.' . $request->file('user_image')->extension();
            $request->file('user_image')->move($folderPath, $user_image);
        }

        $user = new User();

        $user->user_name = $request['user_name'];
        $user->user_email = $request['email'];
        $user->user_phone = $request['phone'];
$permissions = $request->input('permissions', []); // default empty array
if (!is_array($permissions)) {
    $permissions = [$permissions]; // convert string to array
}
$user->permissions = implode(',', $permissions);
        $user->password = Hash::make($request['password']);
        $user->user_image = $user_image;
        $user->user_type = $request['user_type'];
        $user->notes = $request['notes'];
        $user->added_by = $username;
        $user->user_id = $user_id;
        $user->save();
        return response()->json(['user_id' => $user->id]);
    }

    public function edit_user(Request $request)
    {
        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();

        if (!$user_data) {
            return response()->json([
                trans('messages.error_lang', [], session('locale')) => trans('messages.user_not_found', [], session('locale'))
            ], 404);
        }

        $permit = explode(',', $user_data->permissions);

        // Define checkbox info including icons and colors
    $checkboxValues = [
    ['id' => 'dashboard', 'value' => 1, 'name' => 'messages.permissions_dashboard', 'icon' => 'bi-speedometer2', 'color' => 'text-success'],
    ['id' => 'locations', 'value' => 2, 'name' => 'messages.permissions_locations', 'icon' => 'fas fa-map-marker-alt', 'color' => 'text-warning'],
    ['id' => 'drivers', 'value' => 3, 'name' => 'messages.permissions_drivers', 'icon' => 'fas fa-car-side', 'color' => 'text-info'],
    ['id' => 'workers', 'value' => 4, 'name' => 'messages.permissions_workers', 'icon' => 'fas fa-people-carry', 'color' => 'text-success'],
    ['id' => 'users', 'value' => 5, 'name' => 'messages.permissions_users', 'icon' => 'bi-person-fill-gear', 'color' => 'text-secondary'],
    ['id' => 'bookings', 'value' => 6, 'name' => 'messages.permissions_bookings', 'icon' => 'bi-calendar-check', 'color' => 'text-danger'],
    ['id' => 'reports', 'value' => 7, 'name' => 'messages.permissions_reports', 'icon' => 'bi-graph-up-arrow', 'color' => 'text-primary'],
        ['id' => 'expense', 'value' => 8, 'name' => 'messages.permissions_expense', 'icon' => 'bi-graph-up-arrow', 'color' => 'text-primary'],
        ['id' => 'sms', 'value' => 9, 'name' => 'messages.permissions_sms', 'icon' => 'bi-graph-up-arrow', 'color' => 'text-primary'],
        ['id' => 'account', 'value' => 10, 'name' => 'messages.permissions_account', 'icon' => 'bi-graph-up-arrow', 'color' => 'text-primary'],
        ['id' => 'customer', 'value' => 11, 'name' => 'messages.permissions_customer', 'icon' => 'bi-graph-up-arrow', 'color' => 'text-primary'],

];


        $checked_html = '<div class="container mt-3" id="checked_html">
            <!-- Select All -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="selectAll">
                <label class="form-check-label fw-bold fs-6" for="selectAll">
                    <i class="bi bi-check2-square me-1"></i> All Permissions
                </label>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 g-1">';

        foreach ($checkboxValues as $value) {
            $checked = in_array($value['value'], $permit) ? "checked='checked'" : "";

            $checked_html .= '<div class="col">
                <div class="form-check d-flex align-items-center small">
                    <input type="checkbox" class="form-check-input me-2 permission-checkbox" name="permissions[]" value="' . $value['value'] . '" id="' . $value['id'] . '" ' . $checked . '>
                    <label class="form-check-label" for="' . $value['id'] . '"><i class="bi ' . $value['icon'] . ' me-1 ' . $value['color'] . '"></i> ' . trans($value['name'], [], session('locale')) . '</label>
                </div>
            </div>';
        }

        $checked_html .= '</div></div>'; // Close row and container

        $user_image = $user_data->user_image
            ? asset('images/user_images/' . $user_data->user_image)
            : asset('images/dummy_images/cover-image-icon.png');

        $data = [
            'user_id' => $user_data->id,
            'user_name' => $user_data->user_name,
            'user_email' => $user_data->user_email,
            'user_phone' => $user_data->user_phone,
            'permissions' => $user_data->permissions,
            'password' => $user_data->password,
            'user_type' => $user_data->user_type,
            'user_image' => $user_image,
            'notes' => $user_data->notes,
            'checked_html' => $checked_html,
        ];

        return response()->json($data);
    }


    public function update_user(Request $request)
    {


        $user_id = $request->input('user_id');

        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.authuser_not_found', [], session('locale'))], 404);
        }

        $previousData = $user->only([
            'user_name',
            'user_email',
            'user_phone',
            'permissions',
            'user_image',
            'user_type',
            'notes',
            'user_id',
            'added_by',
            'created_at'
        ]);

        $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $username = $data->user_name;
        $branch = $data->branch_id;

        $user_image = $user->user_image;

        if ($request->hasFile('user_image')) {
            $oldImagePath = public_path('images/user_images/' . $user->user_image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $folderPath = public_path('images/user_images');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $user_image = time() . '.' . $request->file('user_image')->extension();
            $request->file('user_image')->move($folderPath, $user_image);
        }

        $user->user_name = $request['user_name'];
        $user->user_email = $request['email'];
        $user->user_phone = $request['phone'];
$user->permissions = is_array($request['permissions'])
    ? implode(',', $request['permissions'])
    : $request['permissions'];
        $user->password = Hash::make($request['password']);
        $user->user_image = $user_image;
        $user->user_type = $request['user_type'];
        $user->notes = $request['notes'];
        $user->added_by = $username;
        $user->user_id =$user_id;
        $user->save();

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'users';
        $history->function = 'update';
        $history->function_status = 1;


        $history->branch_id =1;
        $history->record_id = $user->id;
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($user->only([
            'user_name',
            'user_email',
            'user_phone',
            'permissions',
            'user_image',
            'user_type',
            'notes',
            'user_id',
            'added_by'
        ]));
        $history->added_by = 'system';

        $history->save();
        return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))]);
    }

    public function delete_user(Request $request)
    {
        $user_id = $request->input('id');
        $user = User::where('id', $user_id)->first();

        if (!$user) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.user_not_found', [], session('locale'))], 404);
        }

        // Store previous data before deletion
        $previousData = $user->only([
            'user_name',
            'user_email',
            'user_phone',
            'permissions',
            'user_image',
            'user_type',
            'notes',
            'user_id',
            'added_by',
            'created_at'
        ]);

        
        $currentUser = Auth::user();
        $username = $currentUser->user_name;
        $branch = $currentUser->branch_id;

        // Save history before deletion
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'users';
        $history->branch_id = 1;
        $history->function = 'delete';
        $history->function_status = 2;
        $history->record_id = $user->id;
        $history->previous_data = json_encode($previousData);
        $history->added_by = $username;
        $history->save();

        $user->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }




public function register(Request $request)
    {
        // Validate request, including the hidden form_index field
        $validated = $request->validate([
            'user_name'     => ['required', 'string', 'max:255'],
            'phone'         => ['required', 'string', 'max:30', 'unique:users,user_phone'],
            'password'      => ['required', 'string', 'min:4'],
            'form_index'    => ['required', 'in:1,2'], // Ensure form_index is either 1 or 2
        ]);

        try {
            [$user, $customer] = DB::transaction(function () use ($validated) {
                // ---- USER ----
                $user = new User();
                $user->user_name   = $validated['user_name'];
                $user->user_phone  = $validated['phone'];
                $user->user_type   = 10;
                $user->added_by    = $validated['user_name']; // or auth()->id() if preferred
                $user->permissions = 500;
                $user->password    = Hash::make($validated['password']);
                $user->save();

                // ---- CUSTOMER ----
                $customer = new Customer();
                $customer->user_id       = $user->id;
                $customer->customer_name = $validated['user_name'];
                $customer->phone_number  = $validated['phone'];
                $customer->added_by      = $user->id; // or keep as name if that's your convention
                $customer->save();

                return [$user, $customer];
            });

                $guestToken = $request->cookie('guest_token');
        if ($guestToken) {
            Googlelinks::where('guest_token', $guestToken)
                ->update([
                    'user_id'     => $user->id,
                    'guest_token' => null,
                ]);

            Cookie::queue(Cookie::forget('guest_token'));
        }

            // Handle login for form_index = 2
            if ($validated['form_index'] == '2') {
                // Attempt to log the user in using the phone and password
                if (Auth::attempt(['user_phone' => $validated['phone'], 'password' => $validated['password']])) {
                    // Regenerate session to prevent session fixation
                    $request->session()->regenerate();

                    return response()->json([
                        'status'       => 'success',
                        'message'      => trans('messages.account_created_logged_in', [], session('locale')),
                        'user_id'      => $user->id,
                        'customer_id'  => $customer->id,
                        'logged_in'    => true,
                    ]);
                } else {
                    // If login fails, still return success for account creation
                    return response()->json([
                        'status'       => 'success',
                        'message'      => 'Account created but login failed. Please try logging in manually.',
                        'user_id'      => $user->id,
                        'customer_id'  => $customer->id,
                        'logged_in'    => false,
                    ], 200);
                }
            }

            // Default response for form_index = 1
            return response()->json([
                'status'       => 'success',
                'message'      => trans('messages.account_created_successfully', [], session('locale')),
                'user_id'      => $user->id,
                'customer_id'  => $customer->id,
                'logged_in'    => false,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'status'  => 'error',
                'message' => 'Could not create account. Please try again.',
            ], 500);
        }

        $guestToken = $request->cookie('guest_token');
        if ($guestToken) {
            Googlelinks::where('guest_token', $guestToken)
                ->update([
                    'user_id'     => $user->id,
                    'guest_token' => null,
                ]);

            Cookie::queue(Cookie::forget('guest_token'));
        }

    }

    public function loginAjax(Request $request)
    {

        // Validate request, including the hidden form_1 field
        $data = $request->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string',
            'form_1'     => ['required', 'in:1,2'], // Ensure form_1 is either 1 or 2
        ]);

        $id = $data['identifier'];
        $isPhone = preg_match('/^\+?\d+$/', $id);

        $user = \App\Models\User::where($isPhone ? 'user_phone' : 'user_name', $id)->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['status' => 'error', 'message' => trans('messages.invalid_credentials', [], session('locale'))], 422);
        }

        // Perform login
        Auth::login($user); // No remember flag
        $request->session()->regenerate(); // Prevent session fixation
   $guestToken = $request->cookie('guest_token');
    if ($guestToken) {
        Googlelinks::where('guest_token', $guestToken)
            ->update([
                'user_id'     => $user->id,
                'guest_token' => null,
            ]);

        Cookie::queue(Cookie::forget('guest_token'));
    }
        // Determine redirect URL based on form_1
        $redirectUrl = $data['form_1'] == '1' ? '/' : 'checkout/booking_no';

        return response()->json([
            'status'       => 'success',
            'redirect_url' => url($redirectUrl),
        ]);

        
    }


public function logoutAjax(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'ok' => true
    ]);
}


public function user_profile($id)
{
    $user = User::select('id', 'user_name', 'user_phone')->findOrFail($id);

    $customerId = Customer::where('phone_number', $user->user_phone)->value('id');

    $totalBookings = Booking::where('user_id', $id)->count();
    $bookingCount  = $customerId ? Booking::where('customer_id', $customerId)->count() : 0;
    $visitCount    = $customerId ? Visit::where('customer_id', $customerId)->count() : 0;

    return view('web_pages.user_profile', compact(
        'user',
        'totalBookings',
        'bookingCount',
        'visitCount'
    ));
}


public function user_bookings(Request $request)
{
    $userId = $request->get('user_id');

    $user = User::select('id', 'user_name', 'user_phone')->findOrFail($userId);
    $customerId = Customer::where('phone_number', $user->user_phone)->value('id');

    $bookings = Booking::with([
            // pull name + the two prices from Package
            'package:id,package_name,package_price_4,package_price_5',
            // pull location name too
            'location:id,location_name',
        ])
        ->where('customer_id', $customerId)
        ->get([
            'id',
            'booking_no',
            'customer_id',
            'package_id',
            'location_id',
            'status',
            'start_date',
            'visits_count',
            'duration',      // adjust if your column is different
        ]);

    return response()->json([
        'ok'       => true,
        'bookings' => $bookings,
        'user_id'  => $userId,
    ]);
}


    // --- Visits ---
 public function user_visits(Request $request)
{
    $userId = $request->get('user_id');

    $user = User::select('id', 'user_name', 'user_phone')->findOrFail($userId);
    $customerId = Customer::where('phone_number', $user->user_phone)->value('id');

    $visits = Visit::with([
            'booking:id,booking_no',
            'worker:id,worker_name',
        ])
        ->where('customer_id', $customerId)
        ->get([
            'id',
            'booking_id',
            'customer_id',
            'visit_date',
            'status',      // 1=pending, 2=completed, 3=cancelled (assumption)
            'worker_id',
            'duration',
            'shift',       // e.g., 'Morning' / 'Evening'
        ]);

    return response()->json([
        'ok'     => true,
        'type'   => 'visits',
        'user_id'=> $userId,
        'visits' => $visits,
    ]);
}


    // --- Feedback ---
 public function user_feedback(Request $request)
{
    $userId = $request->get('user_id');

    $user = User::select('id', 'user_name', 'user_phone')->findOrFail($userId);
    $customerId = Customer::where('phone_number', $user->user_phone)->value('id');

    // Pull active (status=1) bookings with worker name
    $bookings = Booking::with([
            'worker:id,worker_name',
        ])
        ->where('customer_id', $customerId)
        ->where('status', 1)
        ->get([
            'id',
            'booking_no',
            'worker_id',
            'start_date',   // keep if you also want to show date
        ]);

    return response()->json([
        'ok'       => true,
        'type'     => 'feedback',
        'user_id'  => $userId,
        'bookings' => $bookings,
    ]);
}



    public function update(Request $request, \App\Models\Visit $visit)
{
    $data = $request->validate([
        'visit_date' => ['required','date'],
        'duration'   => ['required','integer','min:1'],
        'shift'      => ['required','in:Morning,Evening'], // adjust if you use codes (1/2)
    ]);

    // Save
    $visit->visit_date = $data['visit_date'];
    $visit->duration   = $data['duration'];
    $visit->shift      = $data['shift'];
    $visit->save();

    return response()->json([
        'ok'      => true,
        'message' => trans('messages.visit_updated', [], session('locale')) ?? 'Visit updated successfully',
        'visit'   => [
            'id'         => $visit->id,
            'visit_date' => $visit->visit_date,
            'duration'   => $visit->duration,
            'shift'      => $visit->shift,
        ],
    ]);
}


public function store(Request $request)
{
    // Validate incoming request
    $data = $request->validate([
        'user_id'    => ['required','integer','exists:users,id'], // this is the profile user id
        'worker_id'  => ['required','integer','exists:workers,id'],
        'booking_id' => ['required','integer','exists:bookings,id'],
        'rating'     => ['required','integer','between:1,5'],
        'notes'      => ['nullable','string','max:2000'],
    ]);

    // Lookup the user
    $user = User::select('id','user_name','user_phone')->findOrFail($data['user_id']);

    // Map to the actual customer_id
    $customerId = Customer::where('phone_number', $user->user_phone)->value('id');

    // Replace user_id with customer_id for Feedback
    unset($data['user_id']);
    $data['customer_id'] = $customerId;

    // Create feedback
    $feedback = Feedback::create($data);

    return response()->json([
        'ok'      => true,
        'message' => trans('messages.feedback_saved_successfully', [], session('locale')),
        'feedback'=> $feedback,
    ]);
}




   public function login_page()
    {
        return view('pages.login');
    }






public function login(Request $request)
{
    // Validate the input first



    $baseInput = $request->input('user_name');
    $password  = $request->input('password');

    // Find user by username or email
    $user = User::where('user_name', $baseInput)
        ->orWhere('user_phone', $baseInput)
        ->first();

    if ($user) {
        if (Hash::check($password, $user->password)) {
            Auth::login($user);

            // Check user_type restrictions
            if ($user->user_type == 3) {
                $driver = Driver::where('user_id', $user->id)->first();
                
                if (!$driver) {
                    return response()->json([
                        'status'  => 2,
                        'message' => 'Driver profile not found. Please contact administrator.',
                    ]);
                }
                
                return response()->json([
                    'status'  => 4,
                    'id'      => $driver->id,
                    'message' => 'Login successful',
                ]);
            }

            if ($user->user_type == 4) {
                $worker = Worker::where('user_id', $user->id)->first();
                
                if (!$worker) {
                    return response()->json([
                        'status'  => 2,
                        'message' => 'Worker profile not found. Please contact administrator.',
                    ]);
                }
                
                return response()->json([
                    'status'  => 5,
                    'id'      => $worker->id,
                    'message' => 'Login successful',
                ]);
            }


            if ($user->user_type == 2) {
                return response()->json([
                        'status'   => 1,
                'message'  => 'Login successful',
                'redirect' => route('home'),
                ]);
            }

             if ($user->user_type == 10) {
                return response()->json([
                        'status'   => 1,
                'message'  => 'Login successful',
                'redirect' => route('/'),
                ]);
            }

            // Default success for user_type 1
            return response()->json([
                'status'   => 1,
                'message'  => 'Login successful',
                'redirect' => route('home'),
            ]);
        }

        return response()->json([
            'status'  => 2,
            'message' => trans('messages.password_incorrect', [], session('locale')),
        ], 401);
    }

    return response()->json([
        'status'  => 2,
        'message' => trans('messages.user_not_found', [], session('locale')),
    ], 404);
}


 public function logout(Request $request)
{
    if (Auth::check()) {
        Auth::logout();
        return redirect('login_page')->with('message', trans('messages.logged_out_successfully', [], session('locale')));
    }

    return redirect('login_page')->with('error', trans('messages.no_active_session', [], session('locale')));
}

// Debug method to check driver/worker records
public function checkDriverWorkerRecord(Request $request)
{
    $userId = $request->get('user_id');
    
    if (!$userId) {
        return response()->json(['error' => 'User ID required']);
    }
    
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['error' => 'User not found']);
    }
    
    $driver = Driver::where('user_id', $userId)->first();
    $worker = Worker::where('user_id', $userId)->first();
    
    return response()->json([
        'user_id' => $userId,
        'user_type' => $user->user_type,
        'user_name' => $user->user_name,
        'driver_exists' => $driver ? true : false,
        'driver_id' => $driver ? $driver->id : null,
        'driver_data' => $driver,
        'worker_exists' => $worker ? true : false,
        'worker_id' => $worker ? $worker->id : null,
        'worker_data' => $worker,
    ]);
}

// Debug method to test CSRF token
public function testCsrf(Request $request)
{
    return response()->json([
        'success' => true,
        'message' => 'CSRF token is working correctly',
        'request_data' => $request->all(),
        'csrf_token' => csrf_token(),
        'session_token' => session()->token(),
    ]);
}
}
