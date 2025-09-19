@extends('layouts.header')

@section('main')
    @push('title')
        <title>{{ trans('messages.customers_lang', [], session('locale')) }}</title>
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

            <div
                class="form-head d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 p-3 bg-light rounded shadow-sm border">
                <div class="d-flex flex-column">
                    <h3 class="fw-bold text-primary mb-1">
                        <i class="fa fa-car-side me-2"></i> {{ trans('messages.customers_lang', [], session('locale')) }}
                    </h3>
                    <small class="text-muted">
                        <i class="fa fa-info-circle me-1"></i>
                        {{ trans('messages.manage_customers_subtitle', [], session('locale')) }}
                    </small>
                </div>
                <!-- <div class="mt-3 mt-md-0">
                    <a href="javascript:void();"
                        class="btn btn-success btn-rounded shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal"
                        data-bs-target="#add_customer_modal">
                        <i class="fa fa-user-plus"></i>
                        <span>{{ trans('messages.add_customer', [], session('locale')) }}</span>
                    </a>
                </div> -->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_customers" class="table table-striped mb-4 dataTablesCard fs-14">
                                    <thead class="bg-light text-dark border-bottom align-middle">
                                        <tr class="text-nowrap">
                                            <th class="text-center">
                                                <i class="fa fa-hashtag text-primary me-1"></i>
                                                {{ trans('messages.sr_no', [], session('locale')) }}
                                            </th>
                                            <th>
                                                <i class="fa fa-user text-primary me-1"></i>
                                                {{ trans('messages.customer_name', [], session('locale')) }}
                                            </th>
                                            <th>
                                                <i class="fa fa-phone text-primary me-1"></i>
                                                {{ trans('messages.phone', [], session('locale')) }}
                                            </th>
                                           
                                        
                                            <th>
                                                <i class="fa fa-user-shield text-primary me-1"></i>
                                                {{ trans('messages.added_by', [], session('locale')) }}
                                            </th>
                                            <th>
                                                <i class="fa fa-calendar text-primary me-1"></i>
                                                {{ trans('messages.added_on', [], session('locale')) }}
                                            </th>
                                            <!-- <th class="text-center">
                                                <i class="fa fa-cogs text-primary me-1"></i>
                                                {{ trans('messages.action', [], session('locale')) }}
                                            </th> -->
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

        {{-- Add/Edit customer Modal --}}


    </div>

   

    @include('layouts.footer')
@endsection
