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
    $worker->worker_image = $worker_image;
    $worker->notes = $request->input('notes');

    // Auto set user_id and added_by for branch 1
    $worker->user_id = 1;
    $worker->added_by = 'system';

    $worker->save();

    $updatedData = $worker->only([
        'worker_name',  'phone', 'worker_image',
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


// public function generate(Request $request)
// {

//     $validated = $request->validate([
//         'package_id'  => 'required|integer',
//         'start_date'  => 'required|date',
//         'shift_morning' => 'nullable|in:0,1',
//         'shift_evening' => 'nullable|in:0,1',
//         'duration_4'    => 'nullable|in:0,1',
//         'duration_5'    => 'nullable|in:0,1',
//     ]);



//     $package = Package::findOrFail($validated['package_id']);

//     $visits = $package->sessions;

//     $visit_count = is_numeric($visits) ? (int)$visits : (is_countable($visits) ? count($visits) : 0);

//     return response()->json([
//         'status'       => 'success',
//         'visit_count'  => $visit_count,
//         'visits'       => is_numeric($visits) ? [] : $visits,
//     ]);
// }

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
                'is_available' => !$is_booked_requested,
                'opposite_shift' => $opposite_shift,
                'opposite_shift_available' => !$is_booked_opposite,
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

                $availability_issues[] = [
                    'visit_number' => $index + 1,
                    'date' => $date,
                    'shift' => $shift,
                    'message' => "Worker is occupied on {$date} for the {$shift} shift",
                    'opposite_shift' => $opposite_shift,
                    'opposite_shift_available' => !$is_booked_opposite,
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

}
