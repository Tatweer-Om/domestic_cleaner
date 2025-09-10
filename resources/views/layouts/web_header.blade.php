@php
    $locale = app()->getLocale();
    $isRtl = in_array($locale, ['ar']); // keep what you actually support
@endphp


<!DOCTYPE html>
<html lang="{{ current_locale() }}" dir="{{ dir_attr() }}">
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
    <title>CLEANING</title>
    <!-- Icon fonts -->
    <link href="{{ asset('assets/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/flaticon.css') }}" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    @if ($isRtl)
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
    <link href="{{ asset('assets/sass/style.css') }}" rel="stylesheet">
    @if ($isRtl)
        <link href="{{ asset('assets/sass/style.rtl.css') }}" rel="stylesheet">
    @endif
</head>

<style>
    .navbar-header {
    height: 50px; /* Adjust the height as needed (e.g., 50px for a smaller navbar) */
    line-height: 50px; /* Ensure vertical alignment of content */
}

.navbar-brand img {
    width: 113px;
    height: auto; /* Maintain aspect ratio */
    max-height: 120%; /* Ensure the logo fits within the navbar height */
}

@media (max-width: 768px) {
    .navbar-header {
        height: 40px; /* Smaller height for mobile devices */
        line-height: 40px;
    }

    .navbar-brand img {
        width: 80px; /* Smaller logo width for mobile */
        margin-bottom: 4px; /* Slightly smaller offset on mobile */
    }
}
</style>

<body class="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <div class="page-wrapper">
        <!-- start preloader -->
        <div class="preloader">
            <div class="vertical-centered-box">
                <div class="content">
                    <div class="loader-circle"></div>
                    <div class="loader-line-mask">
                        <div class="loader-line"></div>
                    </div>
                    <img src="{{ asset('assets/images/preloader.png') }}" alt="">
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
                                                <span class="sr-only">Toggle navigation</span>
                                                <span class="icon-bar first-angle"></span>
                                                <span class="icon-bar middle-angle"></span>
                                                <span class="icon-bar last-angle"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-6">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="index.html"><img
                                                    src="{{ asset('images/logo/logo.png') }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-1 col-1">
                                        <div id="navbar" class="collapse navbar-collapse navigation-holder">
                                            <button class="menu-close"><i class="ti-close"></i></button>
                                            <ul class="nav navbar-nav mb-2 mb-lg-0">
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('/') }}">Home</a>

                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('service_page') }}">Services</a>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('about') }}">About Us</a>

                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('contact') }}">Contact Us</a>

                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="{{ url('policy') }}">Policy</a>

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
                                                        {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                                                    </span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3"
                                                    aria-labelledby="langMenu">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('locale.switch', 'en') }}">🇬🇧 English</a>
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('locale.switch', 'ar') }}">🇸🇦 العربية</a>
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
                                                            Hello, {{ Auth::user()->user_name }}
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center"
                                                                href="{{ url('user_profile/' . Auth::user()->id) }}">
                                                                <i class="fi flaticon-user me-2 text-muted"
                                                                    style="font-size:16px;"></i> Profile
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center text-danger"
                                                                href="#" id="btnLogout">
                                                                <i class="fi flaticon-logout me-2"
                                                                    style="font-size:16px;"></i> Logout
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
