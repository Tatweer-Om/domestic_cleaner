<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\History;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
     public function index()
    {

        return view('packages.package');
    }

    public function show_package()
    {

        $sno = 0;

            $view_authpackage = Package::all();

        if (count($view_authpackage) > 0) {
            foreach ($view_authpackage as $value) {

                $package_name = '<a class-"patient-info ps-0" href="javascript:void(0);">' . $value->package_name . '</a>';

                $modal = '
                    <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_package_modal" onclick=edit("' . $value->id . '")>
                        <i class="fa fa-pencil fs-18 text-success"></i>
                    </a>
                    <a href="javascript:void(0);" onclick=del("' . $value->id . '")>
                        <i class="fa fa-trash fs-18 text-danger"></i>
                    </a>';

                $add_data = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $package_type_label = $value->package_type == 1 ? 'Daily' : ($value->package_type == 2 ? 'Monthly' : '-');

                $sno++;
                $price_info = '<div class="d-flex flex-column">
                        <span class="fw-semibold text-success">4 Hours: ' . $value->package_price_4 . '</span>
                        <span class="fw-semibold text-info">5 Hours: ' . $value->package_price_5 . '</span>
                </div>';

            $json[] = array(
                '<span class="patient-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2">' . $package_name . '</span>',
                '<span class="text-primary">' . $value->sessions . '</span>',
                $price_info,  // replace old price cell
                $package_type_label,
                '<span>' . $value->added_by . '</span>',
                '<span>' . $add_data . '</span>',
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

    public function add_package(Request $request)
    {

        // $user_id = Auth::id();
        // $data = User::where('id', $user_id)->first();
        // $user_name = $data->user_name;



        $package = new package();

        $package->package_name = $request['package_name'];
        $package->package_type = $request['package_type'];

        $package->sessions = $request['sessions'];
        $package->package_price_4 = $request['package_price_4'];
        $package->package_price_5 = $request['package_price_5'];
        $package->notes = $request['notes'];
        $package->added_by =  'SYSTEM';
        $package->user_id = 1;
        $package->save();
        return response()->json(['package_id' => $package->id]);
    }


    public function edit_package(Request $request)
    {

        $package_id = $request->input('id');

        $package_data = Package::where('id', $package_id)->first();
        $data = [
            'package_id' => $package_data->id,
            'package_name' => $package_data->package_name,
            'package_type' => $package_data->package_type,
            'sessions' => $package_data->sessions,
            'package_price_4' => $package_data->package_price_4,
            'package_price_5' => $package_data->package_price_5,
            'notes' => $package_data->notes,
        ];

        return response()->json($data);
    }

   public function update_package(Request $request)
{
    $package_id = $request->input('package_id');

    $package = Package::find($package_id);

    if (!$package) {
        return response()->json(['error' => trans('messages.package_not_found', [], session('locale'))], 404);
    }

    // ✅ Capture full package data before update
    $previousData = $package->toArray();

    // ✅ Update fields
    $package->package_name = $request['package_name'];
    $package->sessions = $request['sessions'];
    $package->package_type = $request['package_type'];
    $package->package_price_4 = $request['package_price_4'];
    $package->package_price_5 = $request['package_price_5'];
    $package->notes = $request['notes'];
    $package->added_by = 'SYSTEM';
    $package->user_id = 1;
    $package->save();

    // ✅ Capture full updated data as well
    $updatedData = $package->toArray();

    // ✅ Save to history
    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'packages';
    $history->function = 'update';
    $history->function_status = 1;
    $history->branch_id = 1;
    $history->record_id = $package->id;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($updatedData);
    $history->added_by = 'SYSTEM';
    $history->save();

    return response()->json([
        trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))
    ]);
}



    public function delete_package(Request $request)
    {
        // $user_id = Auth::id();
        // $user = User::where('id', $user_id)->first();
        // $user_name = $user->user_name;

        $package_id = $request->input('id');
        $package = Package::where('id', $package_id)->first();

        if (!$package) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.package_not_found', [], session('locale'))], 404);
        }

        // Capture the previous data before deletion
        $previousData = $package->only([
            'package_name',
            'sessions',
            'package_price',
            'branch_id',
            'notes',
            'added_by',
            'user_id',
            'created_at'
        ]);

        // Create the history record for the deletion
        $history = new History();
        $history->user_id = 1;
        $history->table_name = 'packages';  // Corrected table name
        $history->function = 'delete';
        $history->function_status = 2; // 2 for deletion
        $history->branch_id = $package->branch_id;
        $history->record_id = $package->id;
        $history->previous_data = json_encode($previousData); // Store previous data before deletion
        $history->added_by = 'SYSTEM';
        $history->save();

        // Delete the package
        $package->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }
}
