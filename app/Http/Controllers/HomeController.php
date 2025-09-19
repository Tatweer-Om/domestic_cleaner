<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
   public function index()
{



    $monthlyBookings = Booking::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_year'),
                DB::raw('COUNT(*) as bookings_count')
            )
            ->whereNotNull('transactionId')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), 'ASC')
            ->get();

        // Transform data for chart: Extract month names and counts
        $months = [];
        $counts = [];
        $monthNames = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];

        foreach ($monthlyBookings as $record) {
            $monthYear = $record->month_year; // e.g., '2025-09'
            $yearMonth = substr($monthYear, 5, 2); // Extract month part, e.g., '09'
            $monthName = $monthNames[$yearMonth] ?? 'Unknown'; // Fallback if needed
            $fullLabel = $monthName . ' ' . substr($monthYear, 0, 4); // e.g., 'September 2025'

            $months[] = $fullLabel;
            $counts[] = (int) $record->bookings_count;
        }
    // Check authentication
    if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }

    
       $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('1', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }
    // Fetch counts
    $totalUsers = \App\Models\User::count();
    $totalCustomers = \App\Models\Customer::count();
    $totalBookings = \App\Models\Booking::count();
    $totalVisits = \App\Models\Visit::count();

    $workers = Worker::select('id', 'worker_name', 'worker_image', 'location_id')
           
            ->take(8) // Limit to 8 workers for carousel
            ->get();

             $customers = Customer::select('id', 'customer_name',)
           
            ->take(8) // Limit to 8 workers for carousel
            ->get();

    // Pass data to view
    return view('dashboard.index', compact(
        'totalUsers',
        'totalCustomers',
        'totalBookings',
        'totalVisits', 'months', 'counts', 'workers', 'customers'
    ));
}


    public function login_page(){
        return view ('pages.login');
    }

    public function switchLanguage($locale)
{
    app()->setLocale($locale);
    config(['app.locale' => $locale]);
    // You can store the chosen locale in session for persistence
    session(['locale' => $locale]);

    return redirect()->back(); // or any other redirect you want
}

public  function login_error(){
    return view('pages.login_error');
}

}





