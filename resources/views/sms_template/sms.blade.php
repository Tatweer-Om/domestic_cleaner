@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.sms_panel_title', [], session('locale')) }}</title>
    @endpush

    <div class="content-body">
        <div class="container-fluid">

            <!-- Header Card -->
            <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-white shadow-sm border-0 rounded-top px-4 py-3 w-100">

                    <!-- Left Title and Icon -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-sms"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark" style="letter-spacing: 0.5px;">{{ trans('messages.sms_panel', [], session('locale')) }}</h6>
                            <small class="text-muted">{{ trans('messages.sms_panel_subtitle', [], session('locale')) }}</small>
                        </div>
                    </div>



                </div>
            </div>

            <!-- SMS Form Card -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-success" id="success-alert">
                                    {{ Session::get('success') }}
                                </div>
                            @endif

                            <form action="{{ url('add_status_sms') }}" method="post">
                                @csrf
                                <div class="row">
                                    <!-- Left Panel -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="sms_status">
                                                {{ trans('messages.select_sms_type', [], session('locale')) }}
                                            </label>
                                            <select class="form-control sms_status" name="status" id="sms_status">
                                                <option value="">{{ trans('messages.choose', [], session('locale')) }}
                                                </option>
                                                <option value="1">
                                                    {{ trans('messages.booking_message', [], session('locale')) }}</option>
                                                <option value="2">
                                                    {{ trans('messages.visit_done_customer', [], session('locale')) }}</option>
                                                <option value="3">
                                                    {{ trans('messages.visit_done_worker', [], session('locale')) }}</option>
                                                <option value="4">
                                                    {{ trans('messages.next_visit_message', [], session('locale')) }}
                                                </option>
                                                <option value="5">
                                                    {{ trans('messages.extend_booking_message', [], session('locale')) }}
                                                </option>
                                                <option value="6">
                                                    {{ trans('messages.cancel_booking_message', [], session('locale')) }}
                                                </option>
                                                <option value="7">
                                                    {{ trans('messages.driver_message', [], session('locale')) }}</option>
                                                <option value="8">
                                                    {{ trans('messages.worker_message', [], session('locale')) }}</option>
                                            </select>
                                        </div>

                                        <div class="mt-3">
                                            <p class="fw-bold">
                                                {{ trans('messages.available_variables', [], session('locale')) }}
                                            </p>

                                            <p class="text-success worker_name" style="cursor: pointer;">
                                                {{ trans('messages.worker_name', [], session('locale')) }}
                                            </p>
                                            <p class="text-success booking_no" style="cursor: pointer;">
                                                {{ trans('messages.booking_no', [], session('locale')) }}
                                            </p>
                                            <p class="text-success booking_date" style="cursor: pointer;">
                                                {{ trans('messages.booking_date', [], session('locale')) }}
                                            </p>
                                            <p class="text-success booking_time" style="cursor: pointer;">
                                                {{ trans('messages.booking_time', [], session('locale')) }}
                                            </p>
                                            <p class="text-success visit_date" style="cursor: pointer;">
                                                {{ trans('messages.visit_date', [], session('locale')) }}
                                            </p>
                                            <p class="text-success visit_time" style="cursor: pointer;">
                                                {{ trans('messages.visit_time', [], session('locale')) }}
                                            </p>
                                            <p class="text-success package" style="cursor: pointer;">
                                                {{ trans('messages.package', [], session('locale')) }}
                                            </p>
                                            <p class="text-success location" style="cursor: pointer;">
                                                {{ trans('messages.location', [], session('locale')) }}
                                            </p>
                                            <p class="text-success total_visits" style="cursor: pointer;">
                                                {{ trans('messages.total_visits', [], session('locale')) }}
                                            </p>
                                            <p class="text-success remianing_visits" style="cursor: pointer;">
                                                {{ trans('messages.remianing_visits', [], session('locale')) }}
                                            </p>
                                            <p class="text-success next_visit_date" style="cursor: pointer;">
                                                {{ trans('messages.next_visit_date', [], session('locale')) }}
                                            </p>
                                            <p class="text-success extention_time" style="cursor: pointer;">
                                                {{ trans('messages.extention_time', [], session('locale')) }}
                                            </p>
                                            <p class="text-success extention_date" style="cursor: pointer;">
                                                {{ trans('messages.extention_date', [], session('locale')) }}
                                            </p>
                                            <p class="text-success cancel_date" style="cursor: pointer;">
                                                {{ trans('messages.cancel_date', [], session('locale')) }}
                                            </p>
                                            <p class="text-success driver_no" style="cursor: pointer;">
                                                {{ trans('messages.driver_no', [], session('locale')) }}
                                            </p>
                                            <p class="text-success driver_name" style="cursor: pointer;">
                                                {{ trans('messages.driver_name', [], session('locale')) }}
                                            </p>
                                            <p class="text-success customer_name" style="cursor: pointer;">
                                                {{ trans('messages.customer_name', [], session('locale')) }}
                                            </p>
                                            <p class="text-success shift" style="cursor: pointer;">
                                                {{ trans('messages.shift', [], session('locale')) }}
                                            </p>
                                            <p class="text-success duration" style="cursor: pointer;">
                                                {{ trans('messages.duration', [], session('locale')) }}
                                            </p>
                                        </div>


                                    </div>

                                    <!-- SMS Content -->
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="sms">
                                                {{ trans('messages.sms_content', [], session('locale')) }}
                                            </label>
                                            <textarea class="form-control sms_area" id="sms" name="sms" placeholder="{{ trans('messages.sms_content_placeholder', [], session('locale')) }}"
                                                rows="12" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="text-end">
                                    <button class="btn btn-primary"
                                        type="submit">{{ trans('messages.submit', [], session('locale')) }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('layouts.footer')
@endsection
