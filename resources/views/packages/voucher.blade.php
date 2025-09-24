@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.vouchers_lang', [], session('locale')) }}</title>
    @endpush
    <style>
    /* styles unchanged... */
    </style>

<div class="content-body">
    <div class="container-fluid">


      <div class="form-head d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div class="d-flex align-items-center mb-2 mb-md-0">
        <h4 class="mb-0 me-3 text-primary fw-bold">
            <i class="fas fa-box-open me-2"></i> {{ trans('messages.vouchers_lang', [], session('locale')) }}
        </h4>
    </div>

    <div>
        <button type="button" class="btn btn-sm btn-success rounded-pill px-4 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#add_voucher_modal">
            <i class="fas fa-plus me-1"></i>
            {{ trans('messages.add_voucher_lang', [], session('locale')) }}
        </button>
    </div>
</div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table id="all_voucher" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>{{ trans('messages.serial_no_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.voucher_number_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.price_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.voucher_type_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.added_by_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.added_on_lang', [], session('locale')) }}</th>
                                        <th class="text-center">{{ trans('messages.action_lang', [], session('locale')) }}</th>
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

    <div class="modal fade" id="add_voucher_modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('messages.voucher_modal_lang', [], session('locale')) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form class="add_voucher">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label class="col-form-label">{{ trans('messages.voucher_number_lang', [], session('locale')) }}</label>
                                    <input type="text" class="form-control voucher_number" name="voucher_number" placeholder="{{ trans('messages.voucher_number_lang', [], session('locale')) }}">
                                </div>
                            </div>

                            <input type="hidden" class="voucher_id" name="voucher_id">

                            <div class="col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label class="col-form-label">{{ trans('messages.price_lang', [], session('locale')) }}</label>
                                    <input type="text" class="form-control voucher_price" name="voucher_price" placeholder="{{ trans('messages.price_lang', [], session('locale')) }}">
                                </div>
                            </div>

                            <div class="col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label class="col-form-label">{{ trans('messages.voucher_type_lang', [], session('locale')) }} <span class="text-danger">*</span></label>
                                    <select class="form-control default-select wide mb-3 voucher_type" name="voucher_type">
                                        <option value="">{{ trans('messages.choose_lang', [], session('locale')) }}</option>
                                        <option value="1">{{ trans('messages.daily_lang', [], session('locale')) }}</option>
                                        <option value="2">{{ trans('messages.monthly_lang', [], session('locale')) }}</option>
                                         <option value="2">{{ trans('messages.both_lang', [], session('locale')) }}</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-form-label">{{ trans('messages.notes_lang', [], session('locale')) }}</label>
                                    <textarea class="form-control notes" rows="4" name="notes"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ trans('messages.close_lang', [], session('locale')) }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('messages.add_data_lang', [], session('locale')) }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
@endsection
