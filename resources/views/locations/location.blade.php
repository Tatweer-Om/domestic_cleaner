@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.location_lang', [], session('locale')) }}</title>
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
                        <i class="fas fa-map-marker-alt me-2"></i> {{ trans('messages.location_lang', [], $locale) }}
                    </h3>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ trans('messages.manage_locations_subtitle', [], $locale) }}
                    </p>
                </div>

                <div>
                    <a href="javascript:void(0);"
                        class="btn btn-success btn-rounded shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#add_location_modal">
                        <i class="fas fa-plus-circle"></i>
                        <span>{{ trans('messages.add_location', [], $locale) }}</span>
                    </a>
                </div>
            </div>


            <!-- Location Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_location"
                                    class="table table-striped table-hover patient-list mb-4 dataTablesCard fs-14">
                                    <thead class="bg-light">
                                        <tr>
                                            <th><i class="fas fa-hashtag me-1"></i>
                                                {{ trans('messages.sr_no', [], $locale) }}</th>
                                            <th><i class="fas fa-map-marked-alt me-1"></i>
                                                {{ trans('messages.location_name', [], $locale) }}</th>
                                            <th><i class="fas fa-dollar-sign me-1"></i>
                                                {{ trans('messages.location_fare', [], $locale) }}</th>
                                                <th><i class="fas fa-dollar-sign me-1"></i>
                                                {{ trans('messages.driver_available', [], $locale) }}</th>
                                            <th><i class="fas fa-user me-1"></i>
                                                {{ trans('messages.added_by', [], $locale) }}</th>

                                            <th><i class="fas fa-calendar-alt me-1"></i>
                                                {{ trans('messages.added_on', [], $locale) }}</th>
                                            <th><i class="fas fa-cogs me-1"></i> {{ trans('messages.action', [], $locale) }}
                                            </th>
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

        <!-- Add Location Modal -->
        <div class="modal fade" id="add_location_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel">
                            <i class="fas fa-map-pin me-2"></i> {{ trans('messages.location_modal', [], $locale) }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="add_location">
                            @csrf
                            <div class="row">
                                <!-- Location Name -->
                                <div class="col-lg-12 col-xl-12 mb-3">
                                    <label
                                        class="col-form-label">{{ trans('messages.location_name', [], $locale) }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control location_name" name="location_name"
                                            placeholder="{{ trans('messages.location_name', [], $locale) }}">
                                    </div>
                                </div>

                                <!-- Location Fare -->
                                <div class="col-lg-12 col-xl-12 mb-3">
                                    <label
                                        class="col-form-label">{{ trans('messages.location_fare', [], $locale) }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        <input type="text" class="form-control location_fare" name="location_fare"
                                            placeholder="{{ trans('messages.location_fare', [], $locale) }}">
                                    </div>
                                </div>

                                <input type="hidden" class="location_id" name="location_id">

                                <!-- Notes -->
                                <div class="col-lg-12 col-xl-12 mb-3">
                                    <label class="col-form-label">{{ trans('messages.notes', [], $locale) }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                        <textarea class="form-control notes" rows="3" placeholder="{{ trans('messages.notes', [], $locale) }}"
                                            name="notes"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-12 mb-3">
                                    <label class="col-form-label d-block">{{ trans('messages.driver_availability', [], $locale) }}</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="driver_status"
                                            value="1" id="driver_available">
                                        <label class="form-check-label" for="driver_available">
                                            <i class="fas fa-user-check text-success me-1"></i>
                                            {{ trans('messages.available', [], $locale) }}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="driver_status"
                                            value="2" id="driver_unavailable">
                                        <label class="form-check-label" for="driver_unavailable">
                                            <i class="fas fa-user-times text-danger me-1"></i>
                                            {{ trans('messages.unavailable', [], $locale) }}
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> {{ trans('messages.add_data', [], $locale) }}
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> {{ trans('messages.close', [], $locale) }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection
