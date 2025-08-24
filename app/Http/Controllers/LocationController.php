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
    public function index(){

    return view ('locations.location');

    }

    public function show_location()
    {

        $sno=0;

        $view_authlocation= Location::all();
        if(count($view_authlocation)>0)
        {
            foreach($view_authlocation as $value)
            {

                $location_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->location_name.'</a>';

                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_location_modal" onclick=edit("'.$value->id.'")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');




                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">'. $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $location_name . '</span>',
                    '<span class="text-nowrap ms-2">' . $value->location_fare . '</span>',

                    '<span >' . $value->added_by . '</span>',
                    '<span >' . $add_data . '</span>',
                    $modal
                );

            }
            $response = array();
            $response['success'] = true;
            $response['aaData'] = $json;
            echo json_encode($response);
        }
        else
        {
            $response = array();
            $response['sEcho'] = 0;
            $response['iTotalRecords'] = 0;
            $response['iTotalDisplayRecords'] = 0;
            $response['aaData'] = [];
            echo json_encode($response);
        }
    }

    public function add_location(Request $request){

        // $user_id = Auth::id();
        // $data= User::where('id', $user_id )->first();
        // $user_name= $data->user_name;



        $location = new Location();

        $location->location_name = $request['location_name'];
        $location->location_fare = $request['location_fare'];

        $location->notes = $request['notes'];
        $location->added_by = 1;
        $location->user_id = 1;
        $location->save();
        return response()->json(['location_id' => $location->id]);

    }


    public function edit_location(Request $request){

        $location_id = $request->input('id');

        $location_data = Location::where('id', $location_id)->first();
        $data = [
            'location_id' => $location_data->id,
            'location_name' => $location_data->location_name,
            'location_fare' => $location_data->location_fare,
            'notes' => $location_data->notes,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_location(Request $request)
{
    $location_id = $request->input('location_id');

    // $user_id = Auth::id();

    // $user = User::where('id', $user_id)->first();
    // $user_name = $user->user_name;

    $location = Location::where('id', $location_id)->first();

    if (!$location) {
        return response()->json(['error' => trans('messages.location_not_found', [], session('locale'))], 404);
    }

    $previousData = $location->only(['location_name', 'location_fare', 'notes', 'added_by', 'user_id', 'created_at']);

    $location->location_name = $request->input('location_name');
    $location->location_name = $request->input('location_fare');
    $location->notes = $request->input('notes');
    $location->added_by = 1;
    $location->user_id = 1;
    $location->save();

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'locationes';
    $history->function = 'update';
    $history->function_status = 1;
    $history->record_id = $location->id;
    $history->branch_id = 1;

    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($location->only([
        'location_name', 'location_email', 'location_phone', 'notes', 'added_by', 'user_id'
    ]));
    $history->added_by = 'system';
    $history->save();

    return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))]);
}


public function delete_location(Request $request) {


    // $user_id = Auth::id();
    // $user = User::where('id', $user_id)->first();
    // $user_name = $user->user_name;
    $location_id = $request->input('id');
    $location = Location::where('id', $location_id)->first();

    if (!$location) {
        return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.location_not_found', [], session('locale'))], 404);
    }

    $previousData = $location->only([
        'location_name', 'location_fare', 'notes', 'added_by', 'user_id', 'created_at'
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
    $history->branch_id =1;

    $history->previous_data = json_encode($previousData);

    $history->added_by = 'system';
    $history->save();
    $location->delete();

    return response()->json([
        trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
    ]);
}
}
