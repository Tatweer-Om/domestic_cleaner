<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index()
    {

        
            if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('2', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }
        return view('locations.location');
    }

    public function show_location()
    {

        $sno = 0;

        $view_authlocation = Location::all();
        if (count($view_authlocation) > 0) {
            foreach ($view_authlocation as $value) {

                $location_name = '<a class-"patient-info ps-0" href="javascript:void(0);">' . $value->location_name . '</a>';

                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_location_modal" onclick=edit("' . $value->id . '")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("' . $value->id . '")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');




                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $location_name . '</span>',
                    '<span class="text-nowrap ms-2">' . $value->location_fare . '</span>',
                (
    $value->driver_availabe == 1
        ? '<span class="badge bg-success px-2 py-1"><i class="fas fa-user-check me-1"></i> Available</span>'
        : '<span class="badge bg-danger px-2 py-1"><i class="fas fa-user-times me-1"></i> Unavailable</span>'
)
,

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

 public function add_location(Request $request)
{
    // Validate input
    $request->validate([
        'location_name' => 'required|string|max:255',
        'location_fare' => 'required|numeric',
        'driver_status' => 'required|boolean',
        'notes' => 'nullable|string',
        'location_polygon' => 'nullable|json', // Ensure it's valid JSON
    ]);

    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    if (!$data) {
        return response()->json(['error' => 'User not found'], 404);
    }
    $user_name = $data->user_name;

    $location = new Location();
    $location->location_name = $request['location_name'];
    $location->location_fare = $request['location_fare'];
    $location->driver_availabe = $request['driver_status'];
    $location->notes = $request['notes'];
    $location->added_by = $user_name;
    $location->user_id = $user_id;

    if ($request->has('location_polygon')) {
        $polygon = json_decode($request['location_polygon'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid polygon JSON: ' . json_last_error_msg()], 422);
        }

        if (!is_array($polygon) || empty($polygon)) {
            return response()->json(['error' => 'Polygon must be a non-empty array'], 422);
        }

        // Assign polygon (cast will handle JSON serialization)
        $location->polygon = $polygon;

        // Flatten nested arrays for bounding box calculation
        $flatCoordinates = [];
        foreach ($polygon as $poly) {
            if (is_array($poly)) {
                $flatCoordinates = array_merge($flatCoordinates, $poly);
            }
        }

        if (empty($flatCoordinates)) {
            return response()->json(['error' => 'No valid coordinates found in polygon'], 422);
        }

        // Calculate bounding box
        $lats = array_column($flatCoordinates, 0); // All latitudes
        $lons = array_column($flatCoordinates, 1); // All longitudes

        if (empty($lats) || empty($lons)) {
            return response()->json(['error' => 'Invalid coordinate format in polygon'], 422);
        }

        $location->lat_min = min($lats);
        $location->lat_max = max($lats);
        $location->lon_min = min($lons);
        $location->lon_max = max($lons);
    }

    $location->save();
    return response()->json(['location_id' => $location->id]);
}

public function edit_location(Request $request)
{
    $location_id = $request->input('id');
    $location_data = Location::where('id', $location_id)->first();

    if (!$location_data) {
        return response()->json(['error' => trans('messages.location_not_found', [], session('locale'))], 404);
    }

    // Map driver_availabe to driver_status (1: available, 2: unavailable, 0: unknown)
    $status = $location_data->driver_availabe ?? 0;
    if ($status == 1) {
        $status = 1; // Available
    } elseif ($status == 2) {
        $status = 2; // Unavailable
    } else {
        $status = 0; // Unknown
    }

    $data = [
        'location_id' => $location_data->id,
        'location_name' => $location_data->location_name,
        'location_fare' => $location_data->location_fare,
        'driver_status' => $status,
        'notes' => $location_data->notes,
        'location_polygon' => $location_data->polygon ?? '',
    ];

    return response()->json($data);
}

public function update_location(Request $request)
{
    $location_id = $request->input('location_id');
    $user_id = Auth::id();
    $user = User::where('id', $user_id)->first();
    $user_name = $user->user_name;

    $location = Location::where('id', $location_id)->first();

    if (!$location) {
        return response()->json(['error' => trans('messages.location_not_found', [], session('locale'))], 404);
    }

    $previousData = $location->only(['location_name', 'location_fare', 'driver_availabe', 'notes', 'added_by', 'user_id', 'polygon']);

    // Update fields
    $location->location_name = $request->input('location_name');
    $location->location_fare = $request->input('location_fare');
    $location->driver_availabe = $request->input('driver_status');
    $location->notes = $request->input('notes');
    $location->added_by = $user_name;
    $location->user_id = $user_id;
    if ($request->has('location_polygon')) {
        $location->polygon = $request->input('location_polygon');
    }
    $location->save();

    // Log history
    $history = new History();
    $history->user_id = $user_id;
    $history->table_name = 'locations'; // Fixed typo: 'locationes' to 'locations'
    $history->function = 'update';
    $history->function_status = 1;
    $history->record_id = $location->id;
    $history->branch_id = 1;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($location->only([
        'location_name',
        'location_fare',
        'driver_availabe',
        'notes',
        'added_by',
        'user_id',
        'polygon'
    ]));
    $history->added_by = $user_name;
    $history->save();

    return response()->json(['message' => trans('messages.user_update_lang', [], session('locale'))]);
}
    public function delete_location(Request $request)
    {


        // $user_id = Auth::id();
        // $user = User::where('id', $user_id)->first();
        // $user_name = $user->user_name;
        $location_id = $request->input('id');
        $location = Location::where('id', $location_id)->first();

        if (!$location) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.location_not_found', [], session('locale'))], 404);
        }

        $previousData = $location->only([
            'location_name',
            'location_fare',
            'notes',
            'added_by',
            'user_id',
            'created_at'
        ]);

        // $currentUser = Auth::user();
        // $username = $currentUser->user_name;
        $location_id = $location->id;

        $history = new History();
        $history->user_id = 1;
        $history->table_name = 'locationes';
        $history->function = 'delete';
        $history->function_status = 2;
        $history->record_id = $location->id;
        $history->branch_id = 1;

        $history->previous_data = json_encode($previousData);

        $history->added_by = 'system';
        $history->save();
        $location->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }
}
