@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.drivers_lang', [], session('locale')) }}</title>
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
        }

        .input-group {
            max-width: 300px;
        }

        /* Icon styling */
        .table th i {
            margin-right: 5px;
            color: #007bff;
        }
    </style>

    <div class="content-body">
        <div class="container-fluid">

         <div class="form-head d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 p-3 bg-light rounded shadow-sm border">
    <div class="d-flex flex-column">
        <h3 class="fw-bold text-primary mb-1">
            <i class="fa fa-car-side me-2"></i> {{ trans('messages.drivers_lang', [], session('locale')) }}
        </h3>
        <small class="text-muted">
            <i class="fa fa-info-circle me-1"></i> {{ trans('messages.manage_drivers_subtitle', [], session('locale')) }}
        </small>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="javascript:void();" class="btn btn-success btn-rounded shadow-sm d-flex align-items-center gap-1"
           data-bs-toggle="modal" data-bs-target="#add_driver_modal">
            <i class="fa fa-user-plus"></i>
            <span>{{ trans('messages.add_driver', [], session('locale')) }}</span>
        </a>
    </div>
</div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_drivers" class="table table-striped mb-4 dataTablesCard fs-14">
                                  <thead class="bg-light text-dark border-bottom align-middle">
    <tr class="text-nowrap">
        <th class="text-center">
            <i class="fa fa-hashtag text-primary me-1"></i>
            {{ trans('messages.sr_no', [], session('locale')) }}
        </th>
        <th>
            <i class="fa fa-user text-primary me-1"></i>
            {{ trans('messages.driver_name', [], session('locale')) }}
        </th>
        <th>
            <i class="fa fa-phone text-primary me-1"></i>
            {{ trans('messages.phone', [], session('locale')) }}
        </th>
        <th>
            <i class="fa fa-map-marker-alt text-primary me-1"></i>
            {{ trans('messages.location', [], session('locale')) }}
        </th>
        <th>
            <i class="fa fa-clock text-primary me-1"></i>
            {{ trans('messages.shifts', [], session('locale')) }}
        </th>
        <th>
            <i class="fa fa-user-shield text-primary me-1"></i>
            {{ trans('messages.added_by', [], session('locale')) }}
        </th>
        <th>
            <i class="fa fa-calendar text-primary me-1"></i>
            {{ trans('messages.added_on', [], session('locale')) }}
        </th>
        <th class="text-center">
            <i class="fa fa-cogs text-primary me-1"></i>
            {{ trans('messages.action', [], session('locale')) }}
        </th>
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

        {{-- Add/Edit Driver Modal --}}
        <div class="modal fade" id="add_driver_modal" tabindex="-1" aria-labelledby="driverModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="driverModalLabel">
                            <i class="fa fa-id-card-alt me-1"></i> {{ trans('messages.driver', [], session('locale')) }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="add_driver" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- Driver Name --}}
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-user"></i>
                                            {{ trans('messages.full_name', [], session('locale')) }}
                                        </label>
                                        <input type="text" class="form-control driver_name" name="driver_name"
                                            placeholder="{{ trans('messages.driver_name_placeholder', [], session('locale')) }}">
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-phone"></i>
                                            {{ trans('messages.mobile_no', [], session('locale')) }}
                                        </label>
                                        <input type="number" class="form-control phone" name="phone"
                                            placeholder="{{ trans('messages.mobile_placeholder', [], session('locale')) }}">
                                    </div>
                                </div>

                                <input type="hidden" name="driver_id" id="driver_id" class="driver_id">

                                {{-- User --}}

                                {{-- Location Dropdown --}}
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-map-marker-alt"></i>
                                            {{ trans('messages.select_location', [], session('locale')) }}
                                        </label>
                                        <select class="form-control selectpicker location_id" name="location_id"
                                            data-live-search="true">
                                            <option value="">{{ trans('messages.choose', [], session('locale')) }}...
                                            </option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}"
                                                    data-tokens="{{ $location->location_name }}">
                                                    {{ $location->location_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- User Dropdown --}}
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-user"></i>
                                            {{ trans('messages.select_user', [], session('locale')) }}
                                        </label>
                                        <select class="form-control selectpicker driver_user_id" name="driver_user_id"
                                            data-live-search="true">
                                            <option value="">{{ trans('messages.choose', [], session('locale')) }}...
                                            </option>
                                                <option value="1">user1
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Shift Dropdown --}}
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-clock"></i>
                                            {{ trans('messages.select_shift', [], session('locale')) }}
                                        </label>
                                        <select class="form-control selectpicker shift" name="shift"
                                            data-live-search="true">
                                            <option value="">{{ trans('messages.choose', [], session('locale')) }}...
                                            </option>
                                            <option value="1">{{ trans('messages.morning', [], session('locale')) }}
                                            </option>
                                            <option value="2">{{ trans('messages.evening', [], session('locale')) }}
                                            </option>
                                            <option value="3">{{ trans('messages.both', [], session('locale')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>



                                {{-- Notes --}}
                                <div class="row mt-3">
                                    <div class="col-12 col-md-8">
                                        <div class="form-group">
                                            <label class="col-form-label">
                                                <i class="fa fa-sticky-note"></i>
                                                {{ trans('messages.notes', [], session('locale')) }}
                                            </label>
                                            <textarea class="form-control notes" rows="4" name="notes"></textarea>
                                        </div>
                                    </div>

                                    {{-- Image Upload --}}
                                    <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
                                        <div class="form-group text-center position-relative">
                                            <label class="col-form-label">
                                                <i class="fa fa-image"></i>
                                                {{ trans('messages.image', [], session('locale')) }}
                                            </label>
                                            <img id="imagePreview"
                                                src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                                alt="Preview" class="img-fluid rounded driver_image"
                                                style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;">

                                            <input type="file" id="imageUpload" name="driver_image" class="d-none"
                                                accept="image/*">

                                            <span id="removeImage"
                                                class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                                style="cursor: pointer; display: none;">&times;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                    <i class="fa fa-times"></i> {{ trans('messages.close', [], session('locale')) }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> {{ trans('messages.add_data', [], session('locale')) }}
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
