<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Worker;
use App\Models\History;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
      public function index()
{
    return view('bookings.service');
}

public function show_service()
{
    $sno = 0;
    $services = Service::all(); // No user-type filtering

    if ($services->count() > 0) {
        foreach ($services as $service) {
            $employee_id = $service->id;



            $service_name = '<a class="service-info ps-0" href="service_profile/' . $service->id . '">' . $service->service_name . '</a>';
            $modal = '<a href="javascript:void(0);" class="me-3 edit-service" data-bs-toggle="modal" data-bs-target="#add_service_modal" onclick="edit(' . $service->id . ')">
                <i class="fa fa-pencil fs-18 text-success"></i>
              </a>
              <a href="javascript:void(0);" onclick="del(' . $service->id . ')">
                <i class="fa fa-trash fs-18 text-danger"></i>
              </a>
            ';

            $add_data = Carbon::parse($service->created_at)->format('d-m-Y (h:i a)');
            $service_image = $service->service_image ? asset('images/service_images/' . $service->service_image) : asset('images/dummy_images/no_image.jpg');
            $src = '<img src="' . $service_image . '" class="service-info ps-0" style="max-width:40px">';



            $sno++;
            $json[] = array(
                '<span class="service-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2">' . $src . ' ' . $service_name . '</span>',
                '<span class="text-primary">' . $service->service_fee . '</span>',

                '<span>' . $service->added_by . '</span>',
                '<span>' . $add_data . '</span>',
                $modal
            );
        }

        return response()->json(['success' => true, 'aaData' => $json]);
    }

    return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
}

public function add_service(Request $request)
{

  $service_image = "";
if ($request->hasFile('service_image')) {
    $folderPath = public_path('images/service_images');

    // Create directory if it doesn't exist
    if (!File::isDirectory($folderPath)) {
        File::makeDirectory($folderPath, 0777, true, true);
    }

    // Get the original extension in lowercase
    $extension = strtolower($request->file('service_image')->getClientOriginalExtension());

    // Generate unique filename with original extension
    $service_image = time() . '_' . uniqid() . '.' . $extension;

    // Move the file
    $request->file('service_image')->move($folderPath, $service_image);
}

    $service = new Service();
    $service->service_name = $request->input('service_name');
    $service->service_fee = $request->input('service_fee');
    $service->service_image = $service_image;
    $service->notes = $request->input('notes');
    $service->user_id = 1;
    $service->added_by =  'system';

    $service->save();

    return response()->json(['service_id' => $service->id]);
}

public function edit_service(Request $request)
{
    $service = Service::find($request->input('id'));
    if (!$service) {
        return response()->json(['error' => 'service not found'], 404);
    }

    $service_image = $service->service_image ? asset('images/service_images/' . $service->service_image) : asset('images/dummy_images/no_image.jpg');

    return response()->json([
        'service_id' => $service->id,
        'service_name' => $service->service_name,
        'service_fee' => $service->service_fee,
        'service_image' => $service_image,
        'notes' => $service->notes,
    ]);
}

public function update_service(Request $request)
{
    $service = Service::find($request->input('service_id'));
    if (!$service) {
        return response()->json(['error' => 'service not found'], 404);
    }

    $previousData = $service->only([
        'service_name',  'service_fee',
        'service_image', 'notes', 'user_id', 'added_by', 'created_at'
    ]);

  if ($request->hasFile('service_image')) {
    // Delete old image if it exists
    $oldImagePath = public_path('images/service_images/' . $service->service_image);
    if (!empty($service->service_image) && File::exists($oldImagePath)) {
        File::delete($oldImagePath);
    }

    // Ensure folder exists
    $folderPath = public_path('images/service_images');
    if (!File::isDirectory($folderPath)) {
        File::makeDirectory($folderPath, 0777, true, true);
    }

    // Get original extension (keeps PNG, JPG, etc.)
    $extension = strtolower($request->file('service_image')->getClientOriginalExtension());

    // Generate unique filename
    $service_image = time() . '_' . uniqid() . '.' . $extension;

    // Move file
    $request->file('service_image')->move($folderPath, $service_image);
} else {
    // Keep old image if none uploaded
    $service_image = $service->service_image;
}


    $service->service_name = $request->input('service_name');
    $service->service_fee = $request->input('service_fee');
    $service->service_image = $service_image;
    $service->notes = $request->input('notes');

    $service->user_id = 1;
    $service->added_by = 'system';

    $service->save();

    $updatedData = $service->only([
        'service_name', 'service_fee', 'service_image',
        'notes',  'user_id', 'added_by'
    ]);

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'services';
    $history->function = 'update';
    $history->function_status = 1;
    $history->branch_id = 1;
    $history->record_id = $service->id;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($updatedData);
    $history->added_by = 'admin';
    $history->save();

    return response()->json(['success' => 'service updated successfully']);
}

public function delete_service(Request $request)
{
    $service = Service::find($request->input('id'));
    if (!$service) {
        return response()->json(['error' => 'service not found'], 404);
    }

    $previousData = $service->only([
        'service_name', 'user_name', 'service_fee',  'service_image',
         'notes',
        'user_id', 'added_by', 'created_at'
    ]);

    if (!empty($service->service_image)) {
        $imagePath = public_path('images/service_images/' . $service->service_image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'services';
    $history->branch_id = 1;
    $history->function = 'delete';
    $history->function_status = 2;
    $history->record_id = $service->id;
    $history->previous_data = json_encode($previousData);
    $history->added_by = 'admin';
    $history->save();

    $service->delete();

    return response()->json(['success' => 'service deleted successfully']);
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


}
