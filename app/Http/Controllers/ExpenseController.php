<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Balance;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\History;
use App\Models\Expensecat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Response;

class ExpenseController extends Controller
{
    public function index()
    {


    
            if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('8', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }


        $view_account = Account::get();
        $expense_cats = Expensecat::all();




        return view('expense.expense', compact('view_account',  'expense_cats'));


    }

    public function show_expense()
    {
        $sno = 0;


            // Super admin: view all expenses
            $view_expense = Expense::all();

        if (count($view_expense) > 0) {
            foreach ($view_expense as $value) {

                $expense_name = '<a href="javascript:void(0);">' . $value->expense_name . '</a>';

                $cat_name = Expensecat::where('id', $value->category_id)->value('expense_category_name');
                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_expense_modal" onclick=edit("' . $value->id . '")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("' . $value->id . '")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

                $file_path = asset('uploads/expense_files/' . $value->expense_image);
                $file_extension = pathinfo($value->expense_image, PATHINFO_EXTENSION);
                $download_path = url('download_expense_image/' . $value->expense_image); // Route for download

                // Define dummy icons for non-image files
                $dummy_icons = [
                    'pdf'  => asset('images/dummy_images/pdf.png'),
                    'doc'  => asset('images/dummy_images/word.jpeg'),
                    'docx' => asset('images/dummy_images/word.jpeg'),
                    'xls'  => asset('images/dummy_images/excel.jpeg'),
                    'xlsx' => asset('images/dummy_images/excel.jpeg'),
                ];

                $download_icon = "<a href='{$download_path}' download title='Download'><img src='" . asset('images/dummy_images/download.png') . "' alt='Download' width='20'></a>";

                // Check if the file exists
                if (!empty($value->expense_image) && file_exists(public_path('uploads/expense_files/' . $value->expense_image))) {
                    if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $file_display = "<img src='{$file_path}' alt='Receipt' width='30' height='30'> {$download_icon}";
                    } else {
                        $icon_path = $dummy_icons[$file_extension] ?? asset('images/dummy_images/file.png');
                        $file_display = "<a href='{$file_path}' target='_blank'><img src='{$icon_path}' alt='File' width='30' height='30'></a> {$download_icon}";
                    }
                } else {
                    // Show "No Image" placeholder
                    $no_image = asset('images/dummy_images/no_image.jpg');
                    $file_display = "<img src='{$no_image}' alt='No Image' width='50' height='50'>";
                }

                $expense_type_display = $value->expense_type . ' (' . $value->recurring_frequency . ')';

                $sno++;
                $json[] = array(
                    $sno,
                    $expense_name,
                    $cat_name,
                    $value->amount,
                    $value->expense_date,
                    $expense_type_display,
                    $file_display,
                    $value->added_by,
                    $add_data,
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


    public function add_expense(Request $request)
    {
        // $user_id = Auth::id();
        // $data = User::where('id', $user_id)->first();
        // $user = $data->user_name;

        $expense = new Expense();
        $expense_file = "";

        // Handle the file upload
        if ($request->hasFile('expense_file')) {
            $folderPath = public_path('uploads/expense_files');

            // Check if the folder exists, if not create it
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            // Create a unique filename
            $expense_file = time() . '.' . $request->file('expense_file')->extension();
            $request->file('expense_file')->move($folderPath, $expense_file);
        }

        // Save expense details
        $expense->category_id = $request['category_id'];
        $expense->expense_name = $request['expense_name'];

        $expense->payment_method = $request['account_id'];
        $expense->amount = $request['amount'];
        $expense->expense_date = $request['expense_date'];
        $expense->notes = $request['notes'];
        $expense->expense_image = $expense_file; // Save the file name in the database
        $expense->added_by ='system';
        $expense->user_id = 1;
        $expense->expense_type = $request['expense_type'];
        $expense->recurring_frequency = null;

        if ($request['expense_type'] === 'fixed') {
            if (empty($request['recurring_frequency'])) {
                return response()->json(['error' => 'Recurring frequency is required for fixed expenses'], 422);
            }
            $expense->recurring_frequency = $request['recurring_frequency'];
        }
        $expense->save();

        // Handle account data
        // $account_data = Account::where('id', $request['account_id'])->first();
        // if ($account_data) {
        //     $opening_balance = $account_data->opening_balance ?? 0;
        //     // Make sure you're subtracting the amount correctly
        //     $new_amount = $opening_balance - $request['amount'];

        //     // Update account balance
        //     $account_data->opening_balance = $new_amount;
        //     $account_data->updated_by = 'sustem';
        //     $account_data->save();

        //     // Create a balance entry
        //     $blnc = new Balance();
        //     $blnc->account_name = $account_data->account_name ?? '';
        //     $blnc->account_id = $account_data->id;
        //     $blnc->account_no = $account_data->account_no;
        //     $blnc->previous_balance = $opening_balance;
        //     $blnc->new_total_amount = $new_amount;
        //     $blnc->source = 'Expense';
        //     $blnc->expense_amount = $expense->amount;
        //     $blnc->expense_name = $expense->expense_name;
        //     $blnc->expense_date = $expense->expense_date;
        //     $blnc->expense_added_by = 'sustem';
        //     $blnc->expense_image = $expense->expense_image; // Save the file name in the database
        //     $blnc->notes = $expense->notes;
        //     $blnc->added_by = 'sustem';
        //     $blnc->user_id = 1;
        //     $blnc->save();
        // } else {
        //     // Handle case where account is not found, maybe return an error
        //     return response()->json(['error' => 'Account not found'], 404);
        // }

        return response()->json(['expense_id' => $expense->id]);
    }


    public function edit_expense(Request $request)
    {
        $expense_id = $request->input('id');
        // Use the Eloquent where method to retrieve the expense by column name
        $expense_data = Expense::where('id', $expense_id)->first();

        if (!$expense_data) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_not_found', [], session('locale'))], 404);
        }

        // Check the file extension and prepare appropriate icon or image preview
        $expense_image = $expense_data->expense_image;
        $file_url = null;
        $file_icon = null;
        $file_type = null;

        if ($expense_image) {
            $extension = strtolower(pathinfo($expense_image, PATHINFO_EXTENSION)); // lowercased
            $file_path = 'images/expense_images/' . $expense_image;

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $file_type = 'image';
                $file_icon = asset($file_path); // actual image for preview
                $file_url  = asset($file_path); // actual file URL
            } elseif ($extension === 'pdf') {
                $file_type = 'pdf';
                $file_icon = asset('images/dummy_images/pdf.png');
                $file_url  = asset($file_path);
            } elseif (in_array($extension, ['doc', 'docx'])) {
                $file_type = 'word';
                $file_icon = asset('images/dummy_images/word.jpeg');
                $file_url  = asset($file_path);
            } elseif (in_array($extension, ['xls', 'xlsx'])) {
                $file_type = 'excel';
                $file_icon = asset('images/dummy_images/excel.jpeg');
                $file_url  = asset($file_path);
            } else {
                $file_type = 'other';
                $file_icon = asset('images/dummy_images/file.png');
                $file_url  = asset($file_path);
            }
        }

    $data = [
        'expense_id'           => $expense_data->id,
        'expense_name'         => $expense_data->expense_name,
        'category_id'          => $expense_data->category_id,
        'amount'               => $expense_data->amount,
        'expense_date'         => $expense_data->expense_date,
        'category_image'       => $expense_data->category_image,
        'notes'                => $expense_data->notes,
        'expense_type'         => $expense_data->expense_type,
        'recurring_frequency'  => $expense_data->recurring_frequency,
        'expense_image'        => $file_icon,
        'file_url'             => $file_url,
        'file_type'            => $file_type,
    ];

        return response()->json($data);
    }



    public function update_expense(Request $request)
    {
        // $user_id = Auth::id();
        // $data = User::where('id', $user_id)->first(); // No need to call `first()` after `find()`
        // 'system' = $data->user_name; // Corrected variable name
        // $branch_id = $data->branch_id;

        $expense_id = $request->input('expense_id');
        $expense = Expense::where('id', $expense_id)->first();

        if (!$expense) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_not_found', [], session('locale'))], 404);
        }

        // Capture the previous data before the update
        $previousData = $expense->toArray();

        // Store file if provided
        $expense_file = $expense->expense_image; // Keep the old file if no new file is uploaded.

        if ($request->hasFile('expense_file')) {
            $folderPath = public_path('uploads/expense_files');

            // Check if the folder exists, if not create it
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            // Create a unique filename
            $expense_file = time() . '.' . $request->file('expense_file')->extension();

            // Move the file
            $request->file('expense_file')->move($folderPath, $expense_file);

            // Optionally delete the old file if a new one is uploaded
            if ($expense->expense_image && file_exists(public_path('uploads/expense_files/' . $expense->expense_image))) {
                unlink(public_path('uploads/expense_files/' . $expense->expense_image));
            }
        }

        // Save updated expense details
        $expense->category_id = $request['category_id'];
        $expense->expense_name = $request['expense_name'];
        $expense->payment_method = $request['account_id'];
        $expense->amount = $request['amount'];
        $expense->expense_date = $request['expense_date'];
        $expense->notes = $request['notes'];
        $expense->expense_image = $expense_file; // Save the file name in the database
        $expense->added_by = 'system';
        $expense->user_id = 1;
             $expense->expense_type = $request['expense_type'];
        $expense->recurring_frequency = null;

        if ($request['expense_type'] === 'fixed') {
            if (empty($request['recurring_frequency'])) {
                return response()->json(['error' => 'Recurring frequency is required for fixed expenses'], 422);
            }
            $expense->recurring_frequency = $request['recurring_frequency'];
        }
        $expense->save();


        // Log the update in the history table
        $history = new History();
        $history->user_id = 1;
        $history->table_name = 'expenses'; // Corrected table name to 'expenses'
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = 1;

        $history->record_id = $expense->id; // Use expense id as the record_id
        $history->previous_data = json_encode($previousData); // Store the previous data
        $history->updated_data = json_encode($expense->only([
            'category_id',
            'expense_name',
            'payment_method',
            'amount',
            'expense_date',
            'notes',
            'expense_image',
            'added_by',
            'user_id'
        ])); // Store the updated data
        $history->added_by = 'system';
        $history->save();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.expense_update_lang', [], session('locale'))
        ]);
    }



    public function delete_expense(Request $request)
    {
        $expense_id = $request->input('id');
             $expense = Expense::where('id', $expense_id)->first();

            if ($expense) {
                $account = Account::where('id', $expense->account_id)->first();

                if ($account) {
                    $account->opening_balance += $expense->expense_amount; // add the expense amount
                    $account->save(); // save the updated balance
                }
            }


        if (!$expense) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_not_found', [], session('locale'))], 404);
        }

        // Capture the previous data before the delete
        $previousData = $expense->only([
            'category_id',
            'expense_name',
            'payment_method',
            'amount',
            'expense_date',
            'notes',
            'expense_image',
            'added_by',
            'user_id',
            'created_at'
        ]);

        // Get the current user
        // $user_id = Auth::id();
        // $data = User::where('id', $user_id)->first();
        //  = $data->user_name;
        // $branch_id = $data->branch_id;


        // Log the deletion in the history table
        $history = new History();
        $history->user_id = 1;
        $history->table_name = 'expenses'; // Table name is 'expenses'
        $history->function = 'delete';
        $history->function_status = 1;
        $history->branch_id = 1;
        $history->record_id = $expense->id; // Use expense id as the record_id
        $history->previous_data = json_encode($previousData); // Store the previous data (before deletion)
        $history->updated_data = null; // No updated data since it's a delete operation
        $history->added_by = 'system';
        $history->save();

        // Delete the expense
        $expense->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.expense_deleted_lang', [], session('locale'))
        ]);
    }


    // download
    public function download_expense_image($filename)
    {
        $filePath = public_path('uploads/expense_files/' . $filename);

        if (File::exists($filePath)) {
            return Response::download($filePath, $filename);
        } else {
            return redirect()->back()->with('error', 'File not found!');
        }
    }
}
