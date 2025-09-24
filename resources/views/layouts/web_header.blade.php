@php
    $locale = session('locale');

    if ($locale == 'ar') {
        $class = 'rtl';
    } else {
        $class = 'ltr';
    }
 
@endphp


<!DOCTYPE html>
<html lang="{{ current_locale() }}" dir="{{ $class }}">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="wpOceans">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/logo.png') }}">
    <!-- Page Title -->
    <title>{{ trans('messages.site_title', [], session('locale')) }}</title>
    <!-- Icon fonts -->
    <link href="{{ asset('assets/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/flaticon.css') }}" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    @if ($locale == 'ar')
        <link href="{{ asset('assets/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    @endif
    <!-- Plugins for this template -->
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/owl.theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/swiper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/owl.transitions.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/jquery.fancybox.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/odometer-theme-default.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/css/toastr.min.css') }}">
    <!-- Custom styles for this template -->
    
    @if ($locale == 'ar')
        <link href="{{ asset('assets/sass/style.rtl.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('assets/sass/style.css') }}" rel="stylesheet">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-search@3.0.2/dist/leaflet-search.min.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-search@3.0.2/dist/leaflet-search.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<style>
    /* .navbar-header {
    height: 50px;  
    line-height: 50px; 
} */

.navbar-brand img {
    width: 113px;
    height: auto; /* Maintain aspect ratio */
    max-height: 120%; /* Ensure the logo fits within the navbar height */
}

@media (max-width: 768px) {
    /* .navbar-header {
        height: 40px;  
        line-height: 40px;
    } */

    .navbar-brand img {
        width: 80px; /* Smaller logo width for mobile */
        margin-bottom: 4px; /* Slightly smaller offset on mobile */
    }
}


</style>

<body class="{{ $locale == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="page-wrapper">
        <!-- start preloader -->
        <div class="preloader">
            <div class="vertical-centered-box">
                <div class="content">
                    <div class="loader-circle"></div>
                    <!-- <div class="loader-line-mask">
                        <div class="loader-line"></div>
                    </div> -->
                    <img src="{{ asset('images/logo/logo.png') }}" alt="" style="width: 100px; height: 100px;">
                </div>
            </div>
        </div> <!-- end preloader -->
        <div id="smooth-wrapper">
            <div id="smooth-content">

                <!-- Start header -->
                <header id="header" class="box-style">
                    <!-- start topbar -->
                    <div class="topbar">

                    </div>

                    <div class="wpo-site-header">
                        <nav class="navigation navbar navbar-expand-lg navbar-light">
                            <div class="container-fluid">
                                <div class="row align-items-center">
                                    <div class="col-lg-3 col-md-3 col-3 d-lg-none dl-block">
                                        <div class="mobail-menu">
                                            <button type="button" class="navbar-toggler open-btn">
                                                <span class="sr-only">{{ trans('messages.toggle_navigation', [], session('locale')) }}</span>
                                                <span class="icon-bar first-angle"></span>
                                                <span class="icon-bar middle-angle"></span>
                                                <span class="icon-bar last-angle"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-6">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="{{url('/')}}"><img
                                                    src="{{ asset('images/logo/logo.png') }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-1 col-1">
                                        <div id="navbar" class="collapse navbar-collapse navigation-holder">
                                            <button class="menu-close"><i class="ti-close"></i></button>
                                            <ul class="nav navbar-nav mb-2 mb-lg-0">
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('/') }}">{{ trans('messages.nav_home', [], session('locale')) }}</a>

                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('service_page') }}">{{ trans('messages.nav_services', [], session('locale')) }}</a>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('about') }}">{{ trans('messages.nav_about_us', [], session('locale')) }}</a>

                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('contact') }}">{{ trans('messages.nav_contact_us', [], session('locale')) }}</a>

                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('policy') }}">{{ trans('messages.nav_policy', [], session('locale')) }}</a>

                                                </li>

                                            </ul>
                                        </div>
                                        <!-- end of nav-collapse -->
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-2">
                                        <div class="header-right d-flex align-items-center justify-content-end">
                                            <div class="dropdown me-3">
                                                <a href="#" class="d-flex align-items-center dropdown-toggle"
                                                    id="langMenu" data-bs-toggle="dropdown" aria-expanded="false"
                                                    style="text-decoration:none; font-weight:500;">
                                                    <i class="fi flaticon-world me-2"
                                                        style="font-size:20px; color:#6c757d;"></i>
                                                    <span class="d-none d-md-inline"
                                                        style="font-size:14px; color:#6c757d;">
                                                        {{ app()->getLocale() === 'ar' ? trans('messages.lang_arabic', [], session('locale')) : trans('messages.lang_english', [], session('locale')) }}
                                                    </span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3"
                                                    aria-labelledby="langMenu">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('locale.switch', 'en') }}">🇬🇧 {{ trans('messages.lang_english', [], session('locale')) }}</a>
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('locale.switch', 'ar') }}">🇸🇦 {{ trans('messages.lang_arabic', [], session('locale')) }}</a>
                                                    </li>
                                                </ul>
                                            </div>

                                            @auth
                                                <div class="dropdown">
                                                    <a href="#" class="d-flex align-items-center dropdown-toggle"
                                                        id="userMenu" data-bs-toggle="dropdown" aria-expanded="false"
                                                        style="text-decoration:none; font-weight:500;">
                                                        <i class="fi flaticon-user me-2"
                                                            style="font-size:28px; color:#6c757d;"></i>
                                                        <span class="d-none d-md-inline"
                                                            style="color:#6c757d; font-size:15px;">
                                                            {{ mb_substr(Auth::user()->user_name, 0, 6) }}
                                                        </span>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3"
                                                        aria-labelledby="userMenu">
                                                        <li class="dropdown-header text-muted small">
                                                            {{ trans('messages.user_hello', [], session('locale')) }}, {{ Auth::user()->user_name }}
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center"
                                                                href="{{ url('user_profile/' . Auth::user()->id) }}">
                                                                <i class="fi flaticon-user me-2 text-muted"
                                                                    style="font-size:16px;"></i> {{ trans('messages.user_profile', [], session('locale')) }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center text-danger btn-logout"
                                                                href="#"
                                                                data-redirect="{{ url('/') }}">
                                                                <i class="fi flaticon-logout me-2" style="font-size:16px;"></i>
                                                                {{ trans('messages.user_logout', [], session('locale')) }}
                                                            </a>

                                                        </li>
                                                    </ul>
                                                </div>
                                            @endauth
                                        </div>
                                    </div>


                                </div>
                            </div><!-- end of container -->
                        </nav>
                    </div>

                </header>
                @yield('main')
