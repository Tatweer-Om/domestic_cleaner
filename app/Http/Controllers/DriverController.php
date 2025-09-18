<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Driver;
use App\Models\History;
use App\Models\Location;
use App\Models\User;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {

        if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('3', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }
        $locations = Location::select('id', 'location_name')->get();
        $users= User::where('user_type', 3)->get();
        return view('drivers.drivers', compact('locations', 'users'));
    }

    public function show_driver()
    {
        $sno = 0;
        $drivers = Driver::all(); // No user-type filtering

        if ($drivers->count() > 0) {
            foreach ($drivers as $driver) {
                $employee_id = $driver->id;



                $driver_name = '<a class="driver-info ps-0" href="driver_page/' . $driver->id . '">' . $driver->driver_name . '</a>';
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
                $location = Location::where('id', $driver->location_id)->value('location_name');
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
                $whatsappStatus = ($driver->whatsapp_notification == 1)
                    ? '<span class="text-success">WhatsApp Enabled</span>'
                    : '<span class="text-danger">WhatsApp Disabled</span>';

                $json[] = array(
                    '<span class="driver-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $src . ' ' . $driver_name . '</span>',
                    '<span class="text-primary">' . $driver->phone . '</span>',

                    '<span class="text-primary">' . $location . '</span>',
                    '<span class="text-primary">' . $shiftLabel . '</span>',
                    $whatsappStatus,


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
        $driver->whatsapp_notification = $request->has('enable_whatsapp') ? 1 : 2;
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
            'driver_name',
            'phone',
            'driver_image',
            'notes',
            'user_id',
            'added_by',
            'created_at'
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
        $driver->whatsapp_notification = $request->has('enable_whatsapp') ? 1 : 2;
        $driver->shift = $request->input('shift');
        $driver->location_id = $request->input('location_id');
        $driver->driver_image = $driver_image;
        $driver->notes = $request->input('notes');

        // Auto set user_id and added_by for branch 1
        $driver->user_id = 1;
        $driver->added_by = 'system';

        $driver->save();

        $updatedData = $driver->only([
            'driver_name',
            'phone',
            'driver_image',
            'notes',
            'user_id',
            'added_by'
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
            'driver_name',
            'user_name',
            'phone',
            'email',
            'driver_image',
            'branch_id',
            'notes',
            'user_id',
            'added_by',
            'created_at'
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


   public function driver_page($id)
{
    $driver = Driver::where('id', $id)->first();
    
    if (!$driver) {
        return redirect()->route('login_page')->with('error', 'Driver not found');
    }
    
    $location = Location::where('id', $driver->location_id)->first();
    $location_name = $location ? $location->location_name : 'No Location';
    $locations = Location::select('location_name', 'id')->get();

    // 🔹 Visit statistics
    $total_visits = Visit::where('driver_id', $id)->count();
    $completed_visits = Visit::where('driver_id', $id)->where('status', 2)->count() ?? 0;
    $pending_visits = Visit::where('driver_id', $id)->where('status', 1)->count() ?? 0;
    $cancelled_visits = Visit::where('driver_id', $id)->where('status', 3)->count() ?? 0;

    return view('drivers.driver_page', compact(
        'driver',

        'location_name',

        'locations',
        'total_visits',
        'completed_visits',
        'pending_visits',
        'cancelled_visits'
    ));
}

public function todayVisitsdriver(Request $request, $driverId)
{
    $today = Carbon::today();

    // get driver's location_id
    $driver = Driver::findOrFail($driverId);
    $locationId = $driver->location_id;

    $visits = Visit::with(['booking.location', 'customer'])
        ->where('location_id', $locationId)
        ->whereDate('visit_date', $today->toDateString())
        ->get();

    return response()->json([
        'data' => $visits->map(function ($row) {
            $statusText = match ((int) $row->driver_status) {
                1 => 'Pending',
                2 => 'Completed',
                3 => 'Cancelled',
                default => 'N/A',
            };

            $durationText = $row->duration ? $row->duration . ' Hours' : 'N/A';

            return [
                'id' => $row->id,
                'booking_no' => $row->booking?->booking_no ?? 'N/A',
                'visit_date' => $row->visit_date
                    ? Carbon::parse($row->visit_date)->format('d-m-Y')
                    : 'N/A',
                'customer' => $row->customer?->customer_name ?? 'N/A',
                'location' => $row->booking?->location?->location_name ?? 'N/A',
                'shift_duration_status' =>
                    '<span class="badge bg-info me-1">' . ($row->shift ?? 'N/A') . '</span>' .
                    '<span class="badge bg-warning me-1">' . $durationText . '</span>' .
                    '<span class="badge ' .
                        ($row->status == 1
                            ? 'bg-secondary'
                            : ($row->status == 2
                                ? 'bg-success'
                                : 'bg-danger')) .
                    '">' . $statusText . '</span>',
               'action' => $row->driver_status == 2
    ? '<span class="badge bg-success text-light">Done</span>'
    : '<span class="badge bg-warning text-dark" style="cursor:pointer;" onclick="edit_driver_visit(' . $row->id . ')">Mark Completed</span>',

            ];
        })
    ]);
}


public function thisWeekVisitsdriver(Request $request, $driverId)
{
    $today = Carbon::today();
    $endOfWeek = $today->copy()->addDays(6);

    // get driver's location_id
    $driver = Driver::findOrFail($driverId);
    $locationId = $driver->location_id;

    $visits = Visit::with(['booking.location', 'customer'])
        ->where('location_id', $locationId)
        ->whereBetween('visit_date', [$today->toDateString(), $endOfWeek->toDateString()])
        ->get();

    return response()->json([
        'data' => $visits->map(function ($row) {
            $statusText = match ((int) ($row->driver_status ?? 0)) {
                1 => 'Pending',
                2 => 'Completed',
                3 => 'Cancelled',
                default => 'N/A',
            };

            $durationText = $row->duration ? $row->duration . ' Hours' : 'N/A';

            return [
                'id' => $row->id,
                'booking_no' => $row->booking?->booking_no ?? 'N/A',
                'visit_date' => $row->visit_date
                    ? Carbon::parse($row->visit_date)->format('d-m-Y')
                    : 'N/A',
                'customer' => $row->customer?->customer_name ?? 'N/A',
                'location' => $row->booking?->location?->location_name ?? 'N/A',
                'shift_duration_status' =>
                    '<span class="badge bg-info me-1">' . ($row->shift ?? 'N/A') . '</span>' .
                    '<span class="badge bg-warning me-1">' . $durationText . '</span>' .
                    '<span class="badge ' .
                        ($row->status == 1
                            ? 'bg-secondary'
                            : ($row->status == 2
                                ? 'bg-success'
                                : 'bg-danger')) .
                    '">' . $statusText . '</span>',
            ];
        })
    ]);
}


public function allVisitsdriver($driverId)
{


    $sno = 0;

    // fetch visits for this driver directly
    $visits = Visit::with(['booking.location', 'customer'])
        ->where('driver_id', $driverId)
        ->get();

    $json = [];

    if ($visits->count() > 0) {
        foreach ($visits as $visit) {
            $statusBadge = match ($visit->driver_status) {
                1 => '<span class="badge bg-secondary me-1">Pending</span>',
                2 => '<span class="badge bg-success me-1">Completed</span>',
                3 => '<span class="badge bg-danger me-1">Cancelled</span>',
                default => '<span class="badge bg-dark me-1">N/A</span>',
            };

            $shiftBadge    = $visit->shift    ? '<span class="badge bg-info me-1">' . e($visit->shift) . '</span>' : '';
            $durationBadge = $visit->duration ? '<span class="badge bg-warning me-1">' . e($visit->duration) . ' Hours</span>' : '';

            $shiftDurationStatus = $statusBadge . $shiftBadge . $durationBadge;

            $sno++;
            $json[] = [
                '<span class="visit-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2"> ' . ($visit->booking?->booking_no ?? 'N/A') . '</span>',
                '<span class="text-nowrap ms-2"> ' . ($visit->visit_date ? Carbon::parse($visit->visit_date)->format('d-m-Y') : 'N/A') . '</span>',
                '<span class="text-primary">' . ($visit->customer?->customer_name ?? 'N/A') . '</span>',
                '<span class="text-primary">' . ($visit->booking?->location?->location_name ?? 'N/A') . '</span>',
                $shiftDurationStatus,
            ];
        }
    }

    return response()->json([
        'success' => true,
        'aaData' => $json
    ]);
}

public function completeVisitdriver(Request $request)
{

    $visit = Visit::find($request->visit_id);

        $user = Auth::user();
        $user_id = Auth::id(); // null if guest
        $user_name = $user ? $user->user_name : null; // null if guest

        $driver = $user_id ? Driver::where('user_id', $user_id)->first() : null;
        $driver_id = $driver ? $driver->id : null;

    if (!$visit) {
        return response()->json(['success' => false, 'message' => 'Visit not found.']);
    }

    $visit->driver_status = 2; // completed
    $visit->driver_id = $driver_id; // completed
    $visit->save();

    return response()->json(['success' => true]);
}

}
