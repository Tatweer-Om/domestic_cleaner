<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Visit;
use App\Models\Worker;
use App\Models\History;
use App\Models\Package;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WorkerController extends Controller
{
    public function index()
{

    $locations= Location::select('id', 'location_name')->get();
    return view('workers.workers', compact('locations'));
}


   public function worker_page($id)
{
    $worker = Worker::where('id', $id)->first();
    $location = Location::where('id', $worker->location_id)->first();
    $location_name = $location->location_name;
    $delivery = $location->driver_availabe;
    $packages = Package::select('package_name', 'id')->get();
    $locations = Location::select('location_name', 'id')->get();

    // 🔹 Visit statistics
    $total_visits = Visit::where('worker_id', $id)->count();
    $completed_visits = Visit::where('worker_id', $id)->where('status', 2)->count();
    $pending_visits = Visit::where('worker_id', $id)->where('status', 1)->count();
    $cancelled_visits = Visit::where('worker_id', $id)->where('status', 3)->count();

    return view('workers.worker_page', compact(
        'worker',
        'packages',
        'location_name',
        'delivery',
        'locations',
        'total_visits',
        'completed_visits',
        'pending_visits',
        'cancelled_visits'
    ));
}


public function show_worker()
{
    $sno = 0;
    $workers = Worker::all(); // No user-type filtering

    if ($workers->count() > 0) {
        foreach ($workers as $worker) {
            $employee_id = $worker->id;



            $worker_name = '<a class="worker-info ps-0" href="worker_profile/' . $worker->id . '">' . $worker->worker_name . '</a>';
            $modal = '<a href="javascript:void(0);" class="me-3 edit-worker" data-bs-toggle="modal" data-bs-target="#add_worker_modal" onclick="edit(' . $worker->id . ')">
                <i class="fa fa-pencil fs-18 text-success"></i>
              </a>
              <a href="javascript:void(0);" onclick="del(' . $worker->id . ')">
                <i class="fa fa-trash fs-18 text-danger"></i>
              </a>
            ';

            $add_data = Carbon::parse($worker->created_at)->format('d-m-Y (h:i a)');
            $worker_image = $worker->worker_image ? asset('images/worker_images/' . $worker->worker_image) : asset('images/dummy_images/no_image.jpg');
            $src = '<img src="' . $worker_image . '" class="worker-info ps-0" style="max-width:40px">';
            $shiftLabel = '';

            if ($worker->shift == 1) {
                $shiftLabel = trans('messages.morning', [], session('locale'));
            } elseif ($worker->shift == 2) {
                $shiftLabel = trans('messages.evening', [], session('locale'));
            } elseif ($worker->shift == 3) {
                $shiftLabel = trans('messages.both', [], session('locale'));
            } else {
                $shiftLabel = '-';
            }

            $location_name= Location::where('id', $worker->location_id)->value('location_name');
            $sno++;
            $json[] = array(
                '<span class="worker-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2">' . $src . ' ' . $worker_name . '</span>',
                '<span class="text-primary">' . $worker->phone . '</span>',
                '<span class="text-primary">' . $location_name . '</span>',

                '<span class="text-primary">' .$shiftLabel . '</span>',
                '<span class="text-primary">' . e($worker->status ?? '-') . '</span>',

                '<span>' . $worker->added_by . '</span>',
                '<span>' . $add_data . '</span>',
                $modal
            );
        }

        return response()->json(['success' => true, 'aaData' => $json]);
    }

    return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
}

public function add_worker(Request $request)
{
    $worker_image = "";
    if ($request->hasFile('worker_image')) {
        $folderPath = public_path('images/worker_images');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $worker_image = time() . '.' . $request->file('worker_image')->extension();
        $request->file('worker_image')->move($folderPath, $worker_image);
    }

    $worker = new Worker();
    $worker->worker_name = $request->input('worker_name');
    $worker->phone = $request->input('phone');

    $worker->worker_user_id = $request->input('worker_user_id');
        $worker->location_id = $request->input('location_id');

        $worker->shift = $request->input('shift');
        $worker->status = $request->input('status', 'available');
    $worker->worker_image = $worker_image;
    $worker->notes = $request->input('notes');

    // Auto set user_id and added_by for branch 1
    $worker->user_id = 1;
    $worker->added_by =  'system';

    $worker->save();

    return response()->json(['worker_id' => $worker->id]);
}

public function edit_worker(Request $request)
{
    $worker = Worker::find($request->input('id'));
    if (!$worker) {
        return response()->json(['error' => 'worker not found'], 404);
    }

    $worker_image = $worker->worker_image ? asset('images/worker_images/' . $worker->worker_image) : asset('images/dummy_images/no_image.jpg');

    return response()->json([
        'worker_id' => $worker->id,
        'worker_name' => $worker->worker_name,
        'worker_user_id' => $worker->worker_user_id,
                'location_id' => $worker->location_id,

        'shift' => $worker->shift,
        'status' => $worker->status,
        'phone' => $worker->phone,
        'worker_image' => $worker_image,
        'notes' => $worker->notes,
    ]);
}

public function update_worker(Request $request)
{
    $worker = Worker::find($request->input('worker_id'));
    if (!$worker) {
        return response()->json(['error' => 'worker not found'], 404);
    }

    $previousData = $worker->only([
        'worker_name',  'phone',
        'worker_image', 'notes', 'user_id', 'added_by', 'created_at'
    ]);

    if ($request->hasFile('worker_image')) {
        $oldImagePath = public_path('images/worker_images/' . $worker->worker_image);
        if (File::exists($oldImagePath) && !empty($worker->worker_image)) {
            File::delete($oldImagePath);
        }
        $folderPath = public_path('images/worker_images');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $worker_image = time() . '.' . $request->file('worker_image')->extension();
        $request->file('worker_image')->move($folderPath, $worker_image);
    } else {
        $worker_image = $worker->worker_image;
    }

    $worker->worker_name = $request->input('worker_name');
    $worker->phone = $request->input('phone');
    $worker->worker_user_id = $request->input('worker_user_id');
        $worker->location_id = $request->input('location_id');

    $worker->shift = $request->input('shift');
    $worker->status = $request->input('status', $worker->status);
    $worker->worker_image = $worker_image;
    $worker->notes = $request->input('notes');

    // Auto set user_id and added_by for branch 1
    $worker->user_id = 1;
    $worker->added_by = 'system';

    $worker->save();

    $updatedData = $worker->only([
        'worker_name',  'phone', 'worker_image', 'status',
        'notes',  'user_id', 'added_by'
    ]);

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'workers';
    $history->function = 'update';
    $history->function_status = 1;
    $history->branch_id = 1;
    $history->record_id = $worker->id;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($updatedData);
    $history->added_by = 'admin';
    $history->save();

    return response()->json(['success' => 'worker updated successfully']);
}

public function delete_worker(Request $request)
{
    $worker = Worker::find($request->input('id'));
    if (!$worker) {
        return response()->json(['error' => 'worker not found'], 404);
    }

    $previousData = $worker->only([
        'worker_name', 'user_name', 'phone', 'email', 'worker_image',
        'branch_id',  'notes',
        'user_id', 'added_by', 'created_at'
    ]);

    if (!empty($worker->worker_image)) {
        $imagePath = public_path('images/worker_images/' . $worker->worker_image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'workers';
    $history->branch_id = 1;
    $history->function = 'delete';
    $history->function_status = 2;
    $history->record_id = $worker->id;
    $history->previous_data = json_encode($previousData);
    $history->added_by = 'admin';
    $history->save();

    $worker->delete();

    return response()->json(['success' => 'worker deleted successfully']);
}







public function workers_list(Request $request)
{
    // Fetch workers (tweak ordering/limits as you like)
    $workers = Worker::select('id','worker_name','worker_image')
        ->get();

    // Start HTML shell (copied from your section)
    $html = '
    <section class="wpo-blog-section section-padding pt-0">
        <div class="wpo-blog-wrap section-padding box-style">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6">
                        <div class="wpo-section-title">
                            <span><i><img src="'.asset('assets/worker_images/cleaning-icon.svg').'" alt=""></i>News & Blogs</span>
                            <h2 class="poort-text poort-in-right">Updated News & Blogs</h2>
                            <p>communication and utilizes cutting edge logistic planning
                                to get your shipment completed on time. itself founded.</p>
                        </div>
                    </div>
                </div>
                <div class="wpo-blog-items">
                    <div class="row">
    ';

    // Card loop (each worker -> a card)
 foreach ($workers as $index => $w) {
    // Image path directly from public/worker_images
    $img = $w->worker_image
        ? asset('images/worker_images/'.$w->worker_image)
        : asset('assets/images/blog/img-'.( ($index % 3)+1 ).'.jpg'); // fallback rotation

    $name = e($w->worker_name ?? 'Unnamed');
    $spec = e( 'Cleaning');

    $delay = number_format($index * 0.1, 1); // 0.0s, 0.1s, 0.2s...

    $html .= '
        <div class="col col-lg-4 col-md-6 col-12">
            <div class="wpo-blog-item wow fadeInUp" data-wow-delay="'.$delay.'s" data-wow-duration="1200ms">
                <div class="wpo-blog-img middle-light">
                    <img src="'.$img.'" alt="'. $name .'">
                </div>
                <div class="wpo-blog-content">
                    <div class="wpo-blog-content-top">
                        <a class="thumb" href="javascript:void(0)">'. $spec .'</a>
                        <h2><a href="javascript:void(0)">'. $name .'</a></h2>
                    </div>
                    <ul>
                        <li><i class="ti-user"></i> '. Str::limit($spec, 28) .'</li>
                        <li><a href="javascript:void(0)"><i class="ti-comment-alt"></i>Hire Now</a></li>
                    </ul>
                </div>
            </div>
        </div>
    ';
}


    // Close HTML shell
    $html .= '
                    </div>
                </div>
            </div>
        </div>
    </section>';

    // Return single variable named worker_list (as requested)
    return response()->json([
        'worker_list' => $html,
    ]);
}




 public function generate(Request $request)
    {
        $validated = $request->validate([
            'package_id'    => 'required|integer',
            'worker_id'     => 'required|integer',
            'start_date'    => 'required|date',
            'shift_morning' => 'nullable|in:0,1',
            'shift_evening' => 'nullable|in:0,1',
            'duration_4'    => 'nullable|in:0,1',
            'duration_5'    => 'nullable|in:0,1',
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $worker = Worker::findOrFail($validated['worker_id']);



        $visits = $package->sessions;
        $visit_count = is_numeric($visits) ? (int)$visits : (is_countable($visits) ? count($visits) : 0);

        if ($visit_count === 0) {
            return response()->json([
                'status' => 'success',
                'visit_count' => 0,
                'visits' => [],
                'worker_availability' => [],
            ]);
        }

        $shift = $validated['shift_morning'] ? 'morning' : ($validated['shift_evening'] ? 'evening' : null);
        if (!$shift) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please select a shift (morning or evening).',
                'visit_count' => $visit_count,
                'visits' => is_numeric($visits) ? [] : $visits,
            ], 422);
        }

        // Generate visit dates, skipping Fridays
        $visit_dates = [];
        $start_date = Carbon::parse($validated['start_date']);
        $count = 0;
        $day_offset = 0;

        while (count($visit_dates) < $visit_count) {
            $candidate_date = $start_date->copy()->addDays($day_offset);
            if ($candidate_date->dayOfWeek !== 5) { // Skip Friday (Carbon: 5 = Friday)
                $visit_dates[] = $candidate_date->toDateString();
                $count++;
            }
            $day_offset++;
        }

        // Check worker availability for each visit date
        $availability_issues = [];
        $worker_availability = [];

        foreach ($visit_dates as $index => $date) {
            // First check worker status - if worker is sick/emergency/other, mark as unavailable
            $worker_status = $worker->status ?? 'available';
            $is_worker_available = in_array($worker_status, ['available']);
            
            // Check availability for the requested shift
            $is_booked_requested = Visit::where([
                'worker_id' => $validated['worker_id'],
                'visit_date' => $date,
                'shift' => $shift,
            ])->exists();

            // Check availability for the opposite shift
            $opposite_shift = $shift === 'morning' ? 'evening' : 'morning';
            $is_booked_opposite = Visit::where([
                'worker_id' => $validated['worker_id'],
                'visit_date' => $date,
                'shift' => $opposite_shift,
            ])->exists();

            $availability = [
                'visit_number' => $index + 1,
                'worker_id' => $validated['worker_id'],
                'date' => $date,
                'shift' => $shift,
                'is_available' => $is_worker_available && !$is_booked_requested,
                'worker_status' => $worker_status,
                'worker_available' => $is_worker_available,
                'opposite_shift' => $opposite_shift,
                'opposite_shift_available' => $is_worker_available && !$is_booked_opposite,
                'next_available_date' => null,
            ];

            // If the requested shift is unavailable, find the next available non-Friday date for the requested shift
            if (!$availability['is_available']) {
                $next_available_date = null;
                $check_date = Carbon::parse($date)->addDay();
                $max_attempts = 30; // Prevent infinite loops
                $attempt = 0;

                while ($attempt < $max_attempts) {
                    if ($check_date->dayOfWeek !== 5) { // Skip Fridays
                        $is_booked = Visit::where([
                            'worker_id' => $validated['worker_id'],
                            'visit_date' => $check_date->toDateString(),
                            'shift' => $shift,
                        ])->exists();

                        if (!$is_booked) {
                            $next_available_date = $check_date->toDateString();
                            break;
                        }
                    }
                    $check_date->addDay();
                    $attempt++;
                }

                $availability['next_available_date'] = $next_available_date;

                // Create appropriate message based on unavailability reason
                $message = "Worker is occupied on {$date} for the {$shift} shift";
                if (!$is_worker_available) {
                    $status_labels = [
                        'sick' => 'sick',
                        'emergency_leave' => 'on emergency leave',
                        'other' => 'unavailable'
                    ];
                    $status_label = $status_labels[$worker_status] ?? 'unavailable';
                    $message = "Worker is {$status_label} on {$date}";
                }

                $availability_issues[] = [
                    'visit_number' => $index + 1,
                    'date' => $date,
                    'shift' => $shift,
                    'message' => $message,
                    'worker_status' => $worker_status,
                    'worker_available' => $is_worker_available,
                    'opposite_shift' => $opposite_shift,
                    'opposite_shift_available' => $is_worker_available && !$is_booked_opposite,
                    'next_available_date' => $next_available_date,
                ];
            }

            $worker_availability[] = $availability;
        }

        // If there are availability issues, include them in the response but allow tiles to be generated
        return response()->json([
            'status' => empty($availability_issues) ? 'success' : 'warning',
            'visit_count' => $visit_count,
            'visits' => is_numeric($visits) ? [] : $visits,
            'worker_availability' => $worker_availability,
            'availability_issues' => $availability_issues,
        ]);
    }

    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => 'required|integer',
            'date' => 'required|date',
            'shift' => 'required|in:morning,evening',
        ]);

        // Check availability for the requested shift
        $is_booked_requested = Visit::where([
            'worker_id' => $validated['worker_id'],
            'visit_date' => $validated['date'],
            'shift' => $validated['shift'],
        ])->exists();

        // Check availability for the opposite shift
        $opposite_shift = $validated['shift'] === 'morning' ? 'evening' : 'morning';
        $is_booked_opposite = Visit::where([
            'worker_id' => $validated['worker_id'],
            'visit_date' => $validated['date'],
            'shift' => $opposite_shift,
        ])->exists();

        $response = [
            'is_available' => !$is_booked_requested,
            'opposite_shift' => $opposite_shift,
            'opposite_shift_available' => !$is_booked_opposite,
            'next_available_date' => null,
        ];

        // If the requested shift is unavailable, find the next available non-Friday date for the requested shift
        if (!$response['is_available']) {
            $next_available_date = null;
            $check_date = Carbon::parse($validated['date'])->addDay();
            $max_attempts = 30; // Prevent infinite loops
            $attempt = 0;

            while ($attempt < $max_attempts) {
                if ($check_date->dayOfWeek !== 5) { // Skip Fridays
                    $is_booked = Visit::where([
                        'worker_id' => $validated['worker_id'],
                        'visit_date' => $check_date->toDateString(),
                        'shift' => $validated['shift'],
                    ])->exists();

                    if (!$is_booked) {
                        $next_available_date = $check_date->toDateString();
                        break;
                    }
                }
                $check_date->addDay();
                $attempt++;
            }

            $response['next_available_date'] = $next_available_date;
        }

        return response()->json($response);
    }
    
public function todayVisits(Request $request, $worker)
{
    $today = Carbon::today();

    $visits = Visit::with(['booking.location', 'customer'])
                   ->where('worker_id', $worker)
                   ->where('visit_date', $today->toDateString())
                   ->get();

    return response()->json([
        'data' => $visits->map(function ($row) {
            $statusText = match ($row->status) {
                1 => 'Pending',
                2 => 'Completed',
                3 => 'Cancelled',
                default => 'N/A',
            };

            $durationText = $row->duration ? $row->duration . ' Hours' : 'N/A';

            return [
                'id' => $row->id,
                'booking_no' => $row->booking ? $row->booking->booking_no : 'N/A',
                'visit_date' => $row->visit_date ? \Carbon\Carbon::parse($row->visit_date)->format('d-m-Y') : 'N/A',
                'customer' => $row->customer ? $row->customer->customer_name : 'N/A',
                'location' => $row->booking && $row->booking->location 
                                ? $row->booking->location->location_name 
                                : 'N/A',
                'shift_duration_status' => 
                    '<span class="badge bg-info me-1">' . ($row->shift ?? 'N/A') . '</span>' .
                    '<span class="badge bg-warning me-1">' . $durationText . '</span>' .
                    '<span class="badge ' . 
                        ($row->status == 1 ? 'bg-secondary' : ($row->status == 2 ? 'bg-success' : 'bg-danger')) . 
                    '">' . $statusText . '</span>',
              'action' => $row->status == 2
    ? ''
    : '<span class="badge bg-warning text-dark" style="cursor:pointer;" onclick="edit_worker_visit(' . $row->id . ')">Mark Completed</span>',


            ];
        })
    ]);
}



 public function thisWeekVisits(Request $request, $worker)
{

  
    $today = Carbon::today();
    $endOfWeek = $today->copy()->addDays(6); // today + 6 days

    $visits = Visit::with(['booking.location', 'customer'])
                   ->where('worker_id', $worker)
                   ->whereBetween('visit_date', [$today->toDateString(), $endOfWeek->toDateString()])
                   ->get();

    return response()->json([
        'data' => $visits->map(function ($row) {
            $statusText = match ((int) ($row->status ?? 0)) {
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
                    ? \Carbon\Carbon::parse($row->visit_date)->format('d-m-Y')
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

public function allVisits($workerId)
{
    $sno = 0;

    // fetch visits only for this worker
    $visits = Visit::where('worker_id', $workerId)->get();

    $json = []; // init array

    if ($visits->count() > 0) {
        foreach ($visits as $visit) {
            $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" 
                   data-bs-toggle="modal" data-bs-target="#add_visit_modal" 
                   onclick=edit_visit("' . $visit->id . '")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" class="me-3 " 
                   data-bs-toggle="modal" data-bs-target="#add_condition_modal" 
                   onclick=condition("' . $visit->id . '")>
                    <i class="fa fa-book fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("' . $visit->id . '")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

            $add_data   = Carbon::parse($visit->created_at)->format('d-m-Y (h:i a)');
            $location   = optional($visit->booking->location)->location_name;
            $booking_no = optional($visit->booking)->booking_no;
            $customer   = optional($visit->customer)->customer_name;
            $visit_date = Carbon::parse($visit->visit_date)->format('d-m-Y');
            $worker     = optional($visit->worker)->worker_name;

           if ($visit->status == 1) {
    $statusBadge = '<span class="badge bg-secondary me-1">Pending</span>';
} elseif ($visit->status == 2) {
    $statusBadge = '<span class="badge bg-success me-1">Completed</span>';
} elseif ($visit->status == 3) {
    $statusBadge = '<span class="badge bg-danger me-1">Cancelled</span>';
} else {
    $statusBadge = '<span class="badge bg-dark me-1">N/A</span>';
}

// shift + duration badges
$shiftBadge    = $visit->shift    ? '<span class="badge bg-info me-1">' . e($visit->shift) . '</span>' : '';
$durationBadge = $visit->duration ? '<span class="badge bg-warning me-1">' . e($visit->duration) . ' Hours</span>' : '';

// combine all into one column
$shiftDurationStatus = $statusBadge . $shiftBadge . $durationBadge;

            $sno++;
            $json[] = [
                '<span class="visit-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2"> ' . $booking_no . '</span>',
                '<span class="text-nowrap ms-2"> ' . $visit_date . '</span>',
                '<span class="text-primary">' . $customer . '</span>',
                '<span class="text-primary">' . $location . '</span>',
                $shiftDurationStatus,
     
            ];
        }
    }

    return response()->json([
        'success' => true,
        'aaData' => $json
    ]);
}


public function completeVisit(Request $request)
{
    $visit = Visit::find($request->visit_id);

    if (!$visit) {
        return response()->json(['success' => false, 'message' => 'Visit not found.']);
    }

    $visit->status = 2; // completed
    $visit->save();

    return response()->json(['success' => true]);
}

}
