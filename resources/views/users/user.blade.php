@extends('layouts.header')

@section('main')
    @push('title')
        <title> Users</title>
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
                        <i class="bi bi-person-lines-fill me-2"></i> {{ trans('messages.users', [], session('locale')) }}
                    </h3>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ trans('messages.manage_users_subtitle', [], session('locale')) }}
                    </p>
                </div>

                <a href="javascript:void();" class="btn btn-success btn-rounded shadow-sm d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#add_user_modal">
                    <i class="fas fa-user-plus"></i>
                    <span>{{ trans('messages.add_user', [], session('locale')) }}</span>
                </a>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="all_user"
                                    class="table table-striped table-hover mb-4 dataTablesCard fs-14 align-middle text-nowrap">
                                    <thead class="bg-light text-dark">
                                        <tr>
                                            <th><i class="fa fa-hashtag me-1 text-primary"></i>
                                                {{ trans('messages.sr_no') }}</th>
                                            <th><i class="fa fa-user me-1 text-primary"></i>
                                                {{ trans('messages.user_name') }}</th>
                                            <th><i class="fa fa-phone me-1 text-success"></i> {{ trans('messages.phone') }}
                                            </th>
                                            <th><i class="fa fa-users-cog me-1 text-warning"></i>
                                                {{ trans('messages.user_type') }}</th>
                                            <th><i class="fa fa-user-shield me-1 text-info"></i>
                                                {{ trans('messages.added_by') }}</th>
                                            <th><i class="fa fa-calendar me-1 text-danger"></i>
                                                {{ trans('messages.added_on') }}</th>
                                            <th class="text-center"><i class="fa fa-cogs me-1 text-secondary"></i>
                                                {{ trans('messages.action') }}</th>
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

        <div class="modal fade" id="add_user_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel">
                            <i class="bi bi-person-fill me-2"></i> {{ trans('messages.add_user', [], session('locale')) }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ trans('messages.close', [], session('locale')) }}"></button>
                    </div>
                    <div class="modal-body">
                        <form class="add_user">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-user me-2 text-primary"></i>
                                            {{ trans('messages.user_name', [], session('locale')) }}
                                        </label>
                                        <input type="text" class="form-control user_name" name="user_name"
                                            placeholder="{{ trans('messages.user_name', [], session('locale')) }}">
                                    </div>
                                </div>

                                <input type="text" class="user_id" name="user_id" hidden>

                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-phone me-2 text-success"></i>
                                            {{ trans('messages.mobile_no', [], session('locale')) }}
                                        </label>
                                        <input type="number" class="form-control phone" name="phone"
                                            placeholder="{{ trans('messages.mobile_no', [], session('locale')) }}">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-envelope me-2 text-info"></i>
                                            {{ trans('messages.email', [], session('locale')) }} <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control email" name="email"
                                            placeholder="{{ trans('messages.email', [], session('locale')) }}">
                                        <div class="invalid-feedback">
                                            {{ trans('messages.enter_email', [], session('locale')) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-lock me-2 text-warning"></i>
                                            {{ trans('messages.password', [], session('locale')) }} <span
                                                class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control password" name="password">
                                            <span class="show-pass eye">
                                                <i class="fa fa-eye-slash"></i>
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i class="fa fa-users me-2 text-secondary"></i>
                                            {{ trans('messages.user_type', [], session('locale')) }} <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select class="user_type default-select form-control wide mb-3" name="user_type">
                                            <option value="">{{ trans('messages.choose', [], session('locale')) }}...
                                            </option>
                                            <option value="1">{{ trans('messages.admin', [], session('locale')) }}
                                            </option>
                                            <option value="2">{{ trans('messages.user', [], session('locale')) }}
                                            </option>
                                                <option value="3">{{ trans('messages.driver', [], session('locale')) }}
                                            </option>
                                                <option value="4">{{ trans('messages.worker', [], session('locale')) }}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Permissions --}}
                            <div class="container mt-3" id="checked_html">
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                    <label class="form-check-label fw-bold fs-6" for="selectAll">
                                        <i class="bi bi-check2-square me-1"></i>
                                        {{ trans('messages.all_permissions', [], session('locale')) }}
                                    </label>
                                </div>

                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 g-1">
                                    @php
                                        $permissions = [
                                            [
                                                'id' => 'dashboard',
                                                'value' => 1,
                                                'label' => 'dashboard',
                                                'icon' => 'bi-speedometer2',
                                                'color' => 'text-primary',
                                            ],
                                            [
                                                'id' => 'locations',
                                                'value' => 2,
                                                'label' => 'locations',
                                                'icon' => 'fas fa-map-marker-alt',
                                                'color' => 'text-warning',
                                            ],
                                            [
                                                'id' => 'drivers',
                                                'value' => 3,
                                                'label' => 'drivers',
                                                'icon' => 'fas fa-car-side',
                                                'color' => 'text-info',
                                            ],
                                            [
                                                'id' => 'workers',
                                                'value' => 4,
                                                'label' => 'workers',
                                                'icon' => 'fas fa-people-carry',
                                                'color' => 'text-success',
                                            ],
                                            [
                                                'id' => 'users',
                                                'value' => 5,
                                                'label' => 'users',
                                                'icon' => 'bi-person-fill-gear',
                                                'color' => 'text-secondary',
                                            ],
                                            [
                                                'id' => 'bookings',
                                                'value' => 6,
                                                'label' => 'bookings',
                                                'icon' => 'bi-calendar-check',
                                                'color' => 'text-danger',
                                            ],
                                            [
                                                'id' => 'reports',
                                                'value' => 7,
                                                'label' => 'reports',
                                                'icon' => 'bi-graph-up-arrow',
                                                'color' => 'text-primary',
                                            ],
                                                [
                                                'id' => 'expense',
                                                'value' => 8,
                                                'label' => 'expense',
                                                'icon' => 'bi-graph-up-arrow',
                                                'color' => 'text-primary',
                                            ],
                                              [
                                                'id' => 'sms',
                                                'value' => 9,
                                                'label' => 'sms',
                                                'icon' => 'bi-graph-up-arrow',
                                                'color' => 'text-primary',
                                            ],
                                              [
                                                'id' => 'account',
                                                'value' => 10,
                                                'label' => 'account',
                                                'icon' => 'bi-graph-up-arrow',
                                                'color' => 'text-primary',
                                            ],
                                              [
                                                'id' => 'customer',
                                                'value' => 11,
                                                'label' => 'account',
                                                'icon' => 'bi-graph-up-arrow',
                                                'color' => 'text-primary',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($permissions as $permission)
                                        <div class="col">
                                            <div class="form-check d-flex align-items-center small">
                                                <input type="checkbox" class="form-check-input me-2 permission-checkbox"
                                                    name="permissions[]" value="{{ $permission['value'] }}"
                                                    id="{{ $permission['id'] }}">
                                                <label class="form-check-label" for="{{ $permission['id'] }}">
                                                    <i
                                                        class="{{ $permission['icon'] }} me-1 {{ $permission['color'] }}"></i>
                                                    {{ trans('messages.' . $permission['label'], [], session('locale')) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Notes & Image --}}
                            <div class="row mt-3">
                                <div class="col-12 col-md-8">
                                    <div class="form-group">
                                        <label class="col-form-label">
                                            <i
                                                class="fa fa-sticky-note me-2 text-info"></i>{{ trans('messages.note', [], session('locale')) }}
                                        </label>
                                        <textarea class="form-control notes" rows="4" name="notes"></textarea>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
                                    <div class="form-group text-center position-relative">
                                        <label
                                            class="col-form-label">{{ trans('messages.image', [], session('locale')) }}</label>
                                        <img id="imagePreview"
                                            src="{{ asset('images/dummy_images/cover-image-icon.png') }}" alt="{{ trans('messages.preview', [], session('locale')) }}"
                                            class="img-fluid rounded user_image"
                                            style="width: 100%; max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;">
                                        <input type="file" id="imageUpload" name="user_image"
                                            class="d-none user_image" accept="image/*">
                                        <span id="removeImage"
                                            class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                            style="cursor: pointer; display: none;">&times;</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                    {{ trans('messages.close', [], session('locale')) }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('messages.add_user', [], session('locale')) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            // Open file dialog on image click
            document.getElementById('imagePreview').addEventListener('click', function() {
                document.getElementById('imageUpload').click();
            });

            // Show preview of selected image
            document.getElementById('imageUpload').addEventListener('change', function(event) {
                const input = event.target;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('removeImage').style.display = 'inline-block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Remove image and reset preview
            document.getElementById('removeImage').addEventListener('click', function() {
                document.getElementById('imageUpload').value = '';
                document.getElementById('imagePreview').src =
                "{{ asset('images/dummy_images/cover-image-icon.png') }}";
                this.style.display = 'none';
            });
        </script>

    </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Data</button>
    </div>
    </form>
    </div>

    </div>
    </div>
    </div>
    </div>

    @include('layouts.footer')
@endsection
