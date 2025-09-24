<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\History;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
     public function index()
    {

        
            if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('6', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }

        return view('packages.voucher');
    }

    public function show_voucher()
    {

        $sno = 0;

            $view_authvoucher = Voucher::all();

        if (count($view_authvoucher) > 0) {
            foreach ($view_authvoucher as $value) {

                $voucher_number = '<a class-"patient-info ps-0" href="javascript:void(0);">' . $value->voucher_number . '</a>';

                $modal = '
                    <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_voucher_modal" onclick=edit("' . $value->id . '")>
                        <i class="fa fa-pencil fs-18 text-success"></i>
                    </a>
                    <a href="javascript:void(0);" onclick=del("' . $value->id . '")>
                        <i class="fa fa-trash fs-18 text-danger"></i>
                    </a>';

                $add_data = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $voucher_type_label = $value->voucher_type == 1 ? 'Daily' : ($value->voucher_type == 2 ? 'Monthly' : '-');

                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $voucher_number . '</span>',
                    '<span >' . $value->voucher_price . '</span>',
                    $voucher_type_label,
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

    public function add_voucher(Request $request)
    {

        // $user_id = Auth::id();
        // $data = User::where('id', $user_id)->first();
        // $user_name = $data->user_name;



        $voucher = new voucher();

        $voucher->voucher_number = $request['voucher_number'];
        $voucher->voucher_type = $request['voucher_type'];

        $voucher->voucher_price = $request['voucher_price'];
        $voucher->notes = $request['notes'];
        $voucher->added_by =  'SYSTEM';
        $voucher->user_id = 1;
        $voucher->save();
        return response()->json(['voucher_id' => $voucher->id]);
    }


    public function edit_voucher(Request $request)
    {

        $voucher_id = $request->input('id');

        $voucher_data = Voucher::where('id', $voucher_id)->first();
        $data = [
            'voucher_id' => $voucher_data->id,
            'voucher_number' => $voucher_data->voucher_number,
            'voucher_type' => $voucher_data->voucher_type,
            'voucher_price' => $voucher_data->voucher_price,
            'notes' => $voucher_data->notes,
        ];

        return response()->json($data);
    }

   public function update_voucher(Request $request)
{
    $voucher_id = $request->input('voucher_id');

    $voucher = Voucher::find($voucher_id);

    if (!$voucher) {
        return response()->json(['error' => trans('messages.voucher_not_found', [], session('locale'))], 404);
    }

    // ✅ Capture full voucher data before update
    $previousData = $voucher->toArray();

    // ✅ Update fields
    $voucher->voucher_number = $request['voucher_number'];
    $voucher->voucher_type = $request['voucher_type'];
    $voucher->voucher_price = $request['voucher_price'];
    $voucher->branch_id = $request['branch_id'];
    $voucher->notes = $request['notes'];
    $voucher->added_by = 'SYSTEM';
    $voucher->user_id = 1;
    $voucher->save();

    // ✅ Capture full updated data as well
    $updatedData = $voucher->toArray();

    // ✅ Save to history
    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'vouchers';
    $history->function = 'update';
    $history->function_status = 1;
    $history->branch_id = 1;
    $history->record_id = $voucher->id;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($updatedData);
    $history->added_by = 'SYSTEM';
    $history->save();

    return response()->json([
        trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))
    ]);
}



    public function delete_voucher(Request $request)
    {
        // $user_id = Auth::id();
        // $user = User::where('id', $user_id)->first();
        // $user_name = $user->user_name;

        $voucher_id = $request->input('id');
        $voucher = Voucher::where('id', $voucher_id)->first();

        if (!$voucher) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.voucher_not_found', [], session('locale'))], 404);
        }

        // Capture the previous data before deletion
        $previousData = $voucher->only([
            'voucher_number',
            'sessions',
            'voucher_price',
            'branch_id',
            'notes',
            'added_by',
            'user_id',
            'created_at'
        ]);

        // Create the history record for the deletion
        $history = new History();
        $history->user_id = 1;
        $history->table_name = 'vouchers';  // Corrected table name
        $history->function = 'delete';
        $history->function_status = 2; // 2 for deletion
        $history->branch_id = $voucher->branch_id;
        $history->record_id = $voucher->id;
        $history->previous_data = json_encode($previousData); // Store previous data before deletion
        $history->added_by = 'SYSTEM';
        $history->save();

        // Delete the voucher
        $voucher->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }
}
