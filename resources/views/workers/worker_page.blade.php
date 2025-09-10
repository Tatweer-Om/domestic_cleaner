@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.worker_profile_lang', [], session('locale')) }}</title>
    @endpush
   @php $locale = session('locale'); @endphp

  <style>
    /* Default smaller popup */
.swal-small-popup {
    width: 350px !important;
    max-width: 90% !important;
    border-radius: 12px;
}

/* Smaller title for mobile */
.swal-small-title {
    font-size: 1.1rem !important;
}

/* Responsive fix */
@media (max-width: 576px) {
    .swal-small-popup {
        width: 95% !important;
        font-size: 14px !important;
        padding: 1rem !important;
    }
    .swal-small-title {
        font-size: 1rem !important;
    }
    .swal2-actions {
        flex-direction: column !important;
        gap: 0.5rem;
    }
    .swal2-actions .btn {
        width: 100% !important;
    }
}

     .tab-container {
            background: linear-gradient(135deg, #2563eb 0%, #22c55e 50%, #1e293b 100%);
            border-radius: 12px;
            padding: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 18px rgba(16, 24, 40, .06);
        }

        /* Tab navigation */
        .nav-tabs {
            border: none;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .nav-tabs .nav-link {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease;
            backdrop-filter: blur(4px);
        }

        .nav-tabs .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Table styling */
        .card {
            border-radius: 12px;
            border: 1px solid #e8eef2;
            background: #fff;
            box-shadow: 0 6px 18px rgba(16, 24, 40, .06);
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            color: #1e293b;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.75rem;
            border-bottom: 2px solid #e8eef2;
        }

        .table tbody tr {
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f6faff;
        }

        .table tbody td {
            font-size: 0.85rem;
            color: #374151;
            padding: 0.75rem;
            vertical-align: middle;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .nav-tabs .nav-link {
                padding: 0.4rem 1rem;
                font-size: 0.8rem;
            }

            .card-body {
                padding: 1rem;
            }
        }
    /* Background & layout */
    .bg-gradient-clean {
        background: linear-gradient(135deg, #2563eb 0%, #22c55e 50%, #1e293b 100%);
        padding: 3.5rem 1.25rem;
    }

    @media (min-width: 992px) {
        .bg-gradient-clean {
            padding: 5rem 2rem;
        }
    }

    /* Chips */
    .chip {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        padding: .4rem .7rem;
        border-radius: 999px;
        font-size: .8rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(5px);
    }

    /* Circle photo */
    .hero-photo-circle {
        width: 260px;
        height: 260px;
        border-radius: 50%;
        padding: .5rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, .35), rgba(255, 255, 255, .05));
        border: 2px solid rgba(255, 255, 255, .25);
        backdrop-filter: blur(6px);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .hero-photo-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    /* Floating badge */
    .photo-badge {
        position: absolute;
        bottom: 10px;
        right: -10px;
        background: #fff;
        color: #111827;
        border-radius: 999px;
        padding: .35rem .8rem;
        font-size: .75rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        box-shadow: 0 3px 6px rgba(0, 0, 0, .15);
    }

    .photo-badge .dot {
        width: .45rem;
        height: .45rem;
        border-radius: 50%;
        background: #16a34a;
    }

    /* Ambient blur shapes */
    .hero-shape-1,
    .hero-shape-2 {
        position: absolute;
        pointer-events: none;
        filter: blur(40px);
    }

    .hero-shape-1 {
        width: 380px;
        height: 380px;
        right: -120px;
        top: -120px;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, .35), transparent 60%);
    }

    .hero-shape-2 {
        width: 320px;
        height: 320px;
        left: -100px;
        bottom: -120px;
        background: radial-gradient(circle at 70% 70%, rgba(255, 255, 255, .2), transparent 60%);
    }

    /* Hero section */
    .service-hero {
        background: radial-gradient(1200px 600px at 10% -10%, #2ec5ce33, transparent 60%),
            radial-gradient(1200px 600px at 100% 10%, #845ef733, transparent 50%),
            linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
        color: #fff;
        padding: 0px 0 56px;
        position: relative;
    }

    .hero-copy .badge {
        background: rgba(255, 255, 255, .85);
        border: 1px solid #e9ecef;
    }

    /* Shadow and rounded utilities */
    .shadow-sm {
        box-shadow: 0 8px 24px rgba(16, 24, 40, .06);
    }

    .rounded-4 {
        border-radius: 14px;
    }
</style>

                    <div class="content-body">
                        <div class="container-fluid">

                    <div class="service-hero position-relative overflow-hidden bg-gradient-clean rounded-4">
                        <div class="container position-relative">
                            <div class="row align-items-center g-4">

                                {{-- Copy --}}
                                <div class="col-12 col-lg-7">
                                    <div class="hero-copy text-center text-lg-start">
                                       

                                        <h1 class="display-5 fw-bold text-white mb-3">
                                            {{ $worker->worker_name ?? 'Residential Cleaning' }}
                                        </h1>




                                        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-2">
                    {{-- Location name chip --}}
                    {{-- <span class="chip bg-light text-dark fw-semibold px-3 py-1 rounded-pill shadow-sm">
                        <i class="ti-location-pin me-1 text-primary"></i>
                        {{ $location_name ?? 'No location' }}
                    </span> --}}

                 {{-- Visit Stats --}}
<div class="mt-4">
    <h5 class="fw-bold text-white mb-3">
        <i class="ti-check-box me-2 text-warning"></i> Visits
    </h5>

    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-2">
        {{-- Completed --}}
        <span class="chip bg-success text-white fw-semibold px-3 py-1 rounded-pill shadow-sm">
            <i class="ti-check me-1"></i> Done: {{ $completed_visits ?? 0 }}
        </span>

        {{-- Pending --}}
        <span class="chip bg-warning text-dark fw-semibold px-3 py-1 rounded-pill shadow-sm">
            <i class="ti-time me-1"></i> Pending: {{ $pending_visits ?? 0 }}
        </span>

        {{-- Cancelled --}}
        <span class="chip bg-danger text-white fw-semibold px-3 py-1 rounded-pill shadow-sm">
            <i class="ti-close me-1"></i> Cancelled: {{ $cancelled_visits ?? 0 }}
        </span>

        {{-- Total --}}
        <span class="chip bg-primary text-white fw-semibold px-3 py-1 rounded-pill shadow-sm">
            <i class="ti-list me-1"></i> Total: {{ $total_visits ?? 0 }}
        </span>
    </div>
</div>

                </div>

                    </div>
                </div>

                {{-- Circle Photo --}}
                <div class="col-12 col-lg-5 text-center">
                    @php
                        $fileName = $worker->worker_image ?? ($worker->image ?? null);
                        $imgSrc = $fileName
                            ? asset('images/worker_images/' . $fileName)
                            : asset('assets/images/default-worker.png');
                    @endphp

                    <div class="hero-photo-circle mx-auto position-relative">
                        <img src="{{ $imgSrc }}" alt="{{ $worker->worker_name ?? 'Worker photo' }}"
                            class="img-fluid rounded-circle shadow-lg hero-photo-img"
                            onerror="this.onerror=null;this.src='{{ asset('assets/images/default-worker.png') }}';">

                        {{-- Floating badge --}}
                        <div class="photo-badge shadow-sm">
                            <span class="dot"></span> Ready to work
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Background shapes --}}
        <div class="hero-shape-1"></div>
        <div class="hero-shape-2"></div>
    </div>
<hr>
     <div class="tab-container">
                <ul class="nav nav-tabs" id="visitsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="today-visits-tab" data-bs-toggle="tab" data-bs-target="#today-visits" type="button" role="tab" aria-controls="today-visits" aria-selected="true">
                            {{ trans('messages.today_visits', [], $locale) }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="this-week-visits-tab" data-bs-toggle="tab" data-bs-target="#this-week-visits" type="button" role="tab" aria-controls="this-week-visits" aria-selected="false">
                            {{ trans('messages.this_week_visits', [], $locale) }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="all-visits-tab" data-bs-toggle="tab" data-bs-target="#all-visits" type="button" role="tab" aria-controls="all-visits" aria-selected="false">
                            {{ trans('messages.all_visits', [], $locale) }}
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="visitsTabContent">
                <!-- Today's Visits -->
                <div class="tab-pane fade show active" id="today-visits" role="tabpanel">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <!-- ✅ Desktop table -->
                    <div class="table-responsive d-none d-md-block">
                        <table id="today_workers" class="table table-striped mb-4 dataTablesCard fs-14">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Booking No</th>
                                    <th>Visit Date</th>
                                    <th>Customer</th>
                                    <th>Location</th>
                                    <th>Shift / Duration / Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="today_worker_body"></tbody>
                        </table>
                    </div>

                    <!-- ✅ Mobile cards -->
                    <div id="today_worker_cards" class="d-block d-md-none"></div>
                </div>
            </div>
        </div>
    </div>
</div>

                <!-- This Week Visits -->
            <div class="tab-pane fade" id="this-week-visits" role="tabpanel">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <!-- ✅ Desktop table -->
                    <div class="table-responsive d-none d-md-block">
                        <table id="this_week_workers" class="table table-striped mb-4 dataTablesCard fs-14">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Booking No</th>
                                    <th>Visit Date</th>
                                    <th>Customer</th>
                                    <th>Location</th>
                                    <th>Shift / Duration / Status</th>
                                </tr>
                            </thead>
                            <tbody id="this-week-workers-body"></tbody>
                        </table>
                    </div>

                    <!-- ✅ Mobile cards -->
                    <div id="this_week_worker_cards" class="d-block d-md-none"></div>
                </div>
            </div>
        </div>
    </div>
</div>

                <!-- All Visits -->
                <div class="tab-pane fade" id="all-visits" role="tabpanel" aria-labelledby="all-visits-tab">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table id="all_worker_visits" class="table table-striped mb-4 dataTablesCard fs-14">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th><i class="fas fa-list-ol me-1"></i>
                                                        {{ trans('messages.sr_no', [], $locale) }}</th>
                                                    <th><i class="fas fa-receipt me-1"></i>
                                                        {{ trans('messages.booking_no', [], $locale) }}</th>
                                                    <th><i class="fas fa-calendar-check me-1"></i>
                                                        {{ trans('messages.visit_date', [], $locale) }}</th>
                                                    <th><i class="fas fa-user-tie me-1"></i>
                                                        {{ trans('messages.customer', [], $locale) }}</th>
                                                    <th><i class="fas fa-map-marker-alt me-1"></i>
                                                        {{ trans('messages.location', [], $locale) }}</th>
                                                    <th><i class="fas fa-info-circle me-1"></i>
                                                        {{ trans('messages.shift-duration-status', [], $locale) }}</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
      </div>   
      <hr>
      
      
      
      
      
      @include('layouts.footer')
@endsection
