@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.services_lang', [], session('locale')) }}</title>
    @endpush

    <style>
        .form-head .add-staff { width: auto; }
        .search-area { max-width: 250px; width: 100%; }
        @media (max-width: 767px) {
            .form-head { flex-direction: column; align-items: flex-start; }
            .form-head .add-staff { width: 100%; margin-bottom: 10px; }
            .table-responsive { margin-top: 20px; }
            .table th, .table td { padding: 10px 8px; font-size: 12px; }
            .table { font-size: 12px; }
        }
        .input-group { max-width: 300px; }
        .table th i { margin-right: 5px; color: #007bff; }
    </style>

    <div class="content-body">
        <div class="container-fluid">

            <div class="form-head d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 p-3 bg-light rounded shadow-sm border">
                <div class="d-flex flex-column">
                    <h3 class="fw-bold text-primary mb-1">
                        <i class="fas fa-broom me-2"></i> {{ trans('messages.services_lang', [], session('locale')) }}
                    </h3>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ trans('messages.manage_services_subtitle', [], session('locale')) }}
                    </small>
                </div>

                <div class="mt-3 mt-md-0">
                    <a href="javascript:void(0);" class="btn btn-success btn-rounded shadow-sm d-flex align-items-center gap-1"
                       data-bs-toggle="modal" data-bs-target="#add_service_modal">
                        <i class="fas fa-plus-circle"></i>
                        <span>{{ trans('messages.add_service', [], session('locale')) }}</span>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_services" class="table table-striped mb-4 dataTablesCard fs-14">
                                    <thead class="bg-light text-dark border-bottom">
                                        <tr class="align-middle text-nowrap">
                                              <th><i class="fas fa-list-ol me-1 text-primary"></i>{{ trans('messages.sr_no', [], session('locale')) }}</th>
        <th><i class="fas fa-briefcase me-1 text-success"></i>{{ trans('messages.service_name', [], session('locale')) }}</th>
        <th><i class="fas fa-dollar-sign me-1 text-warning"></i>{{ trans('messages.fee', [], session('locale')) }}</th>
        <th><i class="fas fa-user-tie me-1 text-info"></i>{{ trans('messages.added_by', [], session('locale')) }}</th>
        <th><i class="fas fa-calendar-alt me-1 text-secondary"></i>{{ trans('messages.added_on', [], session('locale')) }}</th>
        <th><i class="fas fa-tools me-1 text-danger"></i>{{ trans('messages.action', [], session('locale')) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Add/Edit Service Modal --}}
        <div class="modal fade" id="add_service_modal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="serviceModalLabel">
                            <i class="fas fa-spray-can-sparkles me-1"></i> {{ trans('messages.service', [], session('locale')) }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form class="add_service" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- Service Name --}}
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fas fa-broom"></i>
                                            {{ trans('messages.service_name', [], session('locale')) }}
                                        </label>
                                        <input type="text" class="form-control service_name" name="service_name"
                                               placeholder="{{ trans('messages.service_name_placeholder', [], session('locale')) }}"
                                               required>
                                    </div>
                                </div>

                                {{-- Service Fee --}}
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fas fa-tags"></i>
                                            {{ trans('messages.service_fee', [], session('locale')) }}
                                        </label>
                                        <input type="number" step="0.01" min="0" class="form-control service_fee" name="service_fee"
                                               placeholder="{{ trans('messages.service_fee_placeholder', [], session('locale')) }}"
                                               required>
                                    </div>
                                </div>

                                <input type="hidden" name="service_id" id="service_id" class="service_id">

                                {{-- Notes + Image --}}
                                <div class="row mt-3">
                                    <div class="col-12 col-md-8">
                                        <div class="form-group">
                                            <label class="col-form-label">
                                                <i class="fas fa-sticky-note"></i>
                                                {{ trans('messages.notes', [], session('locale')) }}
                                            </label>
                                            <textarea class="form-control notes" rows="4" name="notes"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
                                        <div class="form-group text-center position-relative">
                                            <label class="col-form-label">
                                                <i class="far fa-image"></i>
                                                {{ trans('messages.image', [], session('locale')) }}
                                            </label>
                                            <img id="imagePreview"
                                                 src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                                 alt="Preview" class="img-fluid rounded service_image"
                                                 style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;">
                                            <input type="file" id="imageUpload" name="service_image" class="d-none" accept="image/*">
                                            <span id="removeImage" class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                                  style="cursor: pointer; display: none;">&times;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> {{ trans('messages.close', [], session('locale')) }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ trans('messages.add_data', [], session('locale')) }}
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
