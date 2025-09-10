@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.booking_lang', [], session('locale')) }}</title>
    @endpush

    <style>
        .form-head .add-staff {
            width: auto;
        }

        .search-area {
            max-width: 250px;
            width: 100%;
        }

        @media (max-width: 767px) {
            .form-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-head .add-staff {
                width: 100%;
                margin-bottom: 10px;
            }

            .table-responsive {
                margin-top: 20px;
            }

            .table th,
            .table td {
                padding: 10px 8px;
                font-size: 12px;
            }

            .table {
                font-size: 12px;
            }

            .checkbox {
                padding: 0;
            }
        }

        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>

    @php $locale = session('locale'); @endphp

    <div class="content-body">
        <div class="container-fluid">

            <!-- Page Header -->
            <div
                class="form-head d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 p-3 bg-light rounded shadow-sm border mb-4">
                <div>
                    <h3 class="fw-bold text-primary mb-1 d-flex align-items-center">
                        <i class="fas fa-map-marker-alt me-2"></i> {{ trans('messages.booking_lang', [], $locale) }}
                    </h3>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i> {{ trans('messages.manage_bookings_subtitle', [], $locale) }}
                    </p>
                </div>

                {{-- <div>
                    <a href="javascript:void(0);"
                        class="btn btn-success btn-rounded shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#add_booking_modal">
                        <i class="fas fa-plus-circle"></i>
                        <span>{{ trans('messages.add_booking', [], $locale) }}</span>
                    </a>
                </div> --}}
            </div>


            <!-- booking Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_booking"
                                    class="table table-striped table-hover patient-list mb-4 dataTablesCard fs-14">
                                  <thead class="bg-light">
  <tr>
    <th><i class="fas fa-list-ol me-1"></i>
        {{ trans('messages.sr_no', [], $locale) }}</th>

    <th><i class="fas fa-receipt me-1"></i>
        {{ trans('messages.booking_no', [], $locale) }}</th>

    <th><i class="fas fa-calendar-check me-1"></i>
        {{ trans('messages.booking_date', [], $locale) }}</th>

    <th><i class="fas fa-user-tie me-1"></i>
        {{ trans('messages.customer', [], $locale) }}</th>

    <th><i class="fas fa-phone-alt me-1"></i>
        {{ trans('messages.customer_phone', [], $locale) }}</th>

    <th><i class="fas fa-map-marker-alt me-1"></i>
        {{ trans('messages.location', [], $locale) }}</th>

    <th><i class="fas fa-info-circle me-1"></i>
        {{ trans('messages.booking_status', [], $locale) }}</th>
    <th><i class="fas fa-tools me-1"></i>
        {{ trans('messages.visit_count', [], $locale) }}</th>
         <th><i class="fas fa-tools me-1"></i>
        {{ trans('messages.booking_hours', [], $locale) }}</th>
   <th><i class="fas fa-tools me-1"></i>
        {{ trans('messages.package', [], $locale) }}</th>
    <th><i class="fas fa-tools me-1"></i>
        {{ trans('messages.payment', [], $locale) }}</th>
    <th><i class="fas fa-user-plus me-1"></i>
        {{ trans('messages.added_by', [], $locale) }}</th>

    <th><i class="fas fa-clock me-1"></i>
        {{ trans('messages.added_on', [], $locale) }}</th>

    <th><i class="fas fa-tools me-1"></i>
        {{ trans('messages.action', [], $locale) }}</th>
  </tr>
</thead>

                                    <tbody>
                                        <!-- Data loaded via Ajax -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        @include('layouts.footer')
    @endsection
