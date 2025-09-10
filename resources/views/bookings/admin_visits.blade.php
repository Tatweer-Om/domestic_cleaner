@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.visit_lang', [], session('locale')) }}</title>
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
                        <i class="fas fa-map-marker-alt me-2"></i> {{ trans('messages.visit_lang', [], $locale) }}
                    </h3>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i> {{ trans('messages.manage_visits_subtitle', [], $locale) }}
                    </p>
                </div>

                <div>
                    <a href="javascript:void(0);"
                        class="btn btn-success btn-rounded shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#add_visit_modal">
                        <i class="fas fa-plus-circle"></i>
                        <span>{{ trans('messages.add_visit', [], $locale) }}</span>
                    </a>
                </div>
            </div>


            <!-- visit Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_visits"
                                    class="table table-striped table-hover patient-list mb-4 dataTablesCard fs-14">
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
                                        <!-- Data loaded via Ajax -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <div class="modal fade" id="add_condition_modal" tabindex="-1" aria-labelledby="conditionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="conditionModalLabel">
          <i class="fas fa-notes-medical me-1"></i>
          {{ trans('messages.add_condition', [], session('locale')) }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form class="add_condition" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="condition_id" id="condition_id" class="condition_id">

          <div class="row g-3">
            <!-- Condition Select -->
            <div class="col-12">
              <div class="form-group">
                <label class="col-form-label">
                  <i class="fas fa-list-ul"></i>
                  {{ trans('messages.condition_type', [], session('locale')) }}
                </label>
                <select class="form-control selectpicker condition_type" id="condition_type" name="condition_type">
                  <option value="">{{ trans('messages.choose', [], session('locale')) }}...</option>
                  <option value="1">{{ trans('messages.sick_leave', [], session('locale')) }}</option>
                  <option value="2">{{ trans('messages.emergency', [], session('locale')) }}</option>
                  <option value="3">{{ trans('messages.travel_issue', [], session('locale')) }}</option>
                  <option value="4">{{ trans('messages.others', [], session('locale')) }}</option>
                </select>
              </div>
            </div>

            <!-- Notes Textarea -->
            <div class="col-12">
              <div class="form-group">
                <label class="col-form-label">
                  <i class="fas fa-comment-dots"></i>
                  {{ trans('messages.condition_notes', [], session('locale')) }}
                </label>
                <textarea class="form-control condition_notes" id="condition_notes" name="condition_notes" rows="4" placeholder="{{ trans('messages.type_here', [], session('locale')) }}"></textarea>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
              <i class="fas fa-times"></i> {{ trans('messages.close', [], session('locale')) }}
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> {{ trans('messages.save', [], session('locale')) }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>




        @include('layouts.footer')
    @endsection
