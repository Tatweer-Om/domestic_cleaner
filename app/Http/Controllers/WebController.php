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
                    <a href="' . url("service-single/" . $service->id) . '"
                       class="btn btn-sm btn-success mt-2"
                       style="border-radius:20px; padding:5px 15px;">
                        View Service
                    </a>
                </div>
            </div>
        </div>
        ';
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
            
              <div class="d-flex justify-content-lg-end">
                <div class="location-picker-wrap" style="min-width:100%">
                  <label class="form-label small text-muted mb-1 d-flex align-items-center gap-2">
                    <span aria-hidden="true" style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:50%;background:#f0f2f5;">🔎</span>
                    <span class="d-none">' . trans('messages.filter_by_location', [], session('locale')) . '</span>
                    <small class="ms-auto text-muted">
                      <span id="locCount" class="badge rounded-pill bg-light text-dark border d-none">' . trans('messages.all', [], session('locale')) . '</span>
                    </small>
                  </label>

                  <div class="d-flex align-items-center gap-2 d-none">
                  
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

          <div id="workers_slider" class="service-slider">
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
    $workers = Worker::select('id', 'worker_name', 'worker_image', 'location_id')
      ->with(['location:id,location_name'])
      ->when($locationId, function ($q) use ($locationId) {
        $q->where('location_id', $locationId);
      })
      ->orderBy('worker_name')
      ->get();

    if ($workers->isEmpty()) {
      return '<div class="alert alert-info mb-0">' . trans('messages.no_worker_found', [], session('locale')) . '</div>';
    }



    $slides = '';
    foreach ($workers as $worker) {
      $imagePath = asset('images/worker_images/' . $worker->worker_image);
      $workerUrl = url('worker_profile/' . $worker->id);
      $locName = optional($worker->location)->location_name ?? 'Unassigned';

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




  public function getPolygon($id)
  {
    $location = Location::findOrFail($id);
    $polygon = json_decode($location->polygon);
    // If coordinates are [lng, lat], swap to [lat, lng]
    $polygon = array_map(function ($coord) {
      return ['lat' => $coord->lng, 'lng' => $coord->lat];
    }, $polygon);
    return response()->json([
      'id' => $location->id,
      'name' => $location->location_name,
      'polygon' => $polygon
    ]);
  }

  public function worker_profile($id)
  {

    $worker = Worker::where('id', $id)->first();
    $location = Location::where('id', $worker->location_id)->first();
    $location_name = $location->location_name;
    $delivery = $location->driver_availabe;
    $packages = Package::select('package_name', 'id')->get();
    $locations = Location::select('location_name', 'id')->get();
    return view('web_pages.worker_profile', compact('worker', 'packages', 'location_name', 'delivery', 'locations'));
  }




  public function save_location(Request $request)
  {
    // Validate input
    $request->validate([
      'address' => 'nullable|string|max:255',
      'latitude' => 'required|numeric',
      'longitude' => 'required|numeric',
      'google_link' => 'nullable|url',
      'osm_link' => 'nullable|url',
    ]);

    // Get lat/lon from request (truncate to 2 decimals if needed)
    $lat = round((float) $request->input('latitude'), 2);
    $lon = round((float) $request->input('longitude'), 2);
    $address = $request->input('address');
    $googleLink = $request->input('google_link');
    $osmLink = $request->input('osm_link');

    // Fetch all locations with bounding box data
    $locations = Location::select('id', 'location_name', 'lat_min', 'lat_max', 'lon_min', 'lon_max')
      ->whereNotNull(['lat_min', 'lat_max', 'lon_min', 'lon_max'])
      ->get();

    $matchedLocation = null;

    foreach ($locations as $location) {
      if (
        $lat >= $location->lat_min && $lat <= $location->lat_max &&
        $lon >= $location->lon_min && $lon <= $location->lon_max
      ) {
        $matchedLocation = $location;
        break;
      }
    }

    if ($matchedLocation) {


      return response()->json([
        'status' => 'inside',
        'location_id' => $matchedLocation->id,
        'location' => $matchedLocation->location_name,
        'point' => [$lat, $lon],
        'message' => "The point [$lat, $lon] is inside the region '{$matchedLocation->location_name}'."
      ], 200);
    } else {
      return response()->json([
        'status' => 'outside',
        'location_id' => null,
        'location' => null,
        'point' => [$lat, $lon],
        'message' => "The point [$lat, $lon] is outside all defined regions."
      ], 200);
    }
  }




  public function confirm_map(Request $request)
  {
    // Validate incoming data
    $request->validate([
      'address' => 'nullable|string|max:255',
      'latitude' => 'required|numeric',
      'longitude' => 'required|numeric',
      'google_link' => 'nullable|url',
      'osm_link' => 'nullable|url',
    ]);

    // Get lat/lon from request (truncate to 2 decimals if needed)
    $lat = round((float) $request->input('latitude'), 2);
    $lon = round((float) $request->input('longitude'), 2);
    $address = $request->input('address');
    $googleLink = $request->input('google_link');
    $osmLink = $request->input('osm_link');

    // Fetch all locations with bounding box data
    $locations = Location::select('id', 'location_name', 'lat_min', 'lat_max', 'lon_min', 'lon_max')
      ->whereNotNull(['lat_min', 'lat_max', 'lon_min', 'lon_max'])
      ->get();

    $matchedLocation = null;

    foreach ($locations as $location) {
      if (
        $lat >= $location->lat_min && $lat <= $location->lat_max &&
        $lon >= $location->lon_min && $lon <= $location->lon_max
      ) {
        $matchedLocation = $location;
        break;
      }
    }

    if ($matchedLocation) {
      $location = new Googlelinks();
      $location->location_id = $matchedLocation->id;
      $location->google_map = $googleLink;
      $location->e_map = $osmLink;
      $location->address = $address;
      $location->lon = $lon;
      $location->lat = $lat;

      // If user is logged in, attach user_id
      if ($user = Auth::user()) {
        $location->user_id = $user->id;
      } else {
        $location->guest_token = $request->cookie('guest_token');
      }

      $location->save();

      return response()->json([
        'status' => 'inside',
        'message' => "Location is confirmed'."
      ], 200);
    } else {
      return response()->json([
        'status' => 'outside',

        'message' => "Location not Found"
      ], 200);
    }
  }
}
