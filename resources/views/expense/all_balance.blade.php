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

    /* Adjust the padding for the form fields */
    .input-group {
        max-width: 300px;
    }
</style>
<div class="content-body">
    <!-- row -->

    <div class="container-fluid">

        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <button class="btn btn-success btn-rounded me-2" data-bs-toggle="tooltip" title="Account Number">
                    <i class="fas fa-wallet me-2"></i> Acc.No: {{ $account->account_no ?? '' }}
                </button>

                <button class="btn btn-info btn-rounded me-2" data-bs-toggle="tooltip" title="Account Name">
                    <i class="fas fa-user me-2"></i> Acc.Name: {{ $account->account_name ?? '' }}
                </button>

                <button class="btn btn-warning btn-rounded me-2" data-bs-toggle="tooltip" title="Current Balance">
                    <i class="fas fa-money-bill-wave me-2"></i> Current Balance: {{ $account->opening_balance ?? '' }} OMR
                </button>


            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_balances" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Previous<br>Balance</th>
                                        <th>Expense</th>
                                        <th>Balance</th>
                                        <th>New<br>Balance</th>
                                        <th>Added by</th>
                                        <th>Date</th>
                                        <th>Reciept</th>

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


</div>


@include('layouts.footer')
@endsection
