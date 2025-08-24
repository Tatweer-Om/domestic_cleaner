@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.bookings_lang', [], session('locale')) }}</title>
    @endpush



    <style>
        .form-label-sm {
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            color: #444;
        }

        .form-section {
            padding: 10px 15px;
            background: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .form-icon-label i {
            color: #6c757d;
            margin-right: 5px;
        }

        .form-control-sm {
            font-size: 0.8rem;
        }

        .form-group-custom {
            margin-bottom: 10px;
        }
    </style>

  <div class="content-body">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="card-header d-flex justify-content-between align-items-center bg-white shadow-sm border-0 rounded-top px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark" style="letter-spacing: 0.5px;">Worker Booking</h6>
                            <small class="text-muted" style="font-size: 12px;">Schedule Domestic Worker for a Client</small>
                        </div>
                    </div>

                    <div class="input-group input-group-sm" style="max-width: 230px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="client_search" name="client_search" placeholder="Search Client">
                    </div>
                </div>
                <br>

                <form class="add_booking">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4 form-group-custom">
                            <label class="form-label-sm form-icon-label"><i class="fas fa-user-tag"></i> Client Name</label>
                            <input type="text" class="form-control form-control-sm" name="client_name" id="client_name">
                        </div>

                        <div class="col-md-4 form-group-custom">
                            <label class="form-label-sm form-icon-label"><i class="fas fa-phone-alt"></i> Client Phone</label>
                            <input type="text" class="form-control form-control-sm" name="client_phone" id="client_phone">
                        </div>

                        <div class="col-md-4 form-group-custom">
                            <label class="form-label-sm form-icon-label"><i class="fas fa-map-marker-alt"></i> Location</label>
                            <select class="form-control form-control-sm selectpicker" name="location_id" id="location_id" data-live-search="true">
                                <option value="">Select Location</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group-custom">
                            <label class="form-label-sm form-icon-label"><i class="fas fa-users"></i> Worker</label>
                            <select class="form-control form-control-sm selectpicker" name="worker_id" id="worker_id" data-live-search="true">
                                <option value="">Choose Worker</option>
                                @foreach ($workers as $worker)
                                    <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group-custom">
                            <label class="form-label-sm form-icon-label"><i class="fas fa-calendar-alt"></i> Booking Date</label>
                            <input type="text" class="form-control form-control-sm datepicker" name="booking_date" id="booking_date">
                        </div>

                        <div class="col-md-4 form-group-custom">
                            <label class="form-label-sm form-icon-label"><i class="fas fa-clock"></i> Shift</label>
                            <select class="form-control form-control-sm" name="shift" id="shift">
                                <option value="">Select Shift</option>
                                <option value="morning">Morning (8 AM – 1 PM)</option>
                                <option value="evening">Evening (4 PM – 9 PM)</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group-custom">
                            <label class="form-label-sm form-icon-label"><i class="fas fa-money-bill-wave"></i> Fee</label>
                            <div class="input-group">
                                <span class="input-group-text">OMR</span>
                                <input type="text" class="form-control form-control-sm" name="fee" id="fee" value="{{ $setting->default_worker_fee ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-8 form-group-custom">
                            <label class="form-label-sm form-icon-label"><i class="fas fa-align-left"></i> Notes</label>
                            <textarea class="form-control form-control-sm" name="notes" id="notes" rows="2"></textarea>
                        </div>

                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-calendar-check"></i> Confirm Booking
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


    {{-- <div class="modal fade" id="payment_modal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <!-- Styled Header with Total Amount -->
                <div class="modal-header bg-primary text-white d-flex justify-content-between">
                    <h5 class="modal-title fw-bold" id="paymentModalLabel">Payment</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="add_payment">
                        @csrf
                        <!-- Total Amount -->
                        <div class="mb-3 text-center">
                            <h4 class="fw-bold text-danger">Total Amount: OMR <span id="total_amount"></span></h4>
                            <h4 class="fw-bold text-danger d-none" style="disply:none" id="voucher_discount_div">Discount: OMR
                                <span id="voucher_discount">0.000</span>
                            </h4>
                            <h4 class="fw-bold text-danger d-none" style="disply:none" id="after_discount_div">After Discount:
                                OMR <span id="after_discount"></span></h4>

                        </div>
                        <input type="hidden" id="total_amount_input" value="">
                        <input type="hidden" id="total_amount_discount" name="total_amount_discount" value="0.000">
                        <input type="hidden" id="total_amount_after_discount" value="">

                        <hr>



                        <div class="mb-2 col-md-5 mx-auto d-none" style="display:none;" id="voucher_div">
                            <div class="input-group">
                                <input type="text" class="form-control" id="voucher_code" name="voucher_code"
                                    placeholder="Voucher Code">
                                <button type="button" class="btn btn-success" id="check_voucher">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <!-- Payment Method Title -->
                        <div class="col-lg-12 deta">
                            <label class="col-form-label fw-bold fs-5">Select Payment Method</label>
                            <p class="text-muted">You can choose multiple payment methods and specify the amount for each.
                            </p>
                        </div>

                        <!-- Payment Methods with Amount Input -->
                        <div class="col-lg-12">
                            <div class="row">
                                @foreach ($accounts as $account)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input payment-method-checkbox" type="checkbox"
                                                name="payment_methods[]" id="account_{{ $account->id }}"
                                                value="{{ $account->id }}"
                                                onchange="toggleAmountInput({{ $account->id }}, {{ $account->account_status }})">
                                            <label class="form-check-label fw-bold" for="account_{{ $account->id }}">
                                                {{ $account->account_name }}
                                            </label>
                                        </div>

                                        <input type="number"
                                            class="form-control form-control-sm payment-amount-input mt-1 payment_amounts"
                                            id="amount_{{ $account->id }}" name="payment_amounts[{{ $account->id }}]"
                                            value="{{ $setting->appointment_fee ?? '0.00' }}" placeholder="Enter amount"
                                            min="0" step="0.01" style="display: none;">

                                        @if ($account->account_status != 1)
                                            <input type="text" class="form-control form-control-sm ref-no-input mt-1"
                                                id="ref_no_{{ $account->id }}" name="ref_nos[{{ $account->id }}]"
                                                placeholder="Enter Reference Number" style="display: none;">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <!-- Submit Buttons -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="confirm_payment">
                                <i class="fas fa-check"></i> Confirm Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}





    @include('layouts.footer')
@endsection
