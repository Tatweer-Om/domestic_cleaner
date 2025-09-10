<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visit;
use App\Models\Worker;
use App\Models\Booking;
use App\Models\History;
use App\Models\Package;
use App\Models\Voucher;
use App\Models\Customer;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{


  public function save_booking(Request $request)
{
    $data = $request->all();

    $worker = Worker::findOrFail($data['worker_id']);
    $location_id = $worker->location_id;

    if (is_null($location_id)) {
        return response()->json(['error' => 'Worker is not assigned to any location.'], 422);
    }

    if (!isset($data['visits']) || !is_array($data['visits']) || empty($data['visits'])) {
        return response()->json(['error' => 'At least one visit is required.'], 422);
    }


    $user = Auth::user();
    $user_id = Auth::id(); // null if guest
    $user_name = $user ? $user->user_name : null; // null if guest

    dd($user_name);
    $customer = $user_id ? Customer::where('user_id', $user_id)->first() : null;
    $customer_id = $customer ? $customer->id : null;

    try {
        $booking = DB::transaction(function () use ($data, $user_id, $user_name, $customer_id, $location_id) {
            $year = date('Y');

            // Get the last booking for this year whose booking_no matches B{year}-{seq}
            $lastBooking = Booking::whereYear('created_at', $year)
                ->where('booking_no', 'like', "B{$year}-%")
                ->orderByDesc('id') // fallback ordering
                ->first();

            $lastSeq = 0;
            if ($lastBooking && preg_match('/^B' . $year . '-(\d+)$/', $lastBooking->booking_no, $m)) {
                $lastSeq = (int) $m[1];
            }

            $nextSeq = $lastSeq + 1;
            $bookingNo = "B{$year}-{$nextSeq}";

            $booking = Booking::create([
                'booking_no'     => $bookingNo,      // ✅ use booking_no consistently
                'user_id'        => $user_id,
                'worker_id'      => $data['worker_id'],
                'package_id'     => $data['package_id'],
                'location_id'    => $location_id,
                'start_date'     => $data['start_date'],
                'duration'       => $data['duration'],
                'visits'         => json_encode($data['visits']),
                'visits_count'   => count($data['visits']),
                'status'         => 1,
                'added_by'       => $user_name,
                'customer_id'    => $customer_id,
            ]);

          $counter = 1; // keep track of visit number

            foreach ($data['visits'] as $visit) {
                Visit::create([
                    'booking_id'   => $booking->id,
                    'visit_date'   => $visit['date'],
                    'shift'        => $visit['shift'],
                    'duration'     => $visit['duration'],
                    'visit_name'   => $booking->booking_no . '-v' . $counter, // 🔹 auto-generated
                    'worker_id'    => $booking->worker_id,
                    'location_id'  => $booking->location_id,
                    'user_id'      => $user_id,
                    'status'       => 1,
                    'added_by'     => $user_name,
                    'customer_id'  => $customer_id,
                ]);

                $counter++; // increment for next visit
            }

            return $booking;
        });

        return response()->json([
            'ok'          => true,
            'booking_id'  => $booking->id,
            'booking_no'  => $booking->booking_no,  // ✅ return the correct field
            'message'     => 'Booking saved successfully.',
            'redirect'    => $user_id
        ? url("checkout/{$booking->booking_no}")   // if user is logged in
        : '#',
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to save booking: ' . $e->getMessage()], 500);
    }
}




    public function checkout($id)
    {
        // Find the booking for the given worker_id
        $booking = Booking::where('booking_no', $id)->first();

        // Check if booking exists
        if (!$booking) {
            return redirect()->back()->with('error', 'No booking found for this worker.');
        }

        // Find the package associated with the booking
        $package = Package::where('id', $booking->package_id)->first();

        // Check if package exists
        if (!$package) {
            return redirect()->back()->with('error', 'Package not found for this booking.');
        }

        // Determine package price based on duration
        $package_price = $booking->duration == 4 ? $package->package_price_4 : ($booking->duration == 5 ? $package->package_price_5 : 0);

        // Validate duration
        if ($package_price === 0) {
            return redirect()->back()->with('error', 'Invalid booking duration.');
        }

        // Fetch worker details
        $worker = Worker::findOrFail($booking->worker_id);

        // Fetch visits directly from visits table for worker_id
        $visits = [];
        $visit_records = Visit::where('booking_id', $booking->id)->get();
        if ($visit_records->isNotEmpty()) {
            foreach ($visit_records as $visit) {
                $shift = $visit->shift ?? 'unknown';
                $shift_display = $shift === 'morning' ? 'Morning (8:00 AM - 1:00 PM)' : ($shift === 'evening' ? 'Evening (4:00 PM - 9:00 PM)' : $shift);
                $visits[] = [
                    'visit_date' => $visit->visit_date ?? 'Unknown',
                    'shift' => $shift_display,
                ];
            }
        }

        // Fallback if no visits
        if (empty($visits)) {
            $visits = [
                ['visit_date' => '2025-08-25', 'shift' => 'Morning (8:00 AM - 1:00 PM)'],
                ['visit_date' => '2025-08-26', 'shift' => 'Evening (4:00 PM - 9:00 PM)'],
                ['visit_date' => '2025-08-27', 'shift' => 'Morning (8:00 AM - 1:00 PM)'],
            ];
        }

        // Prepare booking details for the view
        $booking_details = [
            'package' => $package->package_name,
            'worker' => $worker->worker_name,
            'location' => $booking->location_id ? \App\Models\Location::find($booking->location_id)->location_name ?? 'Muscat, Oman' : 'Muscat, Oman',
            'visits' => $visits,
            'subtotal' => $package_price,
            'discount' => $booking->discount ?? 0,
            'total_amount' => $package_price - ($booking->discount ?? 0),
        ];

        return view('web_pages.payment', [
            'worker_id' => $id,
            'booking_details' => $booking_details,
        ]);
    }

    public function voucher_apply(Request $request)
    {

        dd($request->all());

        // Validate request
        // dd($voucherCode = $request->input('code'));

        $code  = strtoupper(trim($data['code']));
        $total = (float) $data['total_amount'];

        // Find voucher by code (case-insensitive)
        $voucher = Voucher::whereRaw('UPPER(voucher_name) = ?', [$code])->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => __('messages.invalid_voucher', [], session('locale')) ?? 'Invalid voucher code.',
            ], 422);
        }

        $voucherAmount = (float) ($voucher->voucher_amount ?? 0);

        // Safety: never discount below 0, and never more than total
        $voucherAmount = max(0, min($voucherAmount, $total));

        return response()->json([
            'success'        => true,
            'voucher_amount' => $voucherAmount,
            'message'        => __('messages.voucher_applied', [], session('locale')) ?? 'Voucher applied.',
        ]);
    }


    public function show_booking()
    {
        $sno = 0;
        $bookings = Booking::all(); // No user-type filtering

        if ($bookings->count() > 0) {
            foreach ($bookings as $booking) {
                $booking_id = $booking->id;
                $modal = '
                <a href="javascript:void(0);" onclick="cancel(' . $booking->id . ')" title="Cancel Booking">
                    <i class="fa fa-times-circle fs-18 text-warning"></i>
                </a>
                ';


                $add_data = Carbon::parse($booking->created_at)->format('d-m-Y (h:i a)');
                $location = Location::where('id', $booking->location_id)->value('location_name');
                $customer = User::where('id', $booking->user_id)->value('user_name');
                $customer_phone = User::where('id', $booking->user_id)->value('user_phone');
                $booking_date = Carbon::parse($booking->start_date)->format('d-m-Y');
                $package = Package::where('id', $booking->package_id)->value('package_name');

                $package_price = null;

                if ($booking->duration == 4) {
                    $package_price = Package::where('id', $booking->package_id)->value('package_price_4');
                } elseif ($booking->duration == 5) {
                    $package_price = Package::where('id', $booking->package_id)->value('package_price_5');
                }

                $booking_status = '';

                if ($booking->status == 1) {
                    $booking_status = '<span class="text-warning">●</span> ' . trans('messages.pending', [], session('locale'));
                } elseif ($booking->status == 2) {
                    $booking_status = '<span class="text-success">●</span> ' . trans('messages.completed', [], session('locale'));
                } elseif ($booking->status == 3) {
                    $booking_status = '<span class="text-danger">●</span> ' . trans('messages.cancelled', [], session('locale'));
                } elseif ($booking->status == 4) {
                    $booking_status = '<span class="text-primary">●</span> ' . trans('messages.on_going', [], session('locale'));
                } else {
                    $booking_status = '<span class="text-muted">●</span> -';
                }


                $sno++;
                $json[] = array(
                    '<span class="booking-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2"> ' . $booking->booking_no . '</span>',
                    '<span class="text-nowrap ms-2"> ' . $booking_date . '</span>',
                    '<span class="text-primary">' . $customer . '</span>',
                    '<span class="text-primary">' . $customer_phone . '</span>',
                    '<span class="text-primary">' . $location . '</span>',
                    '<span class="text-primary">' . $booking_status . '</span>',
                    '<span class="text-primary">' . $booking->visits_count . '</span>',
                    '<span class="text-primary">' . $booking->duration . '</span>',
                    '<span class="text-primary">' . $package . '</span>',
                    '<span class="text-primary">' . $package_price . '</span>',
                    '<span>' . $booking->added_by . '</span>',
                    '<span>' . $add_data . '</span>',
                    $modal
                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);
        }

        return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
    }


    public function all_bookings()
    {
        return view('bookings.admin_bookings');
    }




    public function cancel_booking(Request $request)
    {



        $booking_id = $request->input('id');
        $booking = Booking::where('id', $booking_id)->first();

        if (!$booking) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.booking_not_found', [], session('locale'))], 404);
        }

        $booking->status = 3;
        $booking->save();


        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.booking_cancelled_lang', [], session('locale'))
        ]);
    }



    public function show_visit()
    {
        $sno = 0;
        $visits = Visit::all(); // No user-type filtering

        if ($visits->count() > 0) {
            foreach ($visits as $visit) {
                $visit_id = $visit->id;
                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_visit_modal" onclick=edit_visit("' . $visit->id . '")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                   <a href="javascript:void(0);" class="me-3 " data-bs-toggle="modal" data-bs-target="#add_condition_modal" onclick=condition("' . $visit->id . '")>
                    <i class="fa fa-book fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("' . $visit->id . '")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data = Carbon::parse($visit->created_at)->format('d-m-Y (h:i a)');
                $location_id = Booking::where('id', $visit->booking_id)->value('location_id');
                $location = Location::where('id', $location_id)->value('location_name');
                $booking_no = Booking::where('id', $visit->booking_id)->value('booking_no');
                $customer = User::where('id', $visit->user_id)->value('user_name');
                $visit_date = Carbon::parse($visit->visit_date)->format('d-m-Y');
                $worker = Worker::where('id', $visit->worker_id)->value('worker_name');

                $visit_status = '';

                if ($visit->status == 1) {
                    $visit_status = '<span class="text-warning">●</span> ' . trans('messages.pending', [], session('locale'));
                } elseif ($visit->status == 2) {
                    $visit_status = '<span class="text-success">●</span> ' . trans('messages.completed', [], session('locale'));
                } elseif ($visit->status == 3) {
                    $visit_status = '<span class="text-danger">●</span> ' . trans('messages.cancelled', [], session('locale'));
                } else {
                    $visit_status = '<span class="text-muted">●</span> -';
                }



                $sno++;
                $json[] = array(
                    '<span class="visit-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2"> ' . $booking_no . '</span>',
                    '<span class="text-nowrap ms-2"> ' . $visit_date . '</span>',
                    '<span class="text-primary">' . $customer . '</span>',
                    '<span class="text-primary">' . $location . '</span>',
                    '<span class="text-primary">' . $visit_status . '</span>',
                    '<span class="text-primary">' . $worker . '</span>',
                    '<span class="text-primary">' . $visit->shift . '</span>',
                    '<span class="text-primary">' . $visit->duration . '</span>',
                    '<span>' . $visit->added_by . '</span>',
                    '<span>' . $add_data . '</span>',
                    $modal
                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);
        }

        return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
    }

    public function all_visits()
    {
        $workers = Worker::select('id', 'worker_name')->get();
        $bookings = Booking::select('id', 'booking_no',)->where('status', [1, 4])->get();
        return view('bookings.admin_visits', compact('workers', 'bookings'));
    }

    public function add_visit2(Request $request)
    {


        $booking_id  = $request->booking_id;


        $booking = Booking::where('id',  $booking_id)->first();
        $customer_id = $booking->customer_id;
        // ✅ Create new visit
        $visit = new Visit();
        $visit->visit_date  = $request->visit_date;
        $visit->booking_id  = $booking_id;
        $visit->user_id  = $booking->user_id;
        $visit->customer_id = $customer_id;
        $visit->worker_id   = $request->worker_id;
        $visit->duration    = $request->duration;
        $visit->status    = 1;
        $visit->shift       = $request->shift;
        $visit->added_by = 'System';
        $visit->save();

        return response()->json([
            'ok'      => true,
            'message' => 'Visit created successfully',
            'visit'   => $visit,
        ]);
    }


     public function edit_visit2(Request $request)
    {


        $visit_id  = $request->id;


        $visit = Visit::where('id',  $visit_id)->first();

        $data = [
            'visit_id' => $visit->id,
            'worker_id' => $visit->worker_id,
            'booking_id' => $visit->booking_id,
            'customer_id' => $visit->customer_id,
            'shift' => $visit->shift,
            'duration' => $visit->duration,
            'visit_date' => $visit->visit_date,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }


    public function update_visit2(Request $request)
    {


        $visit_id  = $request->visit_id;
        $booking_id  = $request->booking_id;

        $visit= Visit::where('id', $visit_id)->first();


        $booking = Booking::where('id',  $booking_id)->first();
        $customer_id = $booking->customer_id;
        // ✅ Create new visit

        $visit->visit_date  = $request->visit_date;
        $visit->booking_id  = $booking_id;
        $visit->user_id  = $booking->user_id;
        $visit->customer_id = $customer_id;
        $visit->worker_id   = $request->worker_id;
        $visit->duration    = $request->duration;
        $visit->status    = 1;
        $visit->shift       = $request->shift;
        $visit->added_by = 'System';
        $visit->save();

        return response()->json([
            'ok'      => true,
            'message' => 'Visit created successfully',
            'visit'   => $visit,
        ]);
    }

    public function delete_visit(Request $request) {


    // $user_id = Auth::id();
    // $user = User::where('id', $user_id)->first();
    // $user_name = $user->user_name;
    $visit_id = $request->input('id');
    $visit = Visit::where('id', $visit_id)->first();

    if (!$visit) {
        return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.visit_not_found', [], session('locale'))], 404);
    }

    $previousData = $visit->only([
        'visit_date', 'worker_id', 'booking_id', 'shift', 'duration', 'added_by', 'customer_id', 'created_at'
    ]);

    // $currentUser = Auth::user();
    // $username = $currentUser->user_name;
    $visit_id = $visit->id;

    $history = new History();
    $history->user_id = 1;
    $history->table_name = 'visits';
    $history->function = 'delete';
    $history->function_status = 2;
    $history->record_id = $visit->id;
    $history->branch_id =1;

    $history->previous_data = json_encode($previousData);

    $history->added_by = 'system';
    $history->save();
    $visit->delete();

    return response()->json([
        trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
    ]);
}

public function edit_condition(Request $request)
{
    $id = $request->input('id');  // 👈 you'll get it here
    $worker_id= Visit::where('id', $id)->select('worker_id');
    $worker= Worker::where('id', $worker_id)->select('id', 'condition_type');

         $data = [

            'condition_type' => $worker->condition_type,
        ];

        return response()->json($data);


}

}
