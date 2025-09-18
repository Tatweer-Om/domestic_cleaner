<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
      public function index()
{

     if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('11', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }

    return view('customers.customers');
}

public function show_customer()
{
    $sno = 0;
    $customers = Customer::all(); // No user-type filtering

    if ($customers->count() > 0) {
        foreach ($customers as $customer) {
            $employee_id = $customer->id;



            $customer_name = '<a class="customer-info ps-0" href="customer_profile/' . $customer->id . '">' . $customer->customer_name . '</a>';
            $modal = '<a href="javascript:void(0);" class="me-3 edit-customer" data-bs-toggle="modal" data-bs-target="#add_customer_modal" onclick="edit(' . $customer->id . ')">
                <i class="fa fa-pencil fs-18 text-success"></i>
              </a>
              <a href="javascript:void(0);" onclick="del(' . $customer->id . ')">
                <i class="fa fa-trash fs-18 text-danger"></i>
              </a>
            ';

            $add_data = Carbon::parse($customer->created_at)->format('d-m-Y (h:i a)');
            $customer_image = $customer->customer_image ? asset('images/customer_images/' . $customer->customer_image) : asset('images/dummy_images/no_image.jpg');
            $src = '<img src="' . $customer_image . '" class="customer-info ps-0" style="max-width:40px">';
            $location= Location::where('id', $customer->location_id)->value('location_name');
            $shiftLabel = '';

            if ($customer->shift == 1) {
                $shiftLabel = trans('messages.morning', [], session('locale'));
            } elseif ($customer->shift == 2) {
                $shiftLabel = trans('messages.evening', [], session('locale'));
            } elseif ($customer->shift == 3) {
                $shiftLabel = trans('messages.both', [], session('locale'));
            } else {
                $shiftLabel = '-';
            }

            $sno++;
            $json[] = array(
                '<span class="customer-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2">' . $src . ' ' . $customer_name . '</span>',
                '<span class="text-primary">' . $customer->phone . '</span>',

                '<span class="text-primary">' .$location . '</span>',
                '<span class="text-primary">' .$shiftLabel . '</span>',

                '<span>' . $customer->added_by . '</span>',
                '<span>' . $add_data . '</span>',
                $modal
            );
        }

        return response()->json(['success' => true, 'aaData' => $json]);
    }

    return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
}

public function add_customer(Request $request)
{
    $customer_image = "";
    if ($request->hasFile('customer_image')) {
        $folderPath = public_path('images/customer_images');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $customer_image = time() . '.' . $request->file('customer_image')->extension();
        $request->file('customer_image')->move($folderPath, $customer_image);
    }

    $customer = new customer();
    $customer->customer_name = $request->input('customer_name');
    $customer->phone = $request->input('phone');

    $customer->customer_user_id = $request->input('customer_user_id');
        $customer->shift = $request->input('shift');
        $customer->location_id = $request->input('location_id');

    $customer->customer_image = $customer_image;
    $customer->notes = $request->input('notes');

    // Auto set user_id and added_by for branch 1
    $customer->user_id = 1;
    $customer->added_by =  'system';

    $customer->save();

    return response()->json(['customer_id' => $customer->id]);
}

public function edit_customer(Request $request)
{
    $customer = Customer::find($request->input('id'));
    if (!$customer) {
        return response()->json(['error' => 'customer not found'], 404);
    }

    $customer_image = $customer->customer_image ? asset('images/customer_images/' . $customer->customer_image) : asset('images/dummy_images/no_image.jpg');

    return response()->json([
        'customer_id' => $customer->id,
        'customer_name' => $customer->customer_name,
        'customer_user_id' => $customer->customer_user_id,
        'shift' => $customer->shift,
        'location_id' => $customer->location_id,
        'phone' => $customer->phone,
        'customer_image' => $customer_image,
        'notes' => $customer->notes,
    ]);
}

public function update_customer(Request $request)
{
    $customer = Customer::find($request->input('customer_id'));
    if (!$customer) {
        return response()->json(['error' => 'customer not found'], 404);
    }

    $previousData = $customer->only([
        'customer_name',  'phone',
        'customer_image', 'notes', 'user_id', 'added_by', 'created_at'
    ]);

    if ($request->hasFile('customer_image')) {
        $oldImagePath = public_path('images/customer_images/' . $customer->customer_image);
        if (File::exists($oldImagePath) && !empty($customer->customer_image)) {
            File::delete($oldImagePath);
        }
        $folderPath = public_path('images/customer_images');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $customer_image = time() . '.' . $request->file('customer_image')->extension();
        $request->file('customer_image')->move($folderPath, $customer_image);
    } else {
        $customer_image = $customer->customer_image;
    }

    $customer->customer_name = $request->input('customer_name');
    $customer->phone = $request->input('phone');
    $customer->customer_user_id = $request->input('customer_user_id');
    $customer->shift = $request->input('shift');
    $customer->location_id = $request->input('location_id');
    $customer->customer_image = $customer_image;
    $customer->notes = $request->input('notes');

    // Auto set user_id and added_by for branch 1
    $customer->user_id = 1;
    $customer->added_by = 'system';

    $customer->save();

    $updatedData = $customer->only([
        'customer_name',  'phone', 'customer_image',
        'notes',  'user_id', 'added_by'
    ]);

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'customers';
    $history->function = 'update';
    $history->function_status = 1;
    $history->branch_id = 1;
    $history->record_id = $customer->id;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($updatedData);
    $history->added_by = 'admin';
    $history->save();

    return response()->json(['success' => 'customer updated successfully']);
}

public function delete_customer(Request $request)
{
    $customer = Customer::find($request->input('id'));
    if (!$customer) {
        return response()->json(['error' => 'customer not found'], 404);
    }

    $previousData = $customer->only([
        'customer_name', 'user_name', 'phone', 'email', 'customer_image',
        'branch_id',  'notes',
        'user_id', 'added_by', 'created_at'
    ]);

    if (!empty($customer->customer_image)) {
        $imagePath = public_path('images/customer_images/' . $customer->customer_image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'customers';
    $history->branch_id = 1;
    $history->function = 'delete';
    $history->function_status = 2;
    $history->record_id = $customer->id;
    $history->previous_data = json_encode($previousData);
    $history->added_by = 'admin';
    $history->save();

    $customer->delete();

    return response()->json(['success' => 'customer deleted successfully']);
}


}
