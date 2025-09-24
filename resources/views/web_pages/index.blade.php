@extends('layouts.web_header')

@section('main')
@push('title')
<title> {{ trans('messages.home_lang', [], session('locale')) }}</title>
@endpush

<style>
    .slick-dots {
        display: flex !important;
        gap: 6px;
        justify-content: center;
    }

    #workers_slider {
        transition: opacity .25s ease, transform .25s ease;
    }

    #workers_slider.is-swapping {
        opacity: .25;
        transform: scale(.98);
    }

    #workers_slider.is-ready {
        opacity: 1;
        transform: none;
    }

    .location-picker-wrap .bootstrap-select .bs-searchbox .form-control {
        border-radius: .5rem;
        box-shadow: none;
        padding-left: 2.25rem;
        /* space for the small magnifier circle */
    }

    .location-picker-wrap .bootstrap-select .dropdown-menu {
        border-radius: .75rem;
        overflow: hidden;
    }

    .location-picker-wrap .bootstrap-select .dropdown-menu .dropdown-item {
        padding: .45rem .75rem;
    }

    .wpo-service-slide-item {
        width: 390px !important;
    }

    /* Keep all slides on one line and equal height */
    #workers_slider .slick-track {
        display: flex !important;
    }

    #workers_slider .slick-slide {
        height: auto;
    }

    /* Provide consistent side gutters on slides */
    #workers_slider .wpo-service-slide-item {
        padding: 0 10px;
        box-sizing: border-box;
    }

    /* Make the card stretch to fill the slide height */
    #workers_slider .wpo-service-item {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    #workers_slider .wpo-service-img {
        flex: 0 0 auto;
    }

    #workers_slider .wpo-service-text {
        margin-top: 10px;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
    }

    /* Avoid any global .section-padding on each slide (causes width overflow) */
    #map {
        height: 600px;
        width: 100%;
    }

    .controls {
        margin: 10px 0;
        position: relative;
    }

    input,
    button {
        margin: 5px;
        padding: 8px;
    }

    .lang-toggle {
        font-weight: bold;
        cursor: pointer;
        color: blue;
        text-decoration: underline;
    }

    #search-container {
        position: relative;
        display: inline-block;
    }

    #search {
        width: 300px;
    }

    #suggestions {
        position: absolute;
        top: 100%;
        left: 5px;
        right: 5px;
        background: white;
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .controls {
        margin: 10px 0;
        position: relative;
    }

    #search-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    #search {
        width: 100%;
        padding: 6px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    #suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .suggestion-item {
        padding: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    .suggestion-item:hover {
        background: #f0f0f0;
    }

    .lang-toggle {
        font-weight: bold;
        cursor: pointer;
        color: #28a745;
        text-decoration: underline;
        margin: 0 8px;
        font-size: 14px;
    }

    .btn-success,
    .btn-outline-success {
        padding: 6px 12px;
        border-radius: 4px;
        margin: 5px 5px 5px 0;
        font-size: 14px;
    }

    #status {
        font-size: 12px;
    }

    @media (max-width: 991px) {
        #map {
            height: 200px;
        }

        .controls {
            text-align: center;
        }

        .lang-toggle {
            display: inline-block;
            margin: 8px 0;
        }

        #search {
            font-size: 12px;
        }

        .btn-success,
        .btn-outline-success {
            font-size: 12px;
            padding: 5px 10px;
        }

        #suggestions {
            max-height: 120px;
        }

        .suggestion-item {
            font-size: 12px;
            padding: 5px;
        }
    }

    .suggestion-item {
        padding: 8px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background: #f0f0f0;
    }

    .slick-dots li {
        margin: 0;
    }

    .slick-dots li button {
        min-width: 28px;
        min-height: 28px;
    }

    /* compact auth card */
    .auth-card {
        max-width: 360px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        padding: 18px 18px 16px;
        border: 1px solid rgba(0, 0, 0, .06);
    }




    .fixed-img {
        width: 100%;
        height: 250px;
        /* Adjust height for all cards */
        overflow: hidden;
        border-radius: 8px;
        /* Optional: match card style */
    }

    .fixed-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Crops image while maintaining aspect ratio */
    }

    .auth-card .title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .auth-card .sub {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 14px;
    }

    /* compact inputs */
    .auth-card .form-control {
        height: 38px;
        font-size: .9rem;
        border-radius: 10px;
    }

    .auth-card .btn {
        border-radius: 12px;
        height: 40px;
        font-weight: 600;
    }

    /* inline helper row */
    .auth-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }

    .auth-meta a {
        font-size: 12px;
        text-decoration: none;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0;
        color: #94a3b8;
        font-size: 12px;
    }

    .auth-divider:before,
    .auth-divider:after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e9ecef;
    }

    /* spacing under hero content */
    .hero-form-wrap {
        margin-top: 18px;
    }

    @media (max-width: 991.98px) {
        .auth-card {
            max-width: 100%;
        }
    }
</style>
<!-- start page-wrapper -->
<!-- end of header -->


<section class="static-hero box-style" style="position: relative; overflow: hidden;">
    <!-- Leaflet CSS -->


    <div class="container">
        <div class="wraper">
            <div class="row align-items-center">
                <!-- Left: hero content + form -->
                <div class="col-lg-7 col-12">
                    <div class="slide-title wow fadeInUp" data-wow-delay="0.0s">
                        <span>
                            <img src="assets/images/cleaning-icon.svg" alt="">
                            {{ trans('messages.hero_tagline', [], session('locale')) }}
                        </span>
                    </div>

                    <div class="slide-sub-title wow fadeInUp" data-wow-delay="0.3s">
                        <h2>{{ trans('messages.hero_title', [], session('locale')) }}</h2>
                    </div>

                    <!-- Form Card -->
                    <div class="hero-form-wrap wow fadeInUp" data-wow-delay="0.75s">
                        @guest
                        <div class="auth-card" id="authCard">
                            <form id="authForm">
                                <!-- Dynamic Header -->
                                <h5 id="formHeader" class="text-center fw-bold mb-3">
                                    {{ trans('messages.sign_in_heading', [], session('locale')) }}
                                </h5>

                                <!-- Name field (only for Sign Up) -->
                                <div id="nameField" style="display:none;">
                                    <div class="mb-2">
                                        <label class="form-label mb-1 small">
                                            {{ trans('messages.mobile_number', [], session('locale')) }}
                                        </label>
                                        <input type="number" class="form-control"
                                            placeholder="{{ trans('messages.enter_mobile_number', [], session('locale')) }}"
                                            name="phone" id="phone">
                                    </div>
                                </div>

                                <!-- Username -->
                                <div class="mb-2">
                                    <label class="form-label mb-1 small">
                                        {{ trans('messages.username', [], session('locale')) }}
                                    </label>
                                    <input type="text" class="form-control user_name" id="user_name" name="user_name"
                                        placeholder="{{ trans('messages.enter_username', [], session('locale')) }}">
                                </div>

                                <!-- Password -->
                                <div class="mb-2">
                                    <label class="form-label mb-1 small">
                                        {{ trans('messages.password', [], session('locale')) }}
                                    </label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="{{ trans('messages.enter_password', [], session('locale')) }}">
                                </div>

                                <input type="hidden" name="form_index" id="form_index" value="1" hidden>
                                <input type="hidden" name="form_1" value="1" hidden>

                                <!-- Submit Button -->
                                <button type="submit" id="submitBtn" class="btn btn-success w-100 mt-3">
                                    {{ trans('messages.sign_in', [], session('locale')) }}
                                </button>

                                <!-- Divider -->
                                <div class="auth-divider">{{ trans('messages.or', [], session('locale')) }}</div>

                                <!-- Toggle Link -->
                                <p class="text-center mb-0">
                                    <a href="#" id="toggleLink" class="text-success fw-semibold">
                                        {{ trans('messages.create_account', [], session('locale')) }}
                                    </a>
                                </p>
                            </form>
                        </div>
                        @endguest
                        @auth
                        <div class="auth-welcome">
                            <h5 class="fw-bold text-dark mb-1">
                                {{ trans('messages.welcome', [], session('locale')) }}, {{ Auth::user()->user_name }}
                            </h5>
                            <a href="{{ url('user_profile/' . Auth::id()) }}" class="text-success fw-semibold small">
                                {{ trans('messages.my_bookings', [], session('locale')) }}
                            </a>
                        </div>
                        @endauth
                    </div>
                </div>

                <!-- Right: Map -->
                <div class="col-lg-5 col-12">
                    <div class="controls">
                     
                        <div class="d-flex gap-2 flex-wrap">
                         
                          
                        </div>


                        <div id="status" class="small mt-2"></div>
                    </div>
                  

                </div>
            </div>
        </div>
    </div>

    <!-- Shapes -->
    <div class="left-shape">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 948 759" fill="none">
            <path
                d="M0 14C0 6.26801 6.26801 0 14 0H878.445C884.831 0 890.436 4.4205 892.011 10.609C967.074 305.618 966.222 470.707 892.098 748.562C890.468 754.672 884.902 759 878.578 759H14C6.26801 759 0 752.732 0 745V14Z"
                fill="url(#paint0_linear_734_112)" />
            <defs>
                <linearGradient id="paint0_linear_734_112" x1="9.04278" y1="-31.8863" x2="665.174" y2="255.481"
                    gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#FFE5B5" />
                    <stop offset="1" stop-color="#D6E2EA" />
                </linearGradient>
            </defs>
        </svg>
    </div>
    <div class="hero-line-shape new_img-animet" data-speed="100">
        <img src="assets/images/slider/hero-shape.png" alt="">
    </div>





</section>


<div id="servicesContainer">

</div>
<!-- Worker cards will be injected here -->
</div>
<!-- start wpo-choose-section -->
<section class="wpo-choose-section section-padding pt-0">
    <div class="container">
        <div class="wpo-choose-wrap">
            <div class="row">
                <div class="col-lg-6">
                    <div class="wpo-choose-left-side wow fadeInLeftSlow" data-wow-duration="1000ms">
                        <div class="wpo-choose-left-img left-to-right-light">
                            <img src="{{ asset('images/index.png') }}" alt="index"
                                style="height: 500px; width:500px;">
                        </div>
                        <div class="wpo-choose-left-box">
                            <div class="wrap">
                                <div class="inside">
                                    <h2><span class="odometer" data-count="50">00</span>+</h2>
                                    <p>{{ trans('messages.team_member', [], session('locale')) }}</p>
                                </div>
                            </div>
                            <div class="radius-shape">
                                <img src="assets/images/choose/radius-shape.svg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="wpo-choose-right-side wow fadeInRightSlow" data-wow-duration="1000ms">
                        <div class="wpo-section-title-s2">
                            <span>
                                <i><img src="assets/images/cleaning-icon.svg" alt=""></i>
                                {{ trans('messages.why_choose_us', [], session('locale')) }}
                            </span>
                            <h2 class="slide-sub-title wow">
                                {{ trans('messages.why_choose_heading', [], session('locale')) }}
                            </h2>
                            <p>{{ trans('messages.why_choose_desc', [], session('locale')) }}</p>
                        </div>
                        <ul>
                            <li><span>{{ trans('messages.trusted_cleaners', [], session('locale')) }}</span></li>
                            <li class="active">
                                <span>{{ trans('messages.custom_plans', [], session('locale')) }}</span>
                            </li>
                            <li><span>{{ trans('messages.affordable_pricing', [], session('locale')) }}</span></li>
                            <li><span>{{ trans('messages.satisfaction_guarantee', [], session('locale')) }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end wpo-choose-section -->

<!-- start wpo-work-section -->
<section class="wpo-work-section section-padding">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-8">
                <div class="wpo-section-title">
                    <span>
                        <i><img src="assets/images/cleaning-icon-white.svg" alt=""></i>
                        {{ trans('messages.how_it_works', [], session('locale')) }}
                    </span>
                    <h2 class="slide-sub-title wow">
                        {{ trans('messages.how_it_works_heading', [], session('locale')) }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="wpo-work-wrap">
            <div class="row">
                <div class="col col-lg-4 col-md-6 col-12">
                    <div class="wpo-work-item">
                        <div class="wpo-work-icon">
                            <img src="assets/images/work/work-icon-1.svg" alt="">
                        </div>
                        <div class="wpo-work-text">
                            <span>{{ trans('messages.step_register_login', [], session('locale')) }}</span>
                            <h2>01</h2>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
                <div class="col col-lg-4 col-md-6 col-12">
                    <div class="wpo-work-item">
                        <div class="wpo-work-icon">
                            <img src="assets/images/work/work-icon-3.svg" alt="">
                        </div>
                        <div class="wpo-work-text">
                            <span>{{ trans('messages.step_choose_location', [], session('locale')) }}</span>
                            <h2>02</h2>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
                <div class="col col-lg-4 col-md-6 col-12">
                    <div class="wpo-work-item">
                        <div class="wpo-work-icon">
                            <img src="assets/images/work/work-icon-2.svg" alt="">
                        </div>
                        <div class="wpo-work-text">
                            <span>{{ trans('messages.step_select_worker', [], session('locale')) }}</span>
                            <h2>03</h2>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end wpo-work-section -->

<!-- Start fanfuct -->
<section class="fanfuct-section">
    <div class="container">
        <div class="funfact-wrap">
            <div class="top-content">
                <div class="title">
                    <h3 class="splittext-line">
                        {{ trans('messages.customer_satisfaction_quote', [], session('locale')) }}
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="col col-lg-3 col-sm-6 col-12 wow fadeIn" data-wow-delay="0.4s">
                    <div class="item">
                        <h2><span class="odometer" data-count="25">00</span></h2>
                        <h3>{{ trans('messages.years_experience', [], session('locale')) }}</h3>
                    </div>
                </div>
                <div class="col col-lg-3 col-sm-6 col-12 wow fadeIn" data-wow-delay="0.6s">
                    <div class="item">
                        <h2><span class="odometer" data-count="75">00</span>k</h2>
                        <h3>{{ trans('messages.satisfied_clients', [], session('locale')) }}</h3>
                    </div>
                </div>
                <div class="col col-lg-3 col-sm-6 col-12 wow fadeIn" data-wow-delay="0.8s">
                    <div class="item">
                        <h2><span class="odometer" data-count="134">00</span></h2>
                        <h3>{{ trans('messages.team_members', [], session('locale')) }}</h3>
                    </div>
                </div>
                <div class="col col-lg-3 col-sm-6 col-12 wow fadeIn" data-wow-delay="0.8s">
                    <div class="item">
                        <h2><span class="odometer" data-count="85">00</span></h2>
                        <h3>{{ trans('messages.customer_retention', [], session('locale')) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<br> <br>


<!-- Start wpo-cta-section -->
<section class="wpo-cta-section section-padding pt-0">
    <div class="container">
        <div class="wpo-cta-wrap">
            <div class="row">
                <!-- Left box: Emergency Call -->
                <div class="col-lg-6 col-12">
                    <div class="wpo-cta-box wow fadeInUp" data-wow-duration="1200ms">
                        <div class="wpo-section-title-s2">
                            <span>
                                <i><img src="assets/images/cleaning-icon.svg" alt=""></i>
                                {{ trans('messages.emergency_call', [], session('locale')) }}
                            </span>
                            <h2 class="slide-sub-title wow">
                                {{ trans('messages.need_help_heading', [], session('locale')) }}
                            </h2>
                            <p>{{ trans('messages.need_help_desc', [], session('locale')) }}</p>
                        </div>
                        <a href="tel:+17189044450" class="call">
                            <i><img src="assets/images/phone-call.svg" alt=""></i>
                            +1 718-904-4450
                        </a>
                        <small>
                            {{ trans('messages.consult_advisor', [], session('locale')) }}
                            <a href="#">{{ trans('messages.click_now', [], session('locale')) }}</a>
                        </small>
                    </div>
                </div>

                <!-- Right box: Features -->
                <div class="col-lg-6">
                    <div class="wpo-cta-box features wow fadeInUp" data-wow-duration="1400ms">
                        <div class="wpo-section-title-s2">
                            <span>
                                <i><img src="assets/images/cleaning-icon.svg" alt=""></i>
                                {{ trans('messages.features', [], session('locale')) }}
                            </span>
                            <h2 class="slide-sub-title wow">
                                {{ trans('messages.features_heading', [], session('locale')) }}
                            </h2>
                        </div>
                        <ul>
                            <li>{{ trans('messages.feature_workers', [], session('locale')) }}</li>
                            <li>{{ trans('messages.feature_helpers', [], session('locale')) }}</li>
                            <li>{{ trans('messages.feature_drivers', [], session('locale')) }}</li>
                            <li>{{ trans('messages.feature_support', [], session('locale')) }}</li>
                        </ul>
                        <div class="r-shape">
                            <img src="assets/images/cleaning-logo-business-composition.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    const toggleLink = document.getElementById('toggleLink');
    const submitBtn = document.getElementById('submitBtn');
    const nameField = document.getElementById('nameField');
    const formHeader = document.getElementById('formHeader');
    let isSignUp = false;

    toggleLink.addEventListener('click', function(e) {
        e.preventDefault(); // prevent page reload
        isSignUp = !isSignUp;

        if (isSignUp) {
            nameField.style.display = 'block';
            submitBtn.textContent = "<?php echo trans('messages.sign_up', [], session('locale')); ?>";
            toggleLink.textContent = "<?php echo trans('messages.already_have_account', [], session('locale')); ?>";
            formHeader.textContent = "<?php echo trans('messages.register_to_manage_bookings', [], session('locale')); ?>";
        } else {
            nameField.style.display = 'none';
            submitBtn.textContent = "<?php echo trans('messages.sign_in', [], session('locale')); ?>";
            toggleLink.textContent = "<?php echo trans('messages.create_account', [], session('locale')); ?>";
            formHeader.textContent = "<?php echo trans('messages.sign_in_to_manage_bookings', [], session('locale')); ?>";
        }
    });
</script>



@include('layouts.web_footer')
@endsection