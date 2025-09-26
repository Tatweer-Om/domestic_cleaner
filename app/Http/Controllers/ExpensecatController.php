<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Expensecat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensecatController extends Controller
{
      public function index()
{
    if (!Auth::check()) {
        return redirect()->route('login_page')
            ->with('error', trans('messages.please_login_first', [], session('locale')));
    }

    $permissions = explode(',', Auth::user()->permissions ?? '');

    if (!in_array('8', $permissions)) {
        return redirect()->route('login_error')
            ->with('error', trans('messages.permission_denied', [], session('locale')));
    }

    return view('expense.expense_cat');
}


public function show_expense_category()
{
    $sno=0;

    $view_expense_category= Expensecat::all();
    if(count($view_expense_category)>0)
    {
        foreach($view_expense_category as $value)
        {

            $expense_category_name='<a href="javascript:void(0);">'.$value->expense_category_name.'</a>';

            $modal = '
            <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_expense_category_modal" onclick=edit("'.$value->id.'")>
                <i class="fa fa-pencil fs-18 text-success"></i>
            </a>
            <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                <i class="fa fa-trash fs-18 text-danger"></i>
            </a>';

        $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

            $sno++;
            $json[]= array(
                        $sno,
                        $expense_category_name,
                        $value->added_by,
                        $add_data,
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

public function add_expense_category(Request $request){


    // $user_id = Auth::id();
    // $data= User::where('id', $user_id)->first();
    // $user= $data->user_name;

    $expense_category = new Expensecat();
    $expense_category->expense_category_name = $request['expense_category_name'];
    $expense_category->added_by = 'system';
    $expense_category->user_id = 1;
    $expense_category->save();
    return response()->json(['expense_category_id' => $expense_category->id]);

}

public function edit_expense_category(Request $request){


    $expense_category_id = $request->input('id');
    $expense_category_data = Expensecat::where('id', $expense_category_id)->first();

    if (!$expense_category_data) {
        return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_category_not_found', [], session('locale'))], 404);
    }
    // Add more attributes as needed
    $data = [
        'expense_category_id' => $expense_category_data->id,
        'expense_category_name' => $expense_category_data->expense_category_name,
    ];

    return response()->json($data);
}

public function update_expense_category(Request $request){

    // $user_id = Auth::id();
    // $data= User::find( $user_id)->first();
    // $user= $data->user_name;
    // $branch_id= $data->branch_id;

    $expense_category_id = $request->input('expense_category_id');
    $expense_category = Expensecat::where('id', $expense_category_id)->first();
    if (!$expense_category) {
        return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_category_not_found', [], session('locale'))], 404);
    }

 $previousData = $expense_category->only(['expense_category_name', 'branch_id', 'added_by', 'user_id', 'created_at']);

    $expense_category->expense_category_name = $request->input('expense_category_name');
     $expense_category->updated_by = 'system';
    $expense_category->save();

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'expense_category';
    $history->function = 'update';
    $history->function_status = 1;
    $history->branch_id = 1;
    $history->record_id = $expense_category->id;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($expense_category->only([
        'expense_category_name', 'branch_id', 'added_by', 'user_id',
    ]));
    $history->added_by = 'system';
    $history->save();
    return response()->json([
        trans('messages.success_lang', [], session('locale')) => trans('messages.expense_category_update_lang', [], session('locale'))
    ]);
}

public function delete_expense_category(Request $request){

    // $user_id = Auth::id();
    // $data= User::find( $user_id)->first();
    // $user= $data->user_name;
    // $branch_id= $data->branch_id;

    $expense_category_id = $request->input('id');
    $expense_category = Expensecat::where('id', $expense_category_id)->first();
    $previousData = $expense_category->only(['expense_category_name', 'branch_id', 'added_by', 'user_id', 'created_at']);

    if (!$expense_category) {
        return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_category_not_found', [], session('locale'))], 404);
    }


    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'expense_category';
    $history->function = 'delete';
    $history->function_status = 2;
    $history->branch_id = 1;
    $history->record_id = $expense_category->id;
    $history->previous_data = json_encode($previousData);
    $history->added_by = 'system';
    $history->save();
    $expense_category->delete();
    return response()->json([
        trans('messages.success_lang', [], session('locale')) => trans('messages.expense_category_deleted_lang', [], session('locale'))
    ]);
}
}
