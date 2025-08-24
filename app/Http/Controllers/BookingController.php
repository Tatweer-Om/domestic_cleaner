<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Worker;
use App\Models\Booking;
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

        // Optional integrity check: ensure worker exists
        $worker = Worker::findOrFail($data['worker_id']);

        // Use worker's location_id
        $location_id = $worker->location_id;

        // Check if location_id is null
        if (is_null($location_id)) {
            return response()->json(['error' => 'Worker is not assigned to any location.'], 422);
        }

        // Basic check for visits array
        if (!isset($data['visits']) || !is_array($data['visits']) || empty($data['visits'])) {
            return response()->json(['error' => 'At least one visit is required.'], 422);
        }

        // Get user details
        $user = Auth::user();
        $user_id = Auth::id(); // null if guest
        $user_name = $user ? $user->user_name : null; // null if guest
        $customer = $user_id ? Customer::where('user_id', $user_id)->first() : null;
        $customer_id = $customer ? $customer->id : null;

        try {
            $booking = DB::transaction(function () use ($data, $user_id, $user_name, $customer_id, $location_id) {
                $booking = Booking::create([
                    'user_id'      => $user_id,
                    'worker_id'    => $data['worker_id'],
                    'package_id'   => $data['package_id'],
                    'location_id'  => $location_id,
                    'start_date'   => $data['start_date'],
                    'duration'     => $data['duration'],
                    'visits'       => json_encode($data['visits']), // Convert array to JSON string
                    'visits_count' => count($data['visits']),
                    'status'       => 1,
                    'added_by'     => $user_name,
                    'customer_id'  => $customer_id,
                ]);

                foreach ($data['visits'] as $visit) {
                    Visit::create([
                        'booking_id'  => $booking->id,
                        'visit_date'  => $visit['date'],
                        'shift'       => $visit['shift'],
                        'duration'    => $visit['duration'],
                        'visit_name'  => $visit['visit_name'] ?? null,
                         'worker_id'    => $booking->worker_id,
                        'user_id'     => $user_id,
                        'added_by'    => $user_name,
                        'customer_id' => $customer_id,
                    ]);
                }

                return $booking;
            });

            return response()->json([
                'ok'         => true,
                'booking_id' => $booking->id,
                'message'    => 'Booking saved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save booking: ' . $e->getMessage()], 500);
        }
    }


  public function checkout($id)
    {
        // Find the booking for the given worker_id
        $booking = Booking::where('worker_id', $id)->first();

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
        $worker = Worker::findOrFail($id);

        // Fetch visits directly from visits table for worker_id
        $visits = [];
        $visit_records = Visit::where('worker_id', $id)->get();
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
    dd($voucherCode = $request->input('code'));

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

}
