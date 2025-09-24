@extends('layouts.header')

@section('main')
    @push('title')
        <title>  {{ trans('messages.gerneral_users_credentials', [], session('locale')) }}</title>
    @endpush
    <style>
        /* Make button full-width on small screens */
        .form-head .add-staff {
            width: auto;
        }


        .permission-card:hover {
            background-color: #f8f9fa;
            transition: 0.3s;
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
    </style>
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">

            <div
                class="form-head d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 p-3 bg-light rounded shadow-sm border mb-4">
                <div>
                    <h3 class="fw-bold text-primary mb-1 d-flex align-items-center">
                        <i class="bi bi-person-lines-fill me-2"></i>  {{ trans('messages.gerneral_users_credentials', [], session('locale')) }}
                    </h3>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ trans('messages.manage_users_subtitle', [], session('locale')) }}
                    </p>
                </div>

              
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="general_users"
                                    class="table table-striped table-hover mb-4 dataTablesCard fs-14 align-middle text-nowrap">
                                    <thead class="bg-light text-dark">
                                  <tr>
    <th>
        <i class="fa fa-hashtag me-1 text-primary"></i>
        {{ trans('messages.sr_no', [], session('locale')) }}
    </th>
    <th>
        <i class="fa fa-user me-1 text-primary"></i>
        {{ trans('messages.user_name', [], session('locale')) }}
    </th>
    <th>
        <i class="fa fa-phone me-1 text-success"></i>
        {{ trans('messages.phone', [], session('locale')) }}
    </th>
    <th>
        <i class="fa fa-users-cog me-1 text-warning"></i>
        {{ trans('messages.user_type', [], session('locale')) }}
    </th>
    <th>
        <i class="fa fa-user-shield me-1 text-info"></i>
        {{ trans('messages.added_by', [], session('locale')) }}
    </th>
    <th>
        <i class="fa fa-calendar me-1 text-danger"></i>
        {{ trans('messages.added_on', [], session('locale')) }}
    </th>
</tr>

                                    </thead>
                                    <tbody>
                                        {{-- Dynamic rows rendered via JS or Blade --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    @include('layouts.footer')
@endsection
