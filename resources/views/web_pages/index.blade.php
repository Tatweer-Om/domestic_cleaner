@extends('layouts.web_header')

@section('main')
    @push('title')
        <title> {{ trans('messages.accounts_lang', [], session('locale')) }}</title>
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
  padding-left: 2.25rem; /* space for the small magnifier circle */
}
.location-picker-wrap .bootstrap-select .dropdown-menu {
  border-radius: .75rem;
  overflow: hidden;
}
.location-picker-wrap .bootstrap-select .dropdown-menu .dropdown-item {
  padding: .45rem .75rem;
}

        .wpo-service-slide-item{
            width: 390px !important;
        }

        /* Keep all slides on one line and equal height */
#workers_slider .slick-track { display: flex !important; }
#workers_slider .slick-slide { height: auto; }

/* Provide consistent side gutters on slides */
#workers_slider .wpo-service-slide-item {
  padding: 0 10px;
  box-sizing: border-box;
}

/* Make the card stretch to fill the slide height */
#workers_slider .wpo-service-item { height: 100%; display: flex; flex-direction: column; }
#workers_slider .wpo-service-img { flex: 0 0 auto; }
#workers_slider .wpo-service-text { margin-top: 10px; flex: 1 1 auto; display:flex; flex-direction:column; }

/* Avoid any global .section-padding on each slide (causes width overflow) */


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


        <div class="container">
            <div class="wraper">
                <div class="row align-items-center">
                    <!-- Left: hero content + form -->
                    <div class="col-lg-7 col-12">
                        <div class="slide-title wow fadeInUp" data-wow-delay="0.0s">
                            <span>
                                <img src="{{ asset('assets/images/cleaning-icon.svg') }}" alt="">Because Clean Feels
                                Better
                            </span>
                        </div>

                        <div class="slide-sub-title wow fadeInUp" data-wow-delay="0.3s">
                            <h2>Glowing Residential Commercial Cleaning</h2>
                        </div>

                        <!-- Form Card -->
                        <div class="hero-form-wrap wow fadeInUp" data-wow-delay="0.75s">
                            <div class="auth-card" id="authCard">
                                <form id="authForm">

                                    <!-- Dynamic Header -->
                                    <h5 id="formHeader" class="text-center fw-bold mb-3">
                                        SIGN IN TO MANAGE BOOKINGS
                                    </h5>

                                    <!-- Name field (only for Sign Up) -->
                                    <div id="nameField" style="display:none;">
                                        <div class="mb-2">
                                            <label class="form-label mb-1 small">Mobile Number</label>
                                            <input type="number" class="form-control" placeholder="Enter your phone"
                                                name="phone" id="phone">
                                        </div>
                                    </div>

                                    <!-- Username -->
                                    <div class="mb-2">
                                        <label class="form-label mb-1 small">User Name</label>
                                        <input type="text" class="form-control user_name" id="user_name" name="user_name"
                                            placeholder="Enter username">
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-2">
                                        <label class="form-label mb-1 small">Password</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="********">
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" id="submitBtn" class="btn btn-success w-100 mt-3">Sign
                                        In</button>

                                    <!-- Divider -->
                                    <div class="auth-divider">or</div>

                                    <!-- Toggle Link -->
                                    <p class="text-center mb-0">
                                        <a href="#" id="toggleLink" class="text-success fw-semibold">Create an
                                            account</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right: empty / image space -->
                    <div class="col-lg-5 col-12"></div>
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


    <!-- Start partners -->
    <section class="partners-section section-padding">
        <h2>Featured by popular companies in the industry</h2>
        <div class="container">
            <ul class="partners-slider">
                <li>
                    <div>
                        <img src="{{ asset('assets/images/partners/1.png') }}" alt="">
                    </div>
                </li>
                <li>
                    <div>
                        <img src="{{ asset('assets/images/partners/2.png') }}" alt="">
                    </div>
                </li>
                <li>
                    <div>
                        <img src="{{ asset('assets/images/partners/3.png') }}" alt="">
                    </div>
                </li>
                <li>
                    <div>
                        <img src="{{ asset('assets/images/partners/4.png') }}" alt="">
                    </div>
                </li>
                <li>
                    <div>
                        <img src="{{ asset('assets/images/partners/5.png') }}" alt="">
                    </div>
                </li>
                <li>
                    <div>
                        <img src="{{ asset('assets/images/partners/2.png') }}" alt="">
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <!-- end partners -->

    <!-- start about -->
    <section class="about-section">
        <div class="container">
            <div class="about-title">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="sub-title wow fadeInLeftSlow" data-wow-duration="1000ms">
                            <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>about
                                Us</span>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="main-title">
                            <h2 class="text-opacity-animation">we believe that a clean space is
                                happy space. Founded in 1998, our
                                mission is to make homes &
                                businesses sparkle while...</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="about-wrap">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-left-side-wrap">
                            <div class="about-left-side">
                                <div class="about-left-img wow fadeInLeftSlow" data-wow-duration="1000ms">
                                    <img src="assets/images/about/about-img-1.jpg" alt="">
                                </div>
                                <div class="about-left-client-box wow fadeInRightSlow" data-wow-duration="1000ms">
                                    <div class="wrap">
                                        <h2><span class="odometer" data-count="40">00</span>K+</h2>
                                        <p>Client’s serviced</p>
                                    </div>
                                </div>
                            </div>
                            <p class="wow fadeInUp" data-wow-duration="1000ms">At Shiny Clean, we believe a
                                clean
                                space is a happy space.
                                With years of experience in residential and commercial cleaning
                                our mission is to deliver top-quality services tha health...</p>
                            <div class="wow fadeInUp" data-wow-duration="1200ms">
                                <a href="appoinment.html" class="theme-btn">Book Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-right-side">
                            <div class="about-right-img wow fadeInRightSlow" data-wow-duration="1000ms">
                                <img src="assets/images/about/about-img-2.jpg" alt="">
                                <div class="content-box wow fadeInLeftSlow" data-wow-duration="1000ms">
                                    <span>Office
                                        Cleaning</span>
                                </div>
                            </div>
                            <div class="leaf-shape wow zoomIn" data-wow-duration="1000ms">
                                <img src="assets/images/about/leaf.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape-img new_img-animet" data-speed="100">
            <img src="assets/images/about/ab-shape.png" alt="">
        </div>
    </section>
    <!-- end about -->

    <div id="servicesContainer"></div>
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
                                <img src="assets/images/choose/choose.jpg" alt="">
                            </div>
                            <div class="wpo-choose-left-box">
                                <div class="wrap">
                                    <div class="inside">
                                        <h2><span class="odometer" data-count="50">00</span>+</h2>
                                        <p>Team Member</p>
                                    </div>
                                </div>
                                <div class="radius-shape"><img src="assets/images/choose/radius-shape.svg"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="wpo-choose-right-side wow fadeInRightSlow" data-wow-duration="1000ms">
                            <div class="wpo-section-title-s2">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>why
                                    choose
                                    us</span>
                                <h2 class="poort-text poort-in-right">Your Space Deserves the Best Here’s
                                    Why We’re
                                    It</h2>
                                <p>Our team of trained professionals takes pride in every detail, going
                                    above
                                    and beyond to exceed your expectations. Whether it’s routine
                                    housekeeping.</p>
                            </div>
                            <ul>
                                <li><span>Trusted & Vetted Cleaners</span></li>
                                <li class="active"><span>Customizable Cleaning Plans</span></li>
                                <li><span>Affordable & Transparent Pricing</span></li>
                                <li><span>Satisfaction Guarantee</span></li>
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
                        <span><i><img src="assets/images/cleaning-icon-white.svg" alt=""></i>How It
                            Works</span>
                        <h2 class="poort-text poort-in-right">we’re prooffesionaly Commited
                            to give best Cleaning services
                            see how it works actually</h2>
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
                                <span>Book online</span>
                                <h2>01</h2>
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
                                <span>get service</span>
                                <h2>02</h2>
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
                                <span>Enjoy service</span>
                                <h2>03</h2>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="left-images">
            <div class="image-1 wow fadeInDown" data-wow-duration="1200ms">
                <div class="image-move">
                    <img src="assets/images/work/image-1.jpg" alt="">
                </div>
            </div>
            <div class="image-2 wow fadeInLeft" data-wow-duration="1200ms">
                <div class="image-move">
                    <img src="assets/images/work/image-2.jpg" alt="">
                </div>
            </div>
            <div class="image-3 wow fadeInUp" data-wow-duration="1200ms">
                <div class="image-move">
                    <img src="assets/images/work/image-3.jpg" alt="">
                </div>
            </div>
        </div>
        <div class="right-images">
            <div class="image-1 wow fadeInDown" data-wow-duration="1200ms">
                <div class="image-move2">
                    <img src="assets/images/work/image-4.jpg" alt="">
                </div>
            </div>
            <div class="image-2 wow fadeInRight" data-wow-duration="1200ms">
                <div class="image-move2">
                    <img src="assets/images/work/image-5.jpg" alt="">
                </div>
            </div>
            <div class="image-3 wow fadeInUp" data-wow-duration="1200ms">
                <div class="image-move2">
                    <img src="assets/images/work/image-6.jpg" alt="">
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
                        <h3 class="splittext-line">“Customer satisfaction is at the heart of everything we
                            do.”</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-lg-3 col-sm-6 col-12  wow fadeIn" data-wow-delay="0.4s">
                        <div class="item">
                            <h2><span class="odometer" data-count="25">00</span></h2>
                            <h3>Years of Experience</h3>
                        </div>
                    </div>
                    <div class="col col-lg-3 col-sm-6 col-12  wow fadeIn" data-wow-delay="0.6s">
                        <div class="item">
                            <h2><span class="odometer" data-count="75">00</span>k</h2>
                            <h3>Satisfied Clients</h3>
                        </div>
                    </div>
                    <div class="col col-lg-3 col-sm-6 col-12  wow fadeIn" data-wow-delay="0.8s">
                        <div class="item">
                            <h2><span class="odometer" data-count="134">00</span></h2>
                            <h3>Team Members</h3>
                        </div>
                    </div>
                    <div class="col col-lg-3 col-sm-6 col-12  wow fadeIn" data-wow-delay="0.8s">
                        <div class="item">
                            <h2><span class="odometer" data-count="85">00</span></h2>
                            <h3>Customer Retention Rate</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end fanfuct-section -->
    <!-- Start transforming -->
    <section class="transforming-section section-padding">
        <div class="container">
            <div class="transforming-wrap">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="wpo-transforming-left-side wow fadeInLeftSlow" data-wow-duration="1000ms">
                            <div class="wpo-transforming-left-img">
                                <div class="transforming-image-container">
                                    <img class="transforming-image-before slider-image"
                                        src="assets/images/before-after/after-img.jpg" alt="after">
                                    <img class="transforming-image-after slider-image"
                                        src="assets/images/before-after/before-img.jpg" alt="before">
                                </div>
                                <!-- step="10" -->
                                <input type="range" min="0" max="100" value="50"
                                    aria-label="Percentage of before photo shown" class="slider">
                                <div class="transforming-slider-line" aria-hidden="true"></div>
                                <div class="transforming-slider-button" aria-hidden="true">
                                    Drag
                                </div>
                            </div>
                            <div class="after"><span>after</span></div>
                            <div class="before"><span>before</span></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="wpo-transforming-right-side wow fadeInRightSlow" data-wow-duration="1000ms">
                            <div class="wpo-section-title-s2">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>before
                                    &
                                    after</span>
                                <h2 class="poort-text poort-in-right">Transforming Spaces, One Clean at a
                                    Time</h2>
                                <p>Let us take the stress out of cleaning, so you can focus on what matters
                                    most.</p>
                            </div>
                            <ul>
                                <li><span> Deep & Detailed Cleaning</span></li>
                                <li class="active"><span>Eco-Friendly Products</span></li>
                                <li><span>Flexible Scheduling</span></li>
                            </ul>
                            <div class="transforming-btns">
                                <a href="contact.html" class="theme-btn-s2">Try yours now</a>
                                <a href="about.html" class="theme-btn-s3"><span class="rolling-text"
                                        data-text="About us">Learn More</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end transforming -->

    <!-- Start wpo-cta-section -->
    <section class="wpo-cta-section section-padding pt-0">
        <div class="container">
            <div class="wpo-cta-wrap">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="wpo-cta-box wow fadeInUp" data-wow-duration="1200ms">
                            <div class="wpo-section-title-s2">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>emergency
                                    call</span>
                                <h2 class="poort-text poort-in-right">Need Help Fast? We’re Just One Call
                                    Away</h2>
                                <p>In today's competitive business, the demand for efficient
                                    IT solutions has never been more critical.</p>
                            </div>
                            <a href="tel:+17189044450" class="call"><i><img src="assets/images/phone-call.svg"
                                        alt=""></i>+1
                                718-904-4450</a>
                            <small>Consult With It Advisor? <a href="#">Click Now</a></small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="wpo-cta-box features wow fadeInUp" data-wow-duration="1400ms">
                            <div class="wpo-section-title-s2">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>features</span>
                                <h2 class="poort-text poort-in-right">Your Space Deserves the Best Here’s
                                    Why We’re It</h2>
                            </div>
                            <ul>
                                <li>30 Experienced Loaders</li>
                                <li>45 Trained Warehouse Expert</li>
                                <li>120 Expert Truck Drivers</li>
                                <li>345 Delivery Personnel</li>
                            </ul>
                            <div class="r-shape"><img src="assets/images/cleaning-logo-business-composition.png"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end wpo-cta-section -->

    <!-- Start wpo-faq-section -->
    <section class="wpo-faq-section section-padding pt-0">
        <div class="container">
            <div class="wpo-faq-wrap">
                <div class="row">
                    <div class="col-lg-5 col-12">
                        <div class="wpo-faq-box wow fadeInLeftSlow" data-wow-duration="1200ms">
                            <div class="wpo-section-title-s2">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>faq</span>
                                <h2 class="poort-text poort-in-right">Freequently ask questions...</h2>
                                <p>communication and utilizes cutting edge logistic planning
                                    to get your shipment completed on time. itself founded.</p>
                            </div>
                            <a href="appoinment.html" class="theme-btn-s2">Book Now</a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-12">
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="wpo-faq-items wow fadeInRightSlow" data-wow-duration="1200ms">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h3 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    What types of cleaning services do you offer?
                                                </button>
                                            </h3>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <p>GoDaddy offers more than just a platform to build
                                                        your website, we offer everything you need to create
                                                        an effective, memorable online presence. Already
                                                        have a site? We offer hosting plans that will keep
                                                        it fast, secure and online. Our professional</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h3 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    Do I need to be home during the cleaning?
                                                </button>
                                            </h3>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <p>GoDaddy offers more than just a platform to build
                                                        your website, we offer everything you need to create
                                                        an effective, memorable online presence. Already
                                                        have a site? We offer hosting plans that will keep
                                                        it fast, secure and online. Our professional</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h3 class="accordion-header" id="headingThree">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                    What cleaning products do you use?
                                                </button>
                                            </h3>
                                            <div id="collapseThree" class="accordion-collapse collapse"
                                                aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <p>GoDaddy offers more than just a platform to build
                                                        your website, we offer everything you need to create
                                                        an effective, memorable online presence. Already
                                                        have a site? We offer hosting plans that will keep
                                                        it fast, secure and online. Our professional</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h3 class="accordion-header" id="headingFour">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                    aria-expanded="false" aria-controls="collapseFour">
                                                    How do I book a cleaning appointment?
                                                </button>
                                            </h3>
                                            <div id="collapseFour" class="accordion-collapse collapse"
                                                aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <p>GoDaddy offers more than just a platform to build
                                                        your website, we offer everything you need to create
                                                        an effective, memorable online presence. Already
                                                        have a site? We offer hosting plans that will keep
                                                        it fast, secure and online. Our professional</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end wpo-faq-section -->

    <!-- start wpo-testimonials-section -->
    <section class="wpo-testimonials-section section-padding pt-0">
        <div class="wpo-testimonial-wrap section-padding box-style">
            <div class="top-shape">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 595 213"
                    preserveAspectRatio="xMidYMid meet">
                    <path
                        d="M0 0 C196.35 0 392.7 0 595 0 C595 1.98 595 3.96 595 6 C593.704 6.086 592.409 6.173 591.074 6.262 C549.766 9.372 517.231 29.459 490.32 60.432 C487.205 64.131 484.301 67.994 481.387 71.852 C471.791 84.51 461.32 96.901 449 107 C448.145 107.743 447.291 108.485 446.41 109.25 C413.41 137.599 372.873 155.357 330 162 C329.197 162.128 328.394 162.257 327.566 162.389 C295.278 167.322 262.27 163.707 231 155 C229.663 154.629 229.663 154.629 228.3 154.251 C181.455 140.866 139.591 109.828 110.381 71.217 C105.401 64.655 100.158 58.485 94.464 52.531 C93.473 51.494 92.486 50.452 91.505 49.406 C66.143 22.44 36.537 9.521 0 6 C0 4.02 0 2.04 0 0 Z "
                        fill="#ffffff" transform="translate(0,0)" />
                </svg>
            </div>
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6">
                        <div class="wpo-section-title">
                            <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>Testimonial</span>
                            <h2 class="poort-text poort-in-right">Client Feedback ThatSpeaks Volumes</h2>
                        </div>
                    </div>
                </div>
                <div class="wpo-testimonial-active owl-carousel">
                    <div class="wpo-testimonial-item">
                        <div class="t-logo">
                            <img src="assets/images/testimonial/t-logo.png" alt="">
                        </div>
                        <p>“Cleaning hires great people from a widely variety
                            of backgrounds, which simply makes our compan
                            stronger, and we couldn’t be prouder of that.
                            elevating your optimizing Business Growth.”</p>
                        <div class="wpo-testimonial-info">
                            <div class="wpo-testimonial-info-img">
                                <img src="assets/images/testimonial/image-1.jpg" alt="">
                            </div>
                            <div class="wpo-testimonial-info-text">
                                <h5>Aliza Anderson</h5>
                                <span>CEO & Founder </span>
                            </div>
                            <div class="rating"><img src="assets/images/testimonial/rating.svg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="wpo-testimonial-item">
                        <div class="t-logo">
                            <img src="assets/images/testimonial/t-logo.png" alt="">
                        </div>
                        <p>“Cleaning brings together talented people from diverse paths, making our company
                            better every day, and we take pride in that. Empowering your journey to Business
                            Success”</p>
                        <div class="wpo-testimonial-info">
                            <div class="wpo-testimonial-info-img">
                                <img src="assets/images/testimonial/image-2.jpg" alt="">
                            </div>
                            <div class="wpo-testimonial-info-text">
                                <h5>Sara Williamson</h5>
                                <span>Team Leader</span>
                            </div>
                            <div class="rating"><img src="assets/images/testimonial/rating.svg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end container -->
        </div>
    </section>
    <!-- end testimonials-section -->

    <!-- start of wpo-contact-section -->
    <section class="wpo-contact-section section-padding pt-0">
        <div class="wpo-contact-section-wrapper box-style">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="wpo-contact-img left-to-right-light wow fadeInLeftSlow" data-wow-duration="1000ms">
                            <img src="assets/images/contact.jpg" alt="">
                            <div class="bottom-logos">
                                <div class="bottom-logo-item">
                                    <div class="b-logo"><img src="assets/images/contact-logo1.png" alt="">
                                    </div>
                                    <div class="b-logo"><img src="assets/images/contact-logo2.png" alt="">
                                    </div>
                                </div>
                                <div class="left-shape">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 105 111"
                                        preserveAspectRatio="xMidYMid meet">
                                        <path
                                            d="M0 0 C0 34.98 0 69.96 0 106 C-34.65 106 -69.3 106 -105 106 C-105 103.69 -105 101.38 -105 99 C-103.828 98.974 -102.656 98.948 -101.449 98.922 C-87.737 98.486 -76.616 96.421 -66 87 C-56.318 76.465 -54.445 63.636 -54.438 49.812 C-54.381 34.753 -51.779 23.127 -41.375 11.75 C-34.571 5.274 -25.654 1.108 -16.27 0.684 C-15.385 0.642 -14.5 0.6 -13.588 0.557 C-12.672 0.517 -11.756 0.478 -10.812 0.438 C-9.885 0.394 -8.958 0.351 -8.002 0.307 C-1.348 0 -1.348 0 0 0 Z "
                                            fill="#ffffff" transform="translate(105,5)" />
                                    </svg>
                                </div>
                                <div class="right-shape">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 105 111"
                                        preserveAspectRatio="xMidYMid meet">
                                        <path
                                            d="M0 0 C31.067 1.479 31.067 1.479 44.188 15.188 C50.968 23.075 54.067 31.852 54.176 42.156 C54.212 43.939 54.212 43.939 54.248 45.758 C54.29 48.221 54.322 50.685 54.342 53.148 C54.634 65.779 57.252 77.481 66 87 C77.667 97.353 90.069 98.671 105 99 C105 101.31 105 103.62 105 106 C70.35 106 35.7 106 0 106 C0 71.02 0 36.04 0 0 Z "
                                            fill="#ffffff" transform="translate(0,5)" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="wpo-contact-form-area wow fadeInRightSlow" data-wow-duration="1000ms">
                            <div class="wpo-section-title">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>get in
                                    touch</span>
                                <h2 class="poort-text poort-in-right">Reach Out for a Sparkling
                                    Space today</h2>
                            </div>
                            <form method="post" class="contact-validation-active" id="contact-form">
                                <div class="row">
                                    <div class="col col-lg-6 col-12">
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="Your Name*">
                                    </div>
                                    <div class="col col-lg-6 col-12">
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Your Email*">
                                    </div>
                                    <div class="col col-lg-12 col-12">
                                        <select name="subject" class="form-control">
                                            <option disabled="disabled" selected>Service catagories</option>
                                            <option>Office</option>
                                            <option>Home</option>
                                            <option>Shop</option>
                                            <option>Road</option>
                                            <option>car</option>
                                        </select>
                                    </div>
                                    <div class="col col-lg-6 col-12">
                                        <input type="date" class="form-control" name="date" id="date">
                                    </div>
                                    <div class="col col-lg-6 col-12">
                                        <input type="time" class="form-control" name="time" id="time"
                                            value="13:00">
                                    </div>
                                    <div class="col col-lg-12 col-12">
                                        <div class="submit-area">
                                            <button type="submit" class="theme-btn-s2">Book Now</button>
                                            <div id="loader">
                                                <i class="ti-reload"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix error-handling-messages">
                                    <div id="success">Thank you</div>
                                    <div id="error"> Error occurred while sending email. Please try again
                                        later.
                                    </div>
                                </div>
                            </form>
                            <div class="border-style"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end of wpo-contact-section -->

    <!-- start wpo-booking-section -->
    <section class="wpo-booking-section section-padding pt-0">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-8">
                    <div class="wpo-section-title">
                        <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>Book an
                            appointment</span>
                        <h2 class="poort-text poort-in-right">we’re prooffesionaly Commited
                            to give best Cleaning services
                            for Client’s happiness</h2>
                        <div class="booking-btn wow zoomIn" data-wow-duration="1000ms"><a class="btn-wrapper btn-move"
                                href="appoinment.html"><small><i><img src="assets/images/arrow-up.svg"
                                            alt=""></i> BOOK
                                    APPOINTMENT</small></a></div>
                    </div>
                </div>
            </div>
            <div class="wpo-booking-wrap">
                <div class="wpo-booking-item wow fadeInLeftSlow" data-wow-duration="1000ms">
                    <img src="assets/images/booking/img-1.jpg" alt="">
                </div>
                <div class="wpo-booking-item wow fadeInUp" data-wow-duration="1200ms">
                    <img src="assets/images/booking/img-2.jpg" alt="">
                </div>
                <div class="wpo-booking-item">
                    <div class="img-1 wow fadeInLeftSlow" data-wow-duration="1000ms"><img
                            src="assets/images/booking/img-3.jpg" alt=""></div>
                    <div class="img-2 wow fadeInRightSlow" data-wow-duration="1000ms"><img
                            src="assets/images/booking/img-4.jpg" alt=""></div>
                </div>
                <div class="wpo-booking-item wow fadeInRightSlow" data-wow-duration="1000ms">
                    <img src="assets/images/booking/img-5.jpg" alt="">
                </div>
            </div>
        </div> <!-- end container -->
        <div class="shape-img new_img-animet" data-speed="100">
            <img src="assets/images/booking/shape.png" alt="">
        </div>
    </section>
    <!-- end booking-section -->


    <div id="workerListContainer"></div>
    <!-- end wpo-blog-s
                        ection -->
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
                submitBtn.textContent = 'Sign Up';
                toggleLink.textContent = 'Already have an account? Sign In';
                formHeader.textContent = 'REGISTER TO MANAGE BOOKINGS';
            } else {
                nameField.style.display = 'none';
                submitBtn.textContent = 'Sign In';
                toggleLink.textContent = 'Create an account';
                formHeader.textContent = 'SIGN IN TO MANAGE BOOKINGS';
            }
        });
    </script>


    @include('layouts.web_footer')
@endsection
