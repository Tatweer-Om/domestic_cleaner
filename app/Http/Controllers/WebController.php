<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Worker;
use App\Models\Package;
use App\Models\Service;
use App\Models\Location;
use App\Models\Googlelinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
  public function index()
  {
    return view('web_pages.index');
  }


  public function service_page()
  {
    return view('web_pages.service_page');
  }

  public function about()
  {
    return view('web_pages.about');
  }

  public function contact()
  {
    return view('web_pages.contact');
  }

  public function policy()
  {
    return view('web_pages.policy');
  }

public function service_section(Request $request)
{
    $services = Service::select('id', 'service_name', 'service_image')->get();

    $html = '';

    if ($services->isEmpty()) {
        $html = '
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning shadow-sm d-inline-block px-4 py-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ' . trans('messages.no_data_found', [], session('locale')) . '
                </div>
            </div>
        ';
    } else {
        foreach ($services as $service) {
            $html .= '
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 text-center">
                        <div class="card-img-top" style="height:180px; overflow:hidden;">
                            <img src="' . asset("images/service_images/" . $service->service_image) . '"
                                 alt="' . e($service->service_name) . '"
                                 class="img-fluid w-100" style="object-fit:cover; height:100%;">
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title mb-2" style="font-size: 1rem; font-weight:600; color:#333;">
                                ' . e($service->service_name) . '
                            </h6>
                        </div>
                    </div>
                </div>
            ';
        }
    }

    return response($html);
}



  public function worker_section(Request $request)
  {
    $locations  = Location::select('id', 'location_name')->orderBy('location_name')->get();
    $locationId = $request->query('location_id');

    $slidesHtml = $this->buildWorkerSlidesHtml($locationId);


    // Build options
    $optionsHtml = '<option value="">' . trans('messages.all_locations', [], session('locale')) . '</option>';
    foreach ($locations as $loc) {
      $id   = (int) $loc->id;
      $name = e($loc->location_name);
      // If you want to preselect based on ?location_id=
      $selected = ((string)$locationId === (string)$id) ? ' selected' : '';
      $optionsHtml .= "<option value=\"{$id}\"{$selected}>{$name}</option>";
    }


    $html = '
    <section class="wpo-service-section section-padding">
      <div class="wpo-service-wrap box-style section-padding">
        <div class="container">
          <div class="row align-items-center g-3 mb-3">
            <div class="col-lg-12">
              <div class="wpo-section-title">
                <span>
                  <i><img src="' . asset('assets/images/cleaning-icon.svg') . '" alt=""></i>
                  ' . trans('messages.workers_section_title', [], session('locale')) . '
                </span>
                <h2 class="poort-text poort-in-right"> ' . trans('messages.choose_location_worker', [], session('locale')) . '</h2>
              </div>
            </div>

            <div class="col-lg-12">
            
              <div class="d-flex align-items-center justify-content-between">
                <div class="location-picker-wrap" style="min-width:100%">
                  <label class="form-label small text-muted mb-1 d-flex align-items-center gap-2">
                    <span aria-hidden="true" style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:50%;background:#f0f2f5;">🔎</span>
                    <span class="d-none">' . trans('messages.filter_by_location', [], session('locale')) . '</span>
                    <small class="ms-auto text-muted">
                      <span id="locCount" class="badge rounded-pill bg-light text-dark border">' . trans('messages.all', [], session('locale')) . '</span>
                    </small>
                  </label>

                  <div class="d-flex align-items-center gap-2">
                  
                    <select
                      id="filter_location"
                      class="form-select selectpicker"
                      data-live-search="true"
                      data-live-search-placeholder="' . trans('messages.type_location', [], session('locale')) . '..."
                      data-size="8"
                      data-dropup-auto="false"
                      data-width="100%"
                      data-style="btn btn-light border rounded-3 shadow-sm px-3 py-2"
                      title="' . trans('messages.all_locations', [], session('locale')) . '"
                      aria-label="' . trans('messages.filter_workers_by_location', [], session('locale')) . '"
                    >' . $optionsHtml . '</select>


                  </div>


                </div>
              </div>
            </div>
          </div>

          <div id="workers_slider" class="service-slider" style="direction:ltr">
            ' . $slidesHtml . '
          </div>
        </div>

        <div class="left-shape">
          <img src="' . asset('assets/images/service/shape2.svg') . '" alt="">
        </div>
        <div class="left-shape2">
          <img src="' . asset('assets/images/service/shape1.svg') . '" alt="">
        </div>
        <div class="right-shape">
          <img src="' . asset('assets/images/service/shape3.svg') . '" alt="">
        </div>
      </div>
    </section>';

    return response($html);
  }

  public function worker_slides(Request $request)
  {
    $locationId = $request->query('location_id');
    return response($this->buildWorkerSlidesHtml($locationId));
  }
  private function buildWorkerSlidesHtml($locationId = null): string
    {
        // Build the query for workers
        $query = Worker::select('id', 'worker_name', 'worker_image', 'location_id')
            ->orderBy('worker_name');

        // If a locationId is provided, filter workers where location_id matches
        // either as a single ID or within a JSON array
        if ($locationId) {
            $query->where(function ($q) use ($locationId) {
                // Match single location_id (integer or string)
                $q->where('location_id', $locationId)
                  // Match location_id within JSON array
                  ->orWhereRaw('JSON_CONTAINS(location_id, ?)', [json_encode($locationId)]);
            });
        }

        $workers = $query->get();

        // If no workers are found, return the "no workers" message
        if ($workers->isEmpty()) {
            return '<div class="alert alert-info mb-0">' . trans('messages.no_worker_found', [], session('locale')) . '</div>';
        }

        // Fetch all locations to map IDs to names
        $locations = Location::pluck('location_name', 'id')->toArray();

        $slides = '';
        foreach ($workers as $worker) {
            $imagePath = asset('images/worker_images/' . $worker->worker_image);
            $workerUrl = url('worker_profile/' . $worker->id);

            // Handle location_id (single ID or JSON array)
            $locationIds = json_decode($worker->location_id, true) ?: [$worker->location_id];
            // Ensure $locationIds is an array
            $locationIds = is_array($locationIds) ? $locationIds : [$locationIds];
            // Map location IDs to names
            $locNames = [];
            foreach ($locationIds as $id) {
                if ($id && isset($locations[$id])) {
                    $locNames[] = $locations[$id];
                }
            }
            $locName = !empty($locNames) ? implode(', ', $locNames) : 'Unassigned';

            $slides .= '
                <div class="wpo-service-slide-item section-padding">
                <a href="' . $workerUrl . '" class="wpo-service-link" style="text-decoration:none; color:inherit;">
                    <div class="wpo-service-item wow fadeInUp" data-wow-duration="1000ms">
                    <div class="wpo-service-img middle-light fixed-img"
                        style="width:100%; height:280px; overflow:hidden; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <img src="' . $imagePath . '" alt="' . e($worker->worker_name) . '"
                            style="width:100%; height:100%; object-fit:cover; object-position:top center;">
                    </div>
                    <div class="wpo-service-text">
                        <div class="d-flex align-items-center justify-content-between">
                        <h2 class="mb-0" style="font-size:1.05rem;">' . e($worker->worker_name) . '</h2>
                        <span class="badge bg-secondary" title="Location">' . e($locName) . '</span>
                        </div>
                        <i class="ti-arrow-top-right" aria-hidden="true"></i>
                    </div>
                    </div>
                </a>
                </div>';
        }

        return $slides;
    }




  // public function getPolygon($id)
  // {
  //   $location = Location::findOrFail($id);
  //   $polygon = json_decode($location->polygon);
  //   // If coordinates are [lng, lat], swap to [lat, lng]
  //   $polygon = array_map(function ($coord) {
  //     return ['lat' => $coord->lng, 'lng' => $coord->lat];
  //   }, $polygon);
  //   return response()->json([
  //     'id' => $location->id,
  //     'name' => $location->location_name,
  //     'polygon' => $polygon
  //   ]);
  // }

    public function worker_profile($id)
    {
        // Fetch the worker
        $worker = Worker::where('id', $id)->firstOrFail();

        // Handle location_id (single ID or JSON array)
        $locationIds = json_decode($worker->location_id, true) ?: [$worker->location_id];
        $locationIds = is_array($locationIds) ? $locationIds : [$locationIds];

        // Fetch location names for all location IDs
        $locations = Location::whereIn('id', $locationIds)->pluck('location_name', 'id')->toArray();
        $locationNames = [];
        foreach ($locationIds as $id) {
            if ($id && isset($locations[$id])) {
                $locationNames[] = $locations[$id];
            }
        }
        $location_name = !empty($locationNames) ? implode(', ', $locationNames) : 'Unassigned';

        // Fetch delivery status (assuming it's from the first location or needs custom logic)
        $location = Location::whereIn('id', $locationIds)->first();
        $delivery = $location ? $location->driver_availabe : false;

        // Fetch packages and all locations for the view
        $packages = Package::select('package_name', 'id')->get();
        $all_locations = Location::select('location_name', 'id')->get();

        return view('web_pages.worker_profile', compact('worker', 'packages', 'location_name', 'delivery', 'all_locations'));
    }



  // public function save_location(Request $request)
  // {
  //   // Validate input
  //   $request->validate([
  //     'address' => 'nullable|string|max:255',
  //     'latitude' => 'required|numeric',
  //     'longitude' => 'required|numeric',
  //     'google_link' => 'nullable|url',
  //     'osm_link' => 'nullable|url',
  //   ]);

  //   // Get lat/lon from request (truncate to 2 decimals if needed)
  //   $lat = round((float) $request->input('latitude'), 2);
  //   $lon = round((float) $request->input('longitude'), 2);
  //   $address = $request->input('address');
  //   $googleLink = $request->input('google_link');
  //   $osmLink = $request->input('osm_link');

  //   // Fetch all locations with bounding box data
  //   $locations = Location::select('id', 'location_name', 'lat_min', 'lat_max', 'lon_min', 'lon_max')
  //     ->whereNotNull(['lat_min', 'lat_max', 'lon_min', 'lon_max'])
  //     ->get();

  //   $matchedLocation = null;

  //   foreach ($locations as $location) {
  //     if (
  //       $lat >= $location->lat_min && $lat <= $location->lat_max &&
  //       $lon >= $location->lon_min && $lon <= $location->lon_max
  //     ) {
  //       $matchedLocation = $location;
  //       break;
  //     }
  //   }

  //   if ($matchedLocation) {

  //     $location = new Googlelinks();
  //     $location->location_id = $matchedLocation->id;
  //     $location->google_map = $googleLink;
  //     $location->e_map = $osmLink;
  //     $location->address = $address;
  //     $location->lon = $lon;
  //     $location->lat = $lat;

  //     // If user is logged in, attach user_id
  //     if ($user = Auth::user()) {
  //       $location->user_id = $user->id;
  //     } else {
  //       $location->guest_token = $request->cookie('guest_token');
  //     }

  //     $location->save();
  //     return response()->json([
  //       'status' => 'inside',
  //       'location_id' => $matchedLocation->id,
  //       'location' => $matchedLocation->location_name,
  //       'point' => [$lat, $lon],
  //       'message' => "The point [$lat, $lon] is inside the region '{$matchedLocation->location_name}'."
  //     ], 200);
  //   } else {
  //     return response()->json([
  //       'status' => 'outside',
  //       'location_id' => null,
  //       'location' => null,
  //       'point' => [$lat, $lon],
  //       'message' => "The point [$lat, $lon] is outside all defined regions."
  //     ], 200);
  //   }
  // }




  // public function confirm_map(Request $request)
  // {
  //   // Validate incoming data
  //   $request->validate([
  //     'address' => 'nullable|string|max:255',
  //     'latitude' => 'required|numeric',
  //     'longitude' => 'required|numeric',
  //     'google_link' => 'nullable|url',
  //     'osm_link' => 'nullable|url',
  //   ]);

  //   // Get lat/lon from request (truncate to 2 decimals if needed)
  //   $lat = round((float) $request->input('latitude'), 2);
  //   $lon = round((float) $request->input('longitude'), 2);
  //   $address = $request->input('address');
  //   $googleLink = $request->input('google_link');
  //   $osmLink = $request->input('osm_link');

  //   // Fetch all locations with bounding box data
  //   $locations = Location::select('id', 'location_name', 'lat_min', 'lat_max', 'lon_min', 'lon_max')
  //     ->whereNotNull(['lat_min', 'lat_max', 'lon_min', 'lon_max'])
  //     ->get();

  //   $matchedLocation = null;

  //   foreach ($locations as $location) {
  //     if (
  //       $lat >= $location->lat_min && $lat <= $location->lat_max &&
  //       $lon >= $location->lon_min && $lon <= $location->lon_max
  //     ) {
  //       $matchedLocation = $location;
  //       break;
  //     }
  //   }

  //   if ($matchedLocation) {
  //     $location = new Googlelinks();
  //     $location->location_id = $matchedLocation->id;
  //     $location->google_map = $googleLink;
  //     $location->e_map = $osmLink;
  //     $location->address = $address;
  //     $location->lon = $lon;
  //     $location->lat = $lat;

  //     // If user is logged in, attach user_id
  //     if ($user = Auth::user()) {
  //       $location->user_id = $user->id;
  //     } else {
  //       $location->guest_token = $request->cookie('guest_token');
  //     }

  //     $location->save();

  //     return response()->json([
  //       'status' => 'inside',
  //       'message' => "Location is confirmed'."
  //     ], 200);
  //   } else {
  //     return response()->json([
  //       'status' => 'outside',

  //       'message' => "Location not Found"
  //     ], 200);
  //   }
  // }


  // In your controller
public function check_package_price(Request $request)
{

  
    $package_id = $request->input('package_id');
    $package = Package::find($package_id);

    if (!$package) {
        return response()->json([
            'ok' => false,
            'message' => trans('messages.package_not_found', [], session('locale'))
        ]);
    }

    return response()->json([
        'ok' => true,
        'price_4h' => $package->package_price_4, // adjust column name
        'price_5h' => $package->package_price_5, // adjust column name
    ]);
}

}
