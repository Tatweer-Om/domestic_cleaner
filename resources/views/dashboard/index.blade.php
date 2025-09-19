@extends('layouts.header')

@section('main')
@push('title')
<title> {{ trans('messages.dashboard_lang', [], session('locale')) }}</title>
@endpush
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
            <div class="me-auto d-none d-lg-block">
                <h3 class="text-black font-w600">{{ trans('messages.welcome_message', [], session('locale')) }}</h3>
                <p class="mb-0 fs-18">{{ trans('messages.company_tagline', [], session('locale')) }}</p>
            </div>


        </div>
        <div class="row">
            <div class="row">
                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="card gradient-bx text-white bg-danger">
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <p class="mb-1">{{ trans('messages.total_users', [], session('locale')) }}</p>
                                    <div class="d-flex flex-wrap">
                                        <h2 class="fs-40 font-w600 text-white mb-0 me-3">{{ $totalUsers }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="card gradient-bx text-white bg-success">
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <p class="mb-1">{{ trans('messages.total_customers', [], session('locale')) }}</p>
                                    <div class="d-flex flex-wrap">
                                        <h2 class="fs-40 font-w600 text-white mb-0 me-3">{{ $totalCustomers }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="card gradient-bx text-white bg-info">
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <p class="mb-1">{{ trans('messages.total_bookings', [], session('locale')) }}</p>
                                    <div class="d-flex flex-wrap">
                                        <h2 class="fs-40 font-w600 text-white mb-0 me-3">{{ $totalBookings }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="card gradient-bx text-white bg-secondary">
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <p class="mb-1">{{ trans('messages.total_visits', [], session('locale')) }}</p>
                                    <div class="d-flex flex-wrap">
                                        <h2 class="fs-40 font-w600 text-white mb-0 me-3">{{ $totalVisits }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          
  <div class="col-xl-9 col-xxl-8 col-lg-7">
    <div class="card shadow-sm border-0">
        <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
            <h3 class="fs-20 mb-0 text-black">{{ trans('messages.top_rated_workers', [], session('locale')) }}</h3>
            <a href="{{ url('worker') }}" class="text-primary font-w500">{{ trans('messages.view_more', [], session('locale')) }} >></a>
        </div>
        <div class="card-body">
            <div class="assigned-doctor owl-carousel">
                @foreach ($workers as $worker)
                    <div class="items">
                        <div class="text-center worker-card">
                            <div class="worker-image-container">
                                <img src="{{ $worker->worker_image ? asset('images/worker_images/' . $worker->worker_image) : asset('images/default-worker.jpg') }}" alt="{{ $worker->worker_name }}" class="worker-image">
                            </div>
                            <div class="dr-star"><i class="las la-star"></i> {{ number_format($worker->rating, 1) }}</div>
                            <h5 class="fs-16 mb-1 font-w600">
                                <a class="text-black worker-name" href="{{ url('workers/' . $worker->id) }}">{{ $worker->worker_name }}</a>
                            </h5>
                            <span class="text-primary mb-2 d-block">{{ Str::limit($worker->location->location_name ?? 'Unknown', 7, '') }}</span>
                            <p class="fs-12 text-muted">{{ $worker->status ?? 'No status' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.worker-card {
    padding: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.worker-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.worker-image-container {
    width: 100px;
    height: 100px;
    margin: 0 auto 10px;
    overflow: hidden;
    border-radius: 50%;
    border: 2px solid #e9ecef;
}
.worker-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.dr-star {
    color: #f1c40f;
    font-size: 14px;
    margin-bottom: 8px;
}
.worker-name:hover {
    color: #007bff;
    text-decoration: none;
}
.text-muted {
    color: #6c757d !important;
}
.card {
    border-radius: 10px;
}
</style>
      <div class="col-xl-3 col-xxl-4 col-lg-5">
    <div class="card border-0 pb-0 shadow-sm">
        <div class="card-header flex-wrap border-0 pb-0 d-flex justify-content-between align-items-center">
            <h3 class="fs-20 mb-0 text-black">{{ trans('messages.recent_customers', [], session('locale')) }}</h3>
            <a href="{{ url('customers') }}" class="text-primary font-w500">{{ trans('messages.view_more', [], session('locale')) }} >></a>
        </div>
        <div class="card-body recent-patient px-0">
            <div id="DZ_W_Todo2" class="widget-media px-4 dz-scroll height320">
                <ul class="timeline">
                    @foreach ($customers as $customer)
                        <li>
                            <div class="timeline-panel flex-wrap align-items-center">
                                <div class="media me-3 customer-icon">
                                    <i class="las la-user"></i>
                                </div>
                                <div class="media-body">
                                    <h5 class="mb-0">
                                        <a class="text-black" href="{{ url('customers/' . $customer->id) }}">{{ $customer->customer_name }}</a>
                                    </h5>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    @if ($customers->isEmpty())
                        <li>
                            <p class="text-center text-muted">{{ trans('messages.no_customers_found', [], session('locale')) }}</p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-panel {
    padding: 15px 0;
    transition: background-color 0.3s ease;
}
.timeline-panel:hover {
    background-color: #f8f9fa;
    border-radius: 5px;
}
.customer-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e9ecef;
    border-radius: 50%;
    font-size: 30px;
    color: #007bff;
}
.customer-icon i {
    line-height: 1;
}
.text-black {
    font-weight: 600;
    transition: color 0.3s ease;
}
.text-black:hover {
    color: #007bff;
    text-decoration: none;
}
.card {
    border-radius: 10px;
}
.text-muted {
    color: #6c757d !important;
}
.dz-scroll {
    scrollbar-width: thin;
    scrollbar-color: #007bff #e9ecef;
}
.dz-scroll::-webkit-scrollbar {
    width: 8px;
}
.dz-scroll::-webkit-scrollbar-track {
    background: #e9ecef;
}
.dz-scroll::-webkit-scrollbar-thumb {
    background: #007bff;
    border-radius: 4px;
}
</style>
        </div>
    </div>
</div>

@include('layouts.footer')
@endsection