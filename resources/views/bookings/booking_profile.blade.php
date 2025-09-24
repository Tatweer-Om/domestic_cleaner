@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.booking_profiile', [], session('locale')) }}</title>
    @endpush

    <style>
        .booking-summary {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            border-radius: 15px;
            padding: 25px;
            color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .booking-summary h4 {
            font-weight: 700;
        }

        .booking-summary .info-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            font-size: 15px;
        }

        .booking-summary .info-item i {
            margin-right: 10px;
            font-size: 18px;
        }

        .booking-summary .badge {
            font-size: 14px;
            padding: 8px 12px;
        }

           .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .info-card {
        background: #fff;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .info-card h6 {
        font-size: 16px;
        color: #000;
    }

    .info-card small {
        font-size: 12px;
    }
    </style>
 @php $locale = session('locale'); @endphp

    <div class="content-body">
        <div class="container-fluid">

            {{-- Booking Summary Card --}}
          <div class="booking-summary mb-4">
    <div class="row g-3">
        <!-- Booking Number -->
        <div class="col-md-4">
            <div class="info-card d-flex align-items-center p-3 rounded shadow-sm h-100">
                <div class="icon-circle bg-primary text-white me-3">
                    <i class="fa fa-receipt"></i>
                </div>
                <div>
                    <small class="text-muted d-block">{{ trans('messages.booking_no', [], session('locale')) }}</small>
                    <h6 class="mb-0 fw-bold">{{ $booking->booking_no ?? '-' }}</h6>
                </div>
            </div>
        </div>

        <input type="hidden" id="booking_id" value="{{ $booking->id }}" hidden>

        <!-- Customer Name -->
        <div class="col-md-4">
            <div class="info-card d-flex align-items-center p-3 rounded shadow-sm h-100">
                <div class="icon-circle bg-success text-white me-3">
                    <i class="fa fa-user"></i>
                </div>
                <div>
                    <small class="text-muted d-block">{{ trans('messages.customer_name', [], session('locale')) }}</small>
                    <h6 class="mb-0 fw-bold">{{ $customer_name ?? '-' }}</h6>
                </div>
            </div>
        </div>
        <!-- Contact -->
        <div class="col-md-4">
            <div class="info-card d-flex align-items-center p-3 rounded shadow-sm h-100">
                <div class="icon-circle bg-warning text-white me-3">
                    <i class="fa fa-phone"></i>
                </div>
                <div>
                    <small class="text-muted d-block">{{ trans('messages.contact', [], session('locale')) }}</small>
                    <h6 class="mb-0 fw-bold">{{ $phone ?? '-' }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
    

            {{-- Table Section --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_booking_visits" class="table table-striped mb-4 dataTablesCard fs-14">
                                    <thead class="bg-light text-dark border-bottom align-middle">
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
                                                {{ trans('messages.visit_status', [], $locale) }}</th>
                                            <th><i class="fas fa-info-circle me-1"></i>
                                                {{ trans('messages.worker', [], $locale) }}</th>
                                            <th><i class="fas fa-info-circle me-1"></i>
                                                {{ trans('messages.shift', [], $locale) }}</th>
                                            <th><i class="fas fa-info-circle me-1"></i>
                                                {{ trans('messages.duration', [], $locale) }}</th>
                                            <th><i class="fas fa-user-plus me-1"></i>
                                                {{ trans('messages.added_by', [], $locale) }}</th>

                                            <th><i class="fas fa-clock me-1"></i>
                                                {{ trans('messages.added_on', [], $locale) }}</th>

                                            <th><i class="fas fa-tools me-1"></i>
                                                {{ trans('messages.action', [], $locale) }}</th>
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

        @include('layouts.footer')
    </div>
@endsection
