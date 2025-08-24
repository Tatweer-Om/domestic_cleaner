@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.expense_category_lang', [], session('locale')) }}</title>
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

            .table th, .table td {
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

        .input-group {
            max-width: 300px;
        }
    </style>

    <div class="content-body">
        <div class="container-fluid">


            <!-- Form Header with Description and Button -->
            <div class="form-head d-flex justify-content-between align-items-center mb-4 p-4 rounded bg-light flex-wrap shadow-sm">
                <div>
                    <h4 class="mb-1 text-primary d-flex align-items-center">
                        <i class="fas fa-list-alt me-2 fs-5"></i> {{ trans('messages.expense_category_lang', [], session('locale')) }}
                    </h4>
                    <p class="text-muted mb-0 small">
                        {{ trans('messages.manage_expense_category_description', [], session('locale')) }}
                    </p>
                </div>
                <a href="javascript:void(0);" class="btn btn-success btn-rounded d-flex align-items-center mt-3 mt-md-0"
                   data-bs-toggle="modal" data-bs-target="#add_expense_category_modal">
                    <i class="fas fa-plus-circle me-2"></i> {{ trans('messages.add_expense_category_lang', [], session('locale')) }}
                </a>
            </div>

            <!-- Expense Category Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_expense_category" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('messages.sr_no', [], session('locale')) }}</th>
                                            <th>{{ trans('messages.expense_category_lang', [], session('locale')) }}</th>
                                            <th>{{ trans('messages.added_by', [], session('locale')) }}</th>
                                            <th>{{ trans('messages.added_on', [], session('locale')) }}</th>
                                            <th class="text-start">{{ trans('messages.action', [], session('locale')) }}</th>
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

        <!-- Modal -->
        <div class="modal fade" id="add_expense_category_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-plus-circle me-2"></i> {{ trans('messages.add_expense_category_lang', [], session('locale')) }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="add_expense_category">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            {{ trans('messages.expense_category_name_lang', [], session('locale')) }}
                                        </label>
                                        <input type="text" class="form-control expense_category_name" name="expense_category_name" placeholder="{{ trans('messages.expense_category_name_lang', [], session('locale')) }}">
                                    </div>
                                </div>
                                <input type="hidden" class="expense_category_id" name="expense_category_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                    {{ trans('messages.close', [], session('locale')) }}
                                </button>
                                <button type="submit" class="btn btn-success">
                                    {{ trans('messages.submit', [], session('locale')) }}
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
