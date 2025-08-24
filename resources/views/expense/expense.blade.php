@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.expense_lang', [], session('locale')) }}</title>
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
    </style>
  <div class="content-body">
    <div class="container-fluid">

        <!-- Page Header -->
<div class="form-head d-flex justify-content-between align-items-center mb-4 p-4 rounded bg-light flex-wrap shadow-sm">
    <div>
        <h4 class="mb-1 text-primary d-flex align-items-center">
            <i class="fas fa-box-open me-2 fs-5"></i> {{ trans('messages.packages_lang', [], session('locale')) }}
        </h4>
        <p class="text-muted mb-0 small">
            {{ trans('messages.manage_packages_description', [], session('locale')) }}
        </p>
    </div>

    <a href="javascript:void(0);" class="btn btn-success btn-rounded d-flex align-items-center mt-3 mt-md-0"
       data-bs-toggle="modal" data-bs-target="#add_package_modal">
        <i class="fas fa-plus-circle me-2"></i> {{ trans('messages.add_package_lang', [], session('locale')) }}
    </a>
</div>





        <!-- Expenses Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table id="all_expenses" class="table table-striped mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>{{ trans('messages.sr_no', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.expense_name_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.expense_category_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.amount_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.expense_date_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.type_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.file_lang', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.added_by', [], session('locale')) }}</th>
                                        <th>{{ trans('messages.added_on', [], session('locale')) }}</th>
                                        <th class="text-end">{{ trans('messages.action', [], session('locale')) }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Filled via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>




  <div class="modal fade" id="add_expense_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-success text-white rounded-top">
                <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel">
                    <i class="fas fa-plus-circle me-2 fs-4"></i>
                    {{ trans('messages.add_expense_lang', [], session('locale')) }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body overflow-auto" style="max-height: 80vh;">
                <form class="add_expense">
                    @csrf
                    <input type="hidden" class="expense_id" name="expense_id">

                    <div class="row g-4">
                        <!-- Expense Type -->
                        <div class="col-md-4">
                            <label class="form-label small">
                                <i class="fas fa-tags text-secondary me-1"></i>
                                {{ trans('messages.expense_type_lang', [], session('locale')) }}
                            </label>
                            <select class="form-control form-control-sm selectpicker expense_type" name="expense_type"  id="expense_type" onchange="toggleRecurringFields(this.value)">
                                <option value="daily">{{ trans('messages.daily_expense_lang', [], session('locale')) }}</option>
                                <option value="fixed">{{ trans('messages.fixed_expense_lang', [], session('locale')) }}</option>
                            </select>
                        </div>

                        <!-- Expense Name -->
                        <div class="col-md-4">
                            <label class="form-label small">
                                <i class="fas fa-file-signature  text-primary me-1"></i>
                                {{ trans('messages.expense_name_lang', [], session('locale')) }}
                            </label>
                            <input class="form-control form-control-sm expense_name" name="expense_name" type="text"
                                   placeholder="{{ trans('messages.expense_name_placeholder', [], session('locale')) }}">
                        </div>

                        <!-- Category -->
                        <div class="col-md-4">
                            <label class="form-label small">
                                <i class="fas fa-list-alt text-info me-1"></i>
                                {{ trans('messages.expense_category_lang', [], session('locale')) }}
                            </label>
                            <select class="form-control form-control-sm selectpicker category_id " id="category_id" name="category_id">
                                <option value="">{{ trans('messages.select_category_lang', [], session('locale')) }}</option>
                                @foreach ($expense_cats as $expense_cat)
                                    <option value="{{ $expense_cat->id }}">{{ $expense_cat->expense_category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Expense Date -->
                        <div class="col-md-4">
                            <label class="form-label small">
                                <i class="fas fa-calendar-day text-success me-1"></i>
                                {{ trans('messages.expense_date_lang', [], session('locale')) }}
                            </label>
                            <input class="form-control form-control-sm expense_date" name="expense_date" type="date">
                        </div>

                        <!-- Amount -->
                        <div class="col-md-4">
                            <label class="form-label small">
                                <i class="fas fa-money-bill-wave text-warning me-1"></i>
                                {{ trans('messages.amount_lang', [], session('locale')) }}
                            </label>
                            <input class="form-control form-control-sm amount" name="amount" type="text"
                                   placeholder="{{ trans('messages.amount_placeholder', [], session('locale')) }}">
                        </div>


                        <!-- Is Recurring (only for fixed) -->
                        <div class="col-md-4 recurring-section d-none">
                            <label class="form-check-label">
                                <input type="checkbox" name="is_recurring" class="form-check-input me-2">
                                {{ trans('messages.is_recurring_lang', [], session('locale')) }}
                            </label>
                        </div>

                        <!-- Recurring Frequency -->
                        <div class="col-md-4 recurring-section d-none mt-2">
                            <label class="form-label small">
                                {{ trans('messages.recurring_frequency_lang', [], session('locale')) }}
                            </label>
                            <select class="form-control form-control-sm recurring_frequency selectpicker" id="recurring_frequency" name="recurring_frequency">
                                <option value="monthly">{{ trans('messages.monthly_lang', [], session('locale')) }}</option>
                                <option value="quarterly">{{ trans('messages.quarterly_lang', [], session('locale')) }}</option>
                                <option value="yearly">{{ trans('messages.yearly_lang', [], session('locale')) }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Notes -->
                        <div class="col-md-8">
                            <label class="form-label">
                                <i class="fas fa-sticky-note text-muted me-1"></i>
                                {{ trans('messages.notes_lang', [], session('locale')) }}
                            </label>
                            <textarea class="form-control notes" rows="4" name="notes"
                                      placeholder="{{ trans('messages.notes_placeholder', [], session('locale')) }}"></textarea>
                        </div>

                        <!-- File Upload -->
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center position-relative">
                            <label class="form-label">
                                <i class="fas fa-upload text-info me-1"></i> {{ trans('messages.upload_file_lang', [], session('locale')) }}
                            </label>
                            <img id="filePreview" src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                 alt="Preview" class="img-fluid rounded shadow-sm"
                                 style="max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;">
                            <input type="file" id="fileUpload" name="expense_file" class="d-none expense_file"
                                   accept="image/*, .pdf, .doc, .docx, .xls, .xlsx">
                            <span id="removeFile" class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                  style="cursor: pointer; display: none;">&times;</span>
                            <div id="fileName" class="mt-2 text-muted small"></div>
                        </div>
                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            {{ trans('messages.close', [], session('locale')) }}
                        </button>
                        <button type="submit" class="btn btn-success submit_form">
                            {{ trans('messages.submit', [], session('locale')) }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');
    const fileNameDisplay = document.getElementById('fileName');
    const removeButton = document.getElementById('removeFile');

    // Show file preview on change
    fileUpload.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (file) {
            const fileName = file.name.toLowerCase();
            fileNameDisplay.textContent = file.name;

            // Show remove (×) button
            removeButton.style.display = 'block';

            // Preview logic
            if (file.type.startsWith('image')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    filePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                // For non-image types, show file-type-based placeholder
                if (fileName.endsWith('.pdf')) {
                    filePreview.src = "{{ asset('images/dummy_images/pdf.png') }}";
                } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                    filePreview.src = "{{ asset('images/dummy_images/word.jpeg') }}";
                } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                    filePreview.src = "{{ asset('images/dummy_images/excel.jpeg') }}";
                } else {
                    filePreview.src = "{{ asset('images/dummy_images/file.png') }}";
                }
            }
        }
    });

    // Remove the file
    removeButton.addEventListener('click', function () {
        fileUpload.value = '';
        filePreview.src = "{{ asset('images/dummy_images/cover-image-icon.png') }}";
        fileNameDisplay.textContent = '';
        removeButton.style.display = 'none';
    });

    function toggleRecurringFields(value) {
    const recurringFields = document.querySelectorAll('.recurring-section');
    recurringFields.forEach(el => el.classList.toggle('d-none', value !== 'fixed'));
}
</script>

 @include('layouts.footer')
@endsection
