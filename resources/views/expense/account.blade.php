@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.accounts_lang', [], session('locale')) }}</title>
    @endpush
    <style>
        /* Make button full-width on small screens */
        .form-head .add-staff {
            width: auto;
        }

        .search-area {
            max-width: 250px;
            width: 100%;
        }

        /* Adjust table font size and padding for smaller screens */
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

        /* Adjust the padding for the form fields */
        .input-group {
            max-width: 300px;
        }
    </style>ccout
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">

            <!-- Page Heading and Action Button -->
            <div
                class="form-head d-flex justify-content-between align-items-center flex-wrap mb-4 p-3 rounded bg-light shadow-sm">
                <div>
                    <h4 class="mb-1 text-primary">
                        <i class="fas fa-university me-2"></i>
                        {{ trans('messages.account_management_lang', [], session('locale')) }}
                    </h4>
                    <p class="text-muted mb-0 small">
                        {{ trans('messages.account_management_desc_lang', [], session('locale')) }}
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="javascript:void(0);" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal"
                        data-bs-target="#add_account_modal">
                        <i class="fas fa-plus-circle me-1"></i>
                        {{ trans('messages.add_account_lang', [], session('locale')) }}
                    </a>
                </div>
            </div>

            <!-- Account Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_accounts"
                                    class="table table-striped table-bordered mb-0 dataTablesCard fs-14">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('messages.account_name_lang', [], session('locale')) }}</th>
                                            <th>{{ trans('messages.account_no_lang', [], session('locale')) }}</th>
                                            <th>{{ trans('messages.opening_balance_lang', [], session('locale')) }}</th>
                                            <th>{{ trans('messages.added_by_lang', [], session('locale')) }}</th>
                                            <th>{{ trans('messages.added_on_lang', [], session('locale')) }}</th>
                                            <th class="text-center">
                                                {{ trans('messages.action_lang', [], session('locale')) }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <!-- DataTables will populate this -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="add_account_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel">
                        <i class="fas fa-university me-2"></i>
                        {{ trans('messages.add_data_lang', [], session('locale')) }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body overflow-auto" style="max-height: 80vh;">
                    <form class="add_account">
                        @csrf
                        <input type="hidden" class="account_id" name="account_id">

                        <div class="row g-3">
                            <!-- Account Name -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-id-card me-1 text-primary"></i>
                                    {{ trans('messages.account_name_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control account_name" name="account_name" type="text">
                            </div>

                            <!-- Bank Name -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-building-columns me-1 text-info"></i>
                                    {{ trans('messages.bank_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control account_branch" name="account_branch" type="text">
                            </div>

                            <!-- Account No -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-hashtag me-1 text-warning"></i>
                                    {{ trans('messages.account_no_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control account_no is_number" name="account_no" type="number">
                            </div>

                            <!-- Opening Balance -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-coins me-1 text-success"></i>
                                    {{ trans('messages.opening_balance_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control opening_balance" name="opening_balance" type="number">
                            </div>

                            <!-- Commission -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-percent me-1 text-danger"></i>
                                    {{ trans('messages.commission_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control commission isnumber" name="commission" type="number">
                            </div>

                            <!-- Account Type -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-wallet me-1 text-secondary"></i>
                                    {{ trans('messages.account_type', [], session('locale')) }}
                                </label>
                                <select class="form-control account_type default-select" name="account_type">
                                    <option value="1">
                                        {{ trans('messages.normal_account_lang', [], session('locale')) }}</option>
                                    <option value="2">
                                        {{ trans('messages.saving_account_lang', [], session('locale')) }}</option>
                                    <option value="3">{{ trans('messages.cash_lang', [], session('locale')) }}
                                    </option>
                                </select>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12">
                                <label class="form-label">
                                    <i class="fas fa-sticky-note me-1 text-muted"></i>
                                    {{ trans('messages.notes_lang', [], session('locale')) }}
                                </label>
                                <textarea class="form-control notes" rows="3" name="notes"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                {{ trans('messages.close_lang', [], session('locale')) }}
                            </button>
                            <button type="submit" class="btn btn-success submit_form">
                                {{ trans('messages.submit_lang', [], session('locale')) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel">
                        <i class="fas fa-coins me-2"></i>
                        {{ trans('messages.add_balance_lang', [], session('locale')) }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="{{ trans('messages.close_lang', [], session('locale')) }}"></button>
                </div>

                <div class="modal-body overflow-auto" style="max-height: 400px;">
                    <form class="add_balance">
                        @csrf
                        <input type="hidden" class="balance_account_id" name="balance_account_id">

                        <div class="row g-3">
                            <!-- Account Name -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-id-card me-1 text-primary"></i>
                                    {{ trans('messages.account_name_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control balance_name" name="balance_name" type="text" readonly>
                            </div>

                            <!-- Remaining Balance -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-wallet me-1 text-info"></i>
                                    {{ trans('messages.remaining_balance_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control remaining_balance" name="remaining_balance" type="number"
                                    readonly>
                            </div>

                            <!-- New Balance -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-plus-circle me-1 text-success"></i>
                                    {{ trans('messages.new_balance_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control new_balance" name="new_balance" type="number">
                            </div>

                            <!-- Amount -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-money-bill-wave me-1 text-warning"></i>
                                    {{ trans('messages.amount_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control amount" name="amount" type="text" readonly>
                            </div>

                            <!-- Balance Date -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt me-1 text-secondary"></i>
                                    {{ trans('messages.balance_date_lang', [], session('locale')) }}
                                </label>
                                <input class="form-control balance_date" name="balance_date" type="date">
                            </div>

                            <!-- Notes -->
                            <div class="col-md-8">
                                <label class="form-label">
                                    <i class="fas fa-sticky-note text-muted me-1"></i>
                                    {{ trans('messages.notes_lang', [], session('locale')) }}
                                </label>
                                <textarea class="form-control notes" rows="4" name="notes"
                                    placeholder="{{ trans('messages.optional_note_lang', [], session('locale')) }}"></textarea>
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-4 text-center">
                                <label class="form-label">
                                    <i class="fas fa-upload text-info me-1"></i>
                                    {{ trans('messages.upload_file_lang', [], session('locale')) }}
                                </label>
                                <div class="position-relative">
                                    <img id="filePreview" src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                        alt="Preview" class="img-fluid rounded shadow-sm"
                                        style="max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;">
                                    <input type="file" id="fileUpload" name="balance_file"
                                        class="d-none balance_file" accept="image/*, .pdf, .doc, .docx, .xls, .xlsx">
                                    <span id="removeFile"
                                        class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                        style="cursor: pointer; display: none;">&times;</span>
                                    <div id="fileName" class="mt-2 text-muted small"></div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                {{ trans('messages.close_lang', [], session('locale')) }}
                            </button>
                            <button type="submit" class="btn btn-success submit_form">
                                {{ trans('messages.submit_lang', [], session('locale')) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection
