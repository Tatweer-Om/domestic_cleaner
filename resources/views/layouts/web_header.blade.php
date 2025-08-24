<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="wpOceans">
        <meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
<!-- Page Title -->
<title>CLEANING</title>
<!-- Icon fonts -->
<link href="{{ asset('assets/css/themify-icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/flaticon.css') }}" rel="stylesheet">
<!-- Bootstrap core CSS -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
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
    </head>
<body>
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
                                            <a class="navbar-brand" href="index.html"><img src="assets/images/logo.svg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-1 col-1">
                                        <div id="navbar" class="collapse navbar-collapse navigation-holder">
                                            <button class="menu-close"><i class="ti-close"></i></button>
                                            <ul class="nav navbar-nav mb-2 mb-lg-0">
                                                <li class="menu-item-has-children">
                                                    <a href="#">Home</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="index.html">Home style 1</a></li>
                                                        <li><a href="index-2.html">Home style 2</a></li>
                                                        <li><a href="index-3.html">Home style 3</a></li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="#">Pages</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="about.html">About</a></li>
                                                        <li><a href="appoinment.html">Appoinment</a></li>
                                                        <li><a href="project.html">Portfolio</a></li>
                                                        <li><a href="project-single.html">Portfolio Single</a></li>
                                                        <li><a href="team.html">Team Page</a></li>
                                                        <li><a href="team-single.html">Team Single</a></li>
                                                        <li><a href="faq.html">Faq Page</a></li>
                                                        <li><a href="404.html">404 Error</a></li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="#">services</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="service.html">services</a></li>
                                                        <li><a href="service-single.html">services Single</a></li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="#">Projects</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="project.html">Projects</a></li>
                                                        <li><a href="project-single.html">Project Single</a></li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="#">Shop</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="shop.html">Shop</a></li>
                                                        <li><a href="shop-single.html">Shop Single</a></li>
                                                        <li><a href="cart.html">Cart</a></li>
                                                        <li><a href="checkout.html">Checkout</a></li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="#">Blog</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="blog.html">Blog right sidebar</a></li>
                                                        <li><a href="blog-left-sidebar.html">Blog left sidebar</a></li>
                                                        <li><a href="blog-fullwidth.html">Blog fullwidth</a></li>
                                                        <li class="menu-item-has-children">
                                                            <a href="#">Blog details</a>
                                                            <ul class="sub-menu">
                                                                <li><a href="blog-single.html">Blog details right
                                                                        sidebar</a>
                                                                </li>
                                                                <li><a href="blog-single-left-sidebar.html">Blog details
                                                                        left
                                                                        sidebar</a></li>
                                                                <li><a href="blog-single-fullwidth.html">Blog details
                                                                        fullwidth</a></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li><a href="contact.html">contact</a></li>
                                            </ul>
                                        </div>
                                        <!-- end of nav-collapse -->
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-2">
                                        <div class="header-right">
                                            @auth
                                            <div class="dropdown">
                                                <a href="#" class="d-flex align-items-center dropdown-toggle"
                                                id="userMenu" data-bs-toggle="dropdown" aria-expanded="false"
                                                style="text-decoration:none; font-weight:500;">

                                                    {{-- User icon (larger size) --}}
                                                    <i class="fi flaticon-user me-4" style="font-size:28px; color:#6c757d;"></i>

                                                    {{-- Username (only visible on desktop/tablet) --}}
                                                    <span class="d-none d-md-inline" style="color:#6c757d; font-size:15px;">
                                                        {{ mb_substr(Auth::user()->user_name, 0, 6) }}
                                                    </span>
                                                </a>

                                                {{-- Dropdown Menu --}}
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3" aria-labelledby="userMenu">
                                                    <li class="dropdown-header text-muted small">
                                                        Hello, {{ Auth::user()->user_name }}
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                                            <i class="fi flaticon-user me-2 text-muted" style="font-size:16px;"></i> Profile
                                                        </a>
                                                    </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center text-danger" href="#" id="btnLogout">
                                                        <i class="fi flaticon-logout me-2" style="font-size:16px;"></i> Logout
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
