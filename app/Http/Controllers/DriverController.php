<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Driver;
use App\Models\History;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
  public function index()
{
    $locations = Location::select('id', 'location_name')->get();
    return view('drivers.drivers', compact('locations'));
}

public function show_driver()
{
    $sno = 0;
    $drivers = Driver::all(); // No user-type filtering

    if ($drivers->count() > 0) {
        foreach ($drivers as $driver) {
            $employee_id = $driver->id;



            $driver_name = '<a class="driver-info ps-0" href="driver_profile/' . $driver->id . '">' . $driver->driver_name . '</a>';
            $modal = '<a href="javascript:void(0);" class="me-3 edit-driver" data-bs-toggle="modal" data-bs-target="#add_driver_modal" onclick="edit(' . $driver->id . ')">
                <i class="fa fa-pencil fs-18 text-success"></i>
              </a>
              <a href="javascript:void(0);" onclick="del(' . $driver->id . ')">
                <i class="fa fa-trash fs-18 text-danger"></i>
              </a>
            ';

            $add_data = Carbon::parse($driver->created_at)->format('d-m-Y (h:i a)');
            $driver_image = $driver->driver_image ? asset('images/driver_images/' . $driver->driver_image) : asset('images/dummy_images/no_image.jpg');
            $src = '<img src="' . $driver_image . '" class="driver-info ps-0" style="max-width:40px">';
            $location= Location::where('id', $driver->location_id)->value('location_name');
            $shiftLabel = '';

            if ($driver->shift == 1) {
                $shiftLabel = trans('messages.morning', [], session('locale'));
            } elseif ($driver->shift == 2) {
                $shiftLabel = trans('messages.evening', [], session('locale'));
            } elseif ($driver->shift == 3) {
                $shiftLabel = trans('messages.both', [], session('locale'));
            } else {
                $shiftLabel = '-';
            }

            $sno++;
            $json[] = array(
                '<span class="driver-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2">' . $src . ' ' . $driver_name . '</span>',
                '<span class="text-primary">' . $driver->phone . '</span>',

                '<span class="text-primary">' .$location . '</span>',
                '<span class="text-primary">' .$shiftLabel . '</span>',

                '<span>' . $driver->added_by . '</span>',
                '<span>' . $add_data . '</span>',
                $modal
            );
        }

        return response()->json(['success' => true, 'aaData' => $json]);
    }

    return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
}

public function add_driver(Request $request)
{
    $driver_image = "";
    if ($request->hasFile('driver_image')) {
        $folderPath = public_path('images/driver_images');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $driver_image = time() . '.' . $request->file('driver_image')->extension();
        $request->file('driver_image')->move($folderPath, $driver_image);
    }

    $driver = new Driver();
    $driver->driver_name = $request->input('driver_name');
    $driver->phone = $request->input('phone');

    $driver->driver_user_id = $request->input('driver_user_id');
        $driver->shift = $request->input('shift');
        $driver->location_id = $request->input('location_id');

    $driver->driver_image = $driver_image;
    $driver->notes = $request->input('notes');

    // Auto set user_id and added_by for branch 1
    $driver->user_id = 1;
    $driver->added_by =  'system';

    $driver->save();

    return response()->json(['driver_id' => $driver->id]);
}

public function edit_driver(Request $request)
{
    $driver = Driver::find($request->input('id'));
    if (!$driver) {
        return response()->json(['error' => 'Driver not found'], 404);
    }

    $driver_image = $driver->driver_image ? asset('images/driver_images/' . $driver->driver_image) : asset('images/dummy_images/no_image.jpg');

    return response()->json([
        'driver_id' => $driver->id,
        'driver_name' => $driver->driver_name,
        'driver_user_id' => $driver->driver_user_id,
        'shift' => $driver->shift,
        'location_id' => $driver->location_id,
        'phone' => $driver->phone,
        'driver_image' => $driver_image,
        'notes' => $driver->notes,
    ]);
}

public function update_driver(Request $request)
{
    $driver = Driver::find($request->input('driver_id'));
    if (!$driver) {
        return response()->json(['error' => 'Driver not found'], 404);
    }

    $previousData = $driver->only([
        'driver_name',  'phone',
        'driver_image', 'notes', 'user_id', 'added_by', 'created_at'
    ]);

    if ($request->hasFile('driver_image')) {
        $oldImagePath = public_path('images/driver_images/' . $driver->driver_image);
        if (File::exists($oldImagePath) && !empty($driver->driver_image)) {
            File::delete($oldImagePath);
        }
        $folderPath = public_path('images/driver_images');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $driver_image = time() . '.' . $request->file('driver_image')->extension();
        $request->file('driver_image')->move($folderPath, $driver_image);
    } else {
        $driver_image = $driver->driver_image;
    }

    $driver->driver_name = $request->input('driver_name');
    $driver->phone = $request->input('phone');
    $driver->driver_user_id = $request->input('driver_user_id');
    $driver->shift = $request->input('shift');
    $driver->location_id = $request->input('location_id');
    $driver->driver_image = $driver_image;
    $driver->notes = $request->input('notes');

    // Auto set user_id and added_by for branch 1
    $driver->user_id = 1;
    $driver->added_by = 'system';

    $driver->save();

    $updatedData = $driver->only([
        'driver_name',  'phone', 'driver_image',
        'notes',  'user_id', 'added_by'
    ]);

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'drivers';
    $history->function = 'update';
    $history->function_status = 1;
    $history->branch_id = 1;
    $history->record_id = $driver->id;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($updatedData);
    $history->added_by = 'admin';
    $history->save();

    return response()->json(['success' => 'Driver updated successfully']);
}

public function delete_driver(Request $request)
{
    $driver = Driver::find($request->input('id'));
    if (!$driver) {
        return response()->json(['error' => 'Driver not found'], 404);
    }

    $previousData = $driver->only([
        'driver_name', 'user_name', 'phone', 'email', 'driver_image',
        'branch_id',  'notes',
        'user_id', 'added_by', 'created_at'
    ]);

    if (!empty($driver->driver_image)) {
        $imagePath = public_path('images/driver_images/' . $driver->driver_image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'drivers';
    $history->branch_id = 1;
    $history->function = 'delete';
    $history->function_status = 2;
    $history->record_id = $driver->id;
    $history->previous_data = json_encode($previousData);
    $history->added_by = 'admin';
    $history->save();

    $driver->delete();

    return response()->json(['success' => 'Driver deleted successfully']);
}



}
