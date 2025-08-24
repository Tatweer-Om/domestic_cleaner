<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    // if (!Auth::check()) {
    //     return redirect()->route('login_page')->with('error', 'Please login first');
    // }

    // $user = Auth::user();

    // // Only allow user_type 1 and permission 7
    // $permissions = explode(',', $user->permissions ?? '');
    // if ($user->user_type != 1 || !in_array('7', $permissions)) {
    //     return redirect()->route('login_error')->with('error', 'Permission denied');
    // }


    return view('users.user');
}


    public function show_user()
    {

        $sno = 0;


            $view_authuser = User::all();

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
                        $user_type = 'Doctor';
                        break;
                    case 4:
                        $user_type = 'Employee';
                        break;
                    default:
                        $user_type = 'Unknown';
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

    public function add_user(Request $request)
    {

        // $user_id = Auth::id();
        // $data = User::where('id', $user_id)->first();
        // $username = $data->user_name;


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
        $user->permissions = implode(',', $request['permissions']);
        $user->password = Hash::make($request['password']);
        $user->user_image = $user_image;
        $user->user_type = $request['user_type'];
        $user->notes = $request['notes'];
        $user->added_by = 'system';
        $user->user_id = 1;
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
    ['id' => 'dashboard', 'value' => 1, 'name' => 'Dashboard', 'icon' => 'bi-speedometer2', 'color' => 'text-success'],
    ['id' => 'locations', 'value' => 2, 'name' => 'Locations', 'icon' => 'fas fa-map-marker-alt', 'color' => 'text-warning'],
    ['id' => 'drivers', 'value' => 3, 'name' => 'Drivers', 'icon' => 'fas fa-car-side', 'color' => 'text-info'],
    ['id' => 'workers', 'value' => 4, 'name' => 'Workers', 'icon' => 'fas fa-people-carry', 'color' => 'text-success'],
    ['id' => 'users', 'value' => 5, 'name' => 'Users', 'icon' => 'bi-person-fill-gear', 'color' => 'text-secondary'],
    ['id' => 'bookings', 'value' => 6, 'name' => 'Bookings', 'icon' => 'bi-calendar-check', 'color' => 'text-danger'],
    ['id' => 'reports', 'value' => 7, 'name' => 'Reports', 'icon' => 'bi-graph-up-arrow', 'color' => 'text-primary'],
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

        // $user_id = Auth::id();
        // $data = User::where('id', $user_id)->first();
        // $username = $data->user_name;
        // $branch = $data->branch_id;

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
        $user->added_by = 'system';
        $user->user_id = 1;
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

        // Get current user info
        // $currentUser = Auth::user();
        // $username = $currentUser->user_name;
        // $branch = $currentUser->branch_id;

        // Save history before deletion
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'users';
        $history->branch_id = 1;
        $history->function = 'delete';
        $history->function_status = 2;
        $history->record_id = $user->id;
        $history->previous_data = json_encode($previousData);
        $history->added_by = 'system';
        $history->save();

        $user->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }


  public function register(Request $request)
{
    // Validate (added phone because you reference it below)
    $validated = $request->validate([
        'user_name'     => ['required', 'string', 'max:255'],
        'phone'    => ['required', 'string', 'max:30', 'unique:users,user_phone'],
        'password' => ['required', 'string', 'min:4'],
    ]);

    try {
        [$user, $customer] = DB::transaction(function () use ($validated) {
            // ---- USER ----
            $user = new User();
            $user->user_name   = $validated['user_name'];
            $user->user_phone  = $validated['phone'];
            $user->user_type   = 10;
            $user->added_by    = $validated['user_name'];    // or auth()->id() if you prefer
            $user->permissions = 500;
            $user->password    = Hash::make($validated['password']);
            $user->save();

            // ---- CUSTOMER ----
            $customer = new Customer();
            $customer->user_id       = $user->id;
            $customer->customer_name = $validated['user_name'];
            $customer->phone_number  = $validated['phone'];
            $customer->added_by      = $user->id;       // or keep your name if that’s your convention
            $customer->save();

            return [$user, $customer];
        });

        return response()->json([
            'status'       => 'success',
            'message'      => 'Account created successfully.',
            'user_id'      => $user->id,
            'customer_id'  => $customer->id,
        ]);
    } catch (\Throwable $e) {
        report($e);
        return response()->json([
            'status'  => 'error',
            'message' => 'Could not create account. Please try again.',
        ], 500);
    }
}

    public function loginAjax(Request $request)
{
    $data = $request->validate([
        'identifier' => 'required|string',
        'password'   => 'required|string',
    ]);

    $id = $data['identifier'];
    $isPhone = preg_match('/^\+?\d+$/', $id);

    $user = \App\Models\User::where($isPhone ? 'user_phone' : 'user_name', $id)->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
        return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 422);
    }

    // 🔑 No remember flag:
    Auth::login($user);                 // same as Auth::login($user, false)
    $request->session()->regenerate();  // prevent session fixation

    return response()->json([
        'status'       => 'success',
        'redirect_url' => url('/'),
    ]);
}


public function logoutAjax(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'ok' => true,
        'redirect_url' => url('/')
    ]);
}
}
