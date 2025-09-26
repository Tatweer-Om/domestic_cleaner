<?php
$locale = session('locale');

if ($locale == 'ar') {
    $class = 'rtl';
} else {
    $class = 'ltr';
}
?>
<!DOCTYPE html>
<html lang="en" dir="{{ $class }}" class="{{ $class }}">

<head>

    <!-- Title -->
    @stack('title')
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="DexignZone">
    <meta name="robots" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">




    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logo.png') }}">

    <link href="{{ asset('vendor/fullcalendar/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link
        href="{{ asset('vendor/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/css/toastr.min.css') }}">
    <!-- <link href="{{ asset('vendor/bootstrap-datetimepicker-master/css/jquery-ui.css') }}"rel="stylesheet"> -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">

    <!-- Material color picker -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link href="{{ asset('vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"
        rel="stylesheet">

    <link href="{{ url('css/style.css') }}" rel="stylesheet">
    <link class="main-css" href="{{ asset('css/style-rtl.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic&display=swap" rel="stylesheet">




</head>

<body>

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">

        <div class="nav-header">


            <a href="{{ url('home') }}" class="brand-logo d-flex align-items-center">
                <img src="{{ asset('images/logo/logo.png') }}"
                    alt="Logo"
                    class="img-fluid"
                    style="max-height: 60px; height: auto; width: auto;">
            </a>


            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                {{ trans('messages.dashboard_title', [], session('locale')) }}
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">

                            <!-- <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link  ai-icon" href="javascript:;" role="button"
                                    data-bs-toggle="dropdown">
                                    <svg width="25" height="25" viewBox="0 0 26 26" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.75 14.8385V12.0463C21.7471 9.88552 20.9385 7.80353 19.4821 6.20735C18.0258 4.61116 16.0264 3.61555 13.875 3.41516V1.625C13.875 1.39294 13.7828 1.17038 13.6187 1.00628C13.4546 0.842187 13.2321 0.75 13 0.75C12.7679 0.75 12.5454 0.842187 12.3813 1.00628C12.2172 1.17038 12.125 1.39294 12.125 1.625V3.41534C9.97361 3.61572 7.97429 4.61131 6.51794 6.20746C5.06159 7.80361 4.25291 9.88555 4.25 12.0463V14.8383C3.26257 15.0412 2.37529 15.5784 1.73774 16.3593C1.10019 17.1401 0.751339 18.1169 0.75 19.125C0.750764 19.821 1.02757 20.4882 1.51969 20.9803C2.01181 21.4724 2.67904 21.7492 3.375 21.75H8.71346C8.91521 22.738 9.45205 23.6259 10.2331 24.2636C11.0142 24.9013 11.9916 25.2497 13 25.2497C14.0084 25.2497 14.9858 24.9013 15.7669 24.2636C16.548 23.6259 17.0848 22.738 17.2865 21.75H22.625C23.321 21.7492 23.9882 21.4724 24.4803 20.9803C24.9724 20.4882 25.2492 19.821 25.25 19.125C25.2486 18.117 24.8998 17.1402 24.2622 16.3594C23.6247 15.5786 22.7374 15.0414 21.75 14.8385ZM6 12.0463C6.00232 10.2113 6.73226 8.45223 8.02974 7.15474C9.32723 5.85726 11.0863 5.12732 12.9212 5.125H13.0788C14.9137 5.12732 16.6728 5.85726 17.9703 7.15474C19.2677 8.45223 19.9977 10.2113 20 12.0463V14.75H6V12.0463ZM13 23.5C12.4589 23.4983 11.9316 23.3292 11.4905 23.0159C11.0493 22.7026 10.716 22.2604 10.5363 21.75H15.4637C15.284 22.2604 14.9507 22.7026 14.5095 23.0159C14.0684 23.3292 13.5411 23.4983 13 23.5ZM22.625 20H3.375C3.14298 19.9999 2.9205 19.9076 2.75644 19.7436C2.59237 19.5795 2.50014 19.357 2.5 19.125C2.50076 18.429 2.77757 17.7618 3.26969 17.2697C3.76181 16.7776 4.42904 16.5008 5.125 16.5H20.875C21.571 16.5008 22.2382 16.7776 22.7303 17.2697C23.2224 17.7618 23.4992 18.429 23.5 19.125C23.4999 19.357 23.4076 19.5795 23.2436 19.7436C23.0795 19.9076 22.857 19.9999 22.625 20Z"
                                            fill="#36C95F" />
                                    </svg>
                                    <span class="badge light text-white bg-primary"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <div id="DZ_W_Notification1" class="widget-media dz-scroll p-3 height380">
                                        <ul class="timeline">
                                            <li>
                                                <div class="timeline-panel">

                                                    <div class="media-body">

                                                    </div>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li> -->

                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell dz-theme-mode" href="javascript:void(0);">
                                    <i id="icon-light" class="fas fa-sun"></i>
                                    <i id="icon-dark" class="fas fa-moon"></i>

                                </a>
                            </li>
                            <li class="nav-item dropdown notification_dropdown">
                                @if ($locale == 'ar')
                                <a class="nav-link lang-switch" data-lang="en" href="{{ route('switch_language', ['locale' => 'en']) }}">
                                    <img src="{{ asset('flags/us.png') }}" class="me-1" height="12">
                                </a>
                                @else
                                <a class="nav-link lang-switch" data-lang="ar" href="{{ route('switch_language', ['locale' => 'ar']) }}">
                                    <img src="{{ asset('flags/om.png') }}" class="me-1" height="12">
                                </a>
                                @endif
                            </li>

                            <li class="nav-item dropdown header-profile">
                             <a class="nav-link" href="javascript:;" role="button" data-bs-toggle="dropdown">
                                <img src="{{ asset('images/logo/logo.png') }}" width="20" alt="image">
                                <div class="header-info">
                                    <span>
                                        {{ trans('messages.admin_hello', [], session('locale')) }}
                                        <strong>{{ Auth::user()->user_name ?? '' }}</strong>
                                    </span>
                                </div>
                            </a>

                                <div class="dropdown-menu dropdown-menu-end">

                                    <a href="#" class="dropdown-item ai-icon btn-logout"
                                        data-redirect="{{ url('/login_page') }}">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                            width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span class="ms-2">{{ trans('messages.logout', [], session('locale')) }}</span>
                                    </a>


                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>


@php
    $permissions = explode(',', Auth::user()->permissions ?? '');
@endphp

<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">

            {{-- Dashboard --}}
            @if(in_array(1, $permissions))
            <li>
                <a class="ai-icon" href="{{ url('home') }}" aria-expanded="false">
                    <i class="flaticon-381-home text-primary"></i>
                    <span class="nav-text">{{ trans('messages.dashboard', [], session('locale')) }}</span>
                </a>
            </li>
            @endif

            {{-- Locations --}}
            @if(in_array(2, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="fas fa-map-marker-alt text-warning"></i>
                    <span class="nav-text">{{ trans('messages.locations', [], session('locale')) }}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('location') }}">{{ trans('messages.all_locations', [], session('locale')) }}</a></li>
                </ul>
            </li>
            @endif

            {{-- Drivers --}}
            @if(in_array(3, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="fas fa-car-side text-info"></i>
                    <span class="nav-text">{{ trans('messages.drivers', [], session('locale')) }}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('driver') }}">{{ trans('messages.all_drivers', [], session('locale')) }}</a></li>
                </ul>
            </li>
            @endif

            {{-- Workers --}}
            @if(in_array(4, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="fas fa-people-carry text-danger"></i>
                    <span class="nav-text">{{ trans('messages.workers', [], session('locale')) }}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('worker') }}">{{ trans('messages.all_workers', [], session('locale')) }}</a></li>
                </ul>
            </li>
            @endif

            {{-- Bookings --}}
            @if(in_array(6, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="fas fa-calendar-alt text-danger"></i>
                    <span class="nav-text">{{ trans('messages.bookings_visits', [], session('locale')) }}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('all_bookings') }}">{{ trans('messages.all_bookings', [], session('locale')) }}</a></li>
                    <li><a href="{{ url('all_visits') }}">{{ trans('messages.all_visits', [], session('locale')) }}</a></li>
                </ul>
            </li>
            @endif
         @if(in_array(6, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="fas fa-clipboard-list text-primary"></i>
                    <span class="nav-text">{{ trans('messages.services', [], session('locale')) }}</span>
                </a>
                <ul aria-expanded="false">
                    <li>
                        <a href="{{ url('service') }}">
                            {{ trans('messages.all_services', [], session('locale')) }}
                        </a>
                    </li>
                </ul>
            </li>
        @endif


            {{-- Users --}}
            @if(in_array(5, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="flaticon-381-user-1"
                       style="font-size:18px;
                              background: linear-gradient(45deg, #ff6b6b, #feca57, #48dbfb, #1dd1a1);
                              -webkit-background-clip: text;
                              -webkit-text-fill-color: transparent;">
                    </i>
                    <span class="nav-text">{{ trans('messages.users', [], session('locale')) }}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('user') }}">{{ trans('messages.add_user', [], session('locale')) }}</a></li>
                    <li><a href="{{ url('general_users') }}">{{ trans('messages.general_users', [], session('locale')) }}</a></li>
                    <li><a href="{{ url('worker_users') }}">{{ trans('messages.worker_users', [], session('locale')) }}</a></li>
                    <li><a href="{{ url('driver_users') }}">{{ trans('messages.driver_users', [], session('locale')) }}</a></li>
                </ul>
            </li>
            @endif

            {{-- Reports --}}
            @if(in_array(7, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon" aria-expanded="false">
                    <i class="bi bi-graph-up-arrow text-primary"></i>
                    <span class="nav-text">{{ trans('messages.reports', [], session('locale')) }}</span>
                </a>
            </li>
            @endif

            {{-- Expense --}}
            @if(in_array(8, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="fas fa-money-bill-wave text-success"></i>
                    <span class="nav-text">{{ trans('messages.expense', [], session('locale')) }}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('expense_category') }}">{{ trans('messages.expense_category', [], session('locale')) }}</a></li>
                    <li><a href="{{ url('expense') }}">{{ trans('messages.expense', [], session('locale')) }}</a></li>
                </ul>
            </li>
            @endif

            {{-- SMS --}}
            @if(in_array(9, $permissions))
            <li>
                <a href="{{ url('sms') }}" class="ai-icon" aria-expanded="false">
                    <i class="bi bi-chat-dots text-primary"></i>
                    <span class="nav-text">{{ trans('messages.sms', [], session('locale')) }}</span>
                </a>
            </li>
            @endif

            {{-- Account --}}
            <!-- @if(in_array(10, $permissions))
            <li>
                <a href="{{ url('account') }}" class="ai-icon" aria-expanded="false">
                    <i class="bi bi-person-circle text-primary"></i>
                    <span class="nav-text">{{ trans('messages.account', [], session('locale')) }}</span>
                </a>
            </li>
            @endif -->

            {{-- Customers --}}
            @if(in_array(11, $permissions))
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="bi bi-people-fill text-success"></i>
                    <span class="nav-text">{{ trans('messages.customers', [], session('locale')) }}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('package') }}">{{ trans('messages.all_customer', [], session('locale')) }}</a></li>
                </ul>
            </li>
            @endif

        </ul>
    </div>
</div>



        <!--**********************************
            Sidebar end
        ***********************************-->
        @yield('main')