<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Package;
use App\Models\Service;
use App\Models\Location;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        return view('web_pages.index');
    }




public function worker_section(Request $request)
{
    $locations  = Location::select('id', 'location_name')->orderBy('location_name')->get();
    $locationId = $request->query('location_id');
    $slidesHtml = $this->buildWorkerSlidesHtml($locationId);

    // Build options
    $optionsHtml = '<option value="">All locations</option>';
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
            <div class="col-lg-6">
              <div class="wpo-section-title">
                <span>
                  <i><img src="' . asset('assets/images/cleaning-icon.svg') . '" alt=""></i>
                  Workers
                </span>
                <h2 class="poort-text poort-in-right">Where Cleanliness meets Care Services</h2>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="d-flex justify-content-lg-end">
                <div class="location-picker-wrap" style="min-width:320px">
                  <label class="form-label small text-muted mb-1 d-flex align-items-center gap-2">
                    <span aria-hidden="true" style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:50%;background:#f0f2f5;">🔎</span>
                    <span>Filter by location</span>
                    <small class="ms-auto text-muted">
                      <span id="locCount" class="badge rounded-pill bg-light text-dark border">All</span>
                    </small>
                  </label>

                  <div class="d-flex align-items-center gap-2">
                    <select
                      id="filter_location"
                      class="form-select selectpicker"
                      data-live-search="true"
                      data-live-search-placeholder="Type location..."
                      data-size="8"
                      data-dropup-auto="false"
                      data-width="100%"
                      data-style="btn btn-light border rounded-3 shadow-sm px-3 py-2"
                      title="All locations"
                      aria-label="Filter workers by location"
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
            return '<div class="alert alert-info mb-0">No workers found for the selected location.</div>';
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

    public function worker_profile($id)
    {

        $worker = Worker::where('id', $id)->first();
        $packages = Package::select('package_name', 'id')->get();
        $locations = Location::select('location_name', 'id')->get();
        return view('web_pages.worker_profile', compact('worker', 'packages', 'locations'));
    }



}

