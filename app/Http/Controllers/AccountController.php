<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Balance;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class AccountController extends Controller
{



public function index()
{

    // if (!Auth::check()) {
    //     return redirect()->route('login_page')->with('error', 'Please login first');
    // }


    // $permissions = explode(',', Auth::user()->permissions ?? '');


    // if (!in_array('11', $permissions)) {
    //     return redirect()->route('login_error')->with('error', 'Permission denied');
    // }





    return view('expense.account');
}



    public function show_account()
    {
        $sno=0;

        //  $user = Auth::user();


            $accounts = Account::all();

        if(count($accounts)>0)
        {
            foreach($accounts as $value)
            {

                $account_name='<a href="javascript:void(0);">'.$value->account_name.'</a>';
                $modal = '
                        <a href="javascript:void(0);" class="me-1 edit-staff" data-bs-toggle="modal" data-bs-target="#add_account_modal" onclick=edit("'.$value->id.'")>
                            <i class="fa fa-pencil fs-18 text-success"></i>
                        </a>';

                    // if (Auth::user()->user_type == 1) {
                    //     $modal .= '
                    //         <a href="javascript:void(0);" class="me-1" onclick=del("'.$value->id.'")>
                    //             <i class="fa fa-trash fs-18 text-danger"></i>
                    //         </a>';
                    // }

                $account_type= "";
                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                if($value->account_type==1) {
                    $account_type = 'normal_account';
                } elseif($value->account_type==2) {
                    $account_type = 'saving_account';
                }
                 else {
                    $account_type = 'Cash';
                }

                if ($value->account_type == 2) { // assuming 2 = saving_account
                    $modal .= '
                    <a href="javascript:void(0);" class="view-details" onclick="viewDetails(' . $value->id . ')" data-bs-toggle="tooltip" title="Add Balance">
                        <i class="fas fa-info-circle fs-18 text-primary"></i>
                    </a>
                    <a href="' . url('all_balance/' . $value->id) . '" class="view-details" data-bs-toggle="tooltip" title="View Balance History">
                        <i class="fas fa-credit-card fs-18 text-primary"></i>
                    </a>
                    ';
                }


                $sno++;
                $json[] = array(
                    $account_name .'<br> '.(  $value->account_branch),
                    $value->account_no .'<br> '. $account_type,
                    $value->opening_balance,
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

    public function add_account(Request $request)
    {
        // $user_id = Auth::id();
        // $data = User::where('id', $user_id)->first();
        // $user = $data->user_name;
        // $branch_id = $request['branch_id'];

        // Check if the account type is 2 (saving account)


        $account = new Account();
        $account->account_name = $request['account_name'];
        $account->account_branch = $request['account_branch'];
        $account->account_no = $request['account_no'];
        $account->opening_balance = $request['opening_balance'];
        $account->commission = $request['commission'];
        $account->account_type = $request['account_type'];
        $account->account_status = $request['account_status'];
        $account->notes = $request['notes'];
        $account->added_by = 'system';
        $account->user_id = 1;
        $account->save();

        return response()->json(['account_id' => $account->id]);
    }


    public function edit_account(Request $request){
        $account_id = $request->input('id');
        $account_data = Account::where('id', $account_id)->first();



        if (!$account_data) {
            return response()->json(['error' => trans('messages.account_not_found_lang', [], session('locale'))], 404);
        }
        $data = [
            'account_id' => $account_data->id,
            'account_name' => $account_data->account_name,
            'account_branch' => $account_data->account_branch,
            'account_no' => $account_data->account_no,
            'opening_balance' => $account_data->opening_balance,
            'commission' => $account_data->commission,
            'account_type' => $account_data->account_type,
            'account_status' => $account_data->account_status,
            'notes' => $account_data->notes,
        ];

        return response()->json($data);
    }

    public function update_account(Request $request) {
        $account_id = $request->input('account_id');
        $account = Account::where('id', $account_id)->first();


        if (!$account) {
            return response()->json(['error' => trans('messages.account_not_found_lang', [], session('locale'))], 404);
        }




        // Store previous data for history
        $previousData = $account->only([
            'account_name', 'account_branch', 'account_no', 'opening_balance',
            'commission', 'account_type', 'account_status',  'notes', 'added_by', 'user_id'
        ]);

        // Fetch logged-in user details


        // Update account details
        $account->account_name = $request->input('account_name');
        $account->account_branch = $request->input('account_branch');
        $account->account_no = $request->input('account_no');
        $account->opening_balance = $request->input('opening_balance');
        $account->commission = $request->input('commission');
        $account->account_type = $request->input('account_type');
        $account->account_status = $request->input('account_status');
        $account->notes = $request->input('notes');
        $account->added_by = 'system';
        $account->user_id = 1;
        $account->save();

        // Store update history
        $history = new History();
        $history->user_id = 1;
        $history->table_name = 'accounts'; // Corrected table name
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = 1;

        $history->record_id = $account->id; // Corrected to account ID
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($account->only([
            'account_name', 'account_branch', 'account_no', 'opening_balance',
            'commission', 'account_type', 'account_status',  'notes', 'added_by', 'user_id'
        ]));
        $history->added_by = 'system';
        $history->save();

        return response()->json(['success' => trans('messages.data_update_success_lang', [], session('locale'))]);
    }


    public function delete_account(Request $request){

        // $user_id = Auth::id();
        // $user= User::where('id', $user_id)->first();
        // $branch_id= $user->branch_id;

        $account_id = $request->input('id');
        $account = Account::where('id', $account_id)->first();
        if (!$account) {
            return response()->json(['error' => trans('messages.account_not_found_lang', [], session('locale'))], 404);
        }

        $previousData = $account->only([
            'account_name', 'account_branch', 'account_no', 'opening_balance',
            'commission', 'account_type', 'account_status',  'notes', 'added_by', 'user_id'
        ]);

        $history = new History();
        $history->user_id = 1;
        $history->table_name = 'accounts'; // Corrected table name
        $history->function = 'delete';
        $history->function_status = 2;
        $history->branch_id =1;
        $history->record_id = $account->id; // Corrected to account ID
        $history->previous_data = json_encode($previousData);
        $history->added_by = 'system';
        $history->save();
        $account->delete();
        return response()->json(['success' => trans('messages.delete_success_lang', [], session('locale'))]);
    }




    public function getAccountDetail($id)
{

    $account_data = Account::find($id);

    if (!$account_data) {
        return response()->json(['error' => 'Account not found'], 404);
    }

    return response()->json([
        'account_name' => $account_data->account_name,
        'account_id' => $account_data->id,
        'opening_balance' => $account_data->opening_balance,
    ]);
}


public function add_balance(Request $request){


    // $user_id = Auth::id();
    // $data= User::where('id', $user_id)->first();
    // $user= $data->user_name;
    // $branch_id=  $request['branch_id'];

    $account_id=  $request['balance_account_id'];

    $balance_account = Account::where('id',  $account_id)->first();
    $old_balance = $balance_account->opening_balance;

    $balance_account->opening_balance = $old_balance + $request['new_balance'];
    $balance_account->save();


    $account = new Balance();

    $expense_file = "";

    // Handle the file upload
    if ($request->hasFile('balance_file')) {
        $folderPath = public_path('uploads/balance_files');

        // Check if the folder exists, if not create it
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }

        // Create a unique filename
        $balance_file = time() . '.' . $request->file('balance_file')->extension();
        $request->file('balance_file')->move($folderPath, $balance_file);
    }

    $account->account_name = $request['balance_name'];
    $account->account_id = $account_id;
    $account->source = 'Balance';
    $account->account_no = $balance_account->account_no;
    $account->previous_balance = $old_balance;
    $account->balance_date = $request['balance_date'];
    $account->new_total_amount = $request['amount'];
    $account->new_balance = $request['new_balance'];
    $account->balance_image = $balance_file; // Save the file name in the database
    $account->notes = $request['notes'];
    $account->added_by = 'system';
    $account->user_id =  1;


    $account->save();
    return response()->json(['account_id' => $account->id]);

}

public function all_balance($id){

    $id=$id;
    $account= Account::where('id', $id)->first();
    return view('expense.all_balance', compact('id', 'account'));
}

public function show_balance(Request $request){

    $balanceId = $request->input('balance_id');

    $sno=0;
    $view_account= Balance::where('account_id',  $balanceId)->get();
    if(count($view_account)>0)
    {
        foreach($view_account as $value) {

            $account_name = $value->account_name;
            $account_number = $value->account_no;
            $pre_blnc = $value->previous_balance;
            $new_blnc = $value->new_balance;
            $new_total = $value->new_total_amount;
            $soucre = $value->source;
            $expense_name = $value->expense_name;
            $expense_amount = $value->expense_amount;
            $expense_date = $value->expense_date;
            $balance_date = $value->balance_date;

            $detail = !empty($expense_name) ? $expense_name : 'Balance Added';



            $added = !empty($value->expense_added_by) ? $value->expense_added_by : $value->added_by;

            $final_date = !empty($expense_date) ? $expense_date : $balance_date;
           $expense_display = ($expense_amount > 0)
                ? '<span class="badge bg-danger">' . $expense_amount . ' ⬇</span>'
                : $expense_amount ?? 0;

            $new_blnc_display = ($new_blnc > 0)
                ? '<span class="badge bg-success">' . $new_blnc . ' ⬆</span>'
                : $new_blnc ?? 0;



                $expense_file = $value->expense_image;
                $balance_file = $value->balance_image;

                $display_file = null;
                $file_type = null;

                if (!empty($expense_file) && file_exists(public_path('uploads/expense_files/' . $expense_file))) {
                    $display_file = $expense_file;
                    $file_type = 'expense';
                } elseif (!empty($balance_file) && file_exists(public_path('uploads/balance_files/' . $balance_file))) {
                    $display_file = $balance_file;
                    $file_type = 'balance';
                }

                $file_display = '';
                if ($display_file) {
                    $file_path = asset("uploads/{$file_type}_files/" . $display_file);
                    $file_extension = pathinfo($display_file, PATHINFO_EXTENSION);
                    $download_path = url("download_{$file_type}_image/" . $display_file);

                    $dummy_icons = [
                        'pdf'  => asset('images/dummy_images/pdf.png'),
                        'doc'  => asset('images/dummy_images/word.jpeg'),
                        'docx' => asset('images/dummy_images/word.jpeg'),
                        'xls'  => asset('images/dummy_images/excel.jpeg'),
                        'xlsx' => asset('images/dummy_images/excel.jpeg'),
                    ];

                    $download_icon = "<a href='{$download_path}' download title='Download'>
                                        <img src='" . asset('images/dummy_images/download.png') . "' alt='Download' width='20'>
                                    </a>";

                    if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $file_display = "<img src='{$file_path}' alt='Receipt' width='30' height='30'> {$download_icon}";
                    } else {
                        $icon_path = $dummy_icons[strtolower($file_extension)] ?? asset('images/dummy_images/file.png');
                        $file_display = "<a href='{$file_path}' target='_blank'>
                                            <img src='{$icon_path}' alt='File' width='30' height='30'>
                                        </a> {$download_icon}";
                    }
                } else {
                    $no_image = asset('images/dummy_images/no_image.jpg');
                    $file_display = "<img src='{$no_image}' alt='No Image' width='50' height='50'>";
                }


            $sno++;
            $json[] = array(
                '<span class="badge bg-primary">' . $soucre . '</span>',
                $pre_blnc,
                $expense_display,
                $new_blnc_display,
                $new_total,
                $added,
                $final_date,
                $file_display,


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


public function downloadExpenseImage($filename)
{
    $path = public_path('uploads/expense_files/' . $filename);

    if (File::exists($path)) {
        return Response::download($path);
    } else {
        return response()->json(['error' => 'File not found.'], 404);
    }
}

public function downloadBalanceImage($filename)
{
    $path = public_path('uploads/balance_files/' . $filename);

    if (File::exists($path)) {
        return Response::download($path);
    } else {
        return response()->json(['error' => 'File not found.'], 404);
    }
}

}
