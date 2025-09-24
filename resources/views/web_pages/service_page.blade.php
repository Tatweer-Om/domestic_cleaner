             @extends('layouts.web_header')

@section('main')
    @push('title')
        <title> {{ trans('messages.services', [], session('locale')) }}</title>
    @endpush

             <div class="breadcumb-area box-style">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcumb-wrap">
                                            <h2>{{ trans('messages.clean_feels_better', [], session('locale')) }}</h2>
                                                                <h3>{{ trans('messages.services_page_title', [], session('locale')) }}</h3>
                                    </div>
            </div>
        </div>
    </div>
</div>        <!-- end page-title -->
        <!-- start wpo-service-section -->
        <section class="wpo-service-section style-3 section-padding">
            <div class="wpo-service-wrap">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-6">
                            <div class="wpo-section-title">
                                <h2 class="slide-title wow">{{ trans('messages.cleanliness_meets_care', [], session('locale')) }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="service-slider-s3">
                        <div class="wpo-service-slide-item">
                            <div class="row">

<div class="row" id="service_show"></div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="left-shape2">
                    <img src="assets/images/service/shape1.svg" alt="">
                </div>
                <div class="right-shape">
                    <img src="assets/images/service/shape3.svg" alt="">
                </div>
            </div>
        </section>
        <!-- end of wpo-service-section -->

        <!-- Start wpo-cta-section -->
       <section class="wpo-cta-section section-padding pt-0">
        <div class="container">
            <div class="wpo-cta-wrap">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="wpo-cta-box wow fadeInUp" data-wow-duration="1200ms">
                            <div class="wpo-section-title-s2">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>{{ trans('messages.emergency_call', [], session('locale')) }}</span>
                                <h2 class="slide-title wow">{{ trans('messages.need_help_heading', [], session('locale')) }}</h2>
                                <p>{{ trans('messages.need_help_desc', [], session('locale')) }}</p>
                            </div>
                            <a href="tel:+17189044450" class="call"><i><img src="assets/images/phone-call.svg"
                                        alt=""></i>+1
                                718-904-4450</a>
                            <small>{{ trans('messages.consult_advisor', [], session('locale')) }} <a href="#">{{ trans('messages.click_now', [], session('locale')) }}</a></small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="wpo-cta-box features wow fadeInUp" data-wow-duration="1400ms">
                            <div class="wpo-section-title-s2">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>{{ trans('messages.features', [], session('locale')) }}</span>
                                <h2 class="slide-title wow">{{ trans('messages.features_heading', [], session('locale')) }}</h2>
                            </div>
                            <ul>
                                <li>{{ trans('messages.feature_workers', [], session('locale')) }}</li>
                                <li>{{ trans('messages.feature_helpers', [], session('locale')) }}</li>
                                <li>{{ trans('messages.feature_drivers', [], session('locale')) }}</li>
                                <li>{{ trans('messages.feature_support', [], session('locale')) }}</li>
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
        {{-- <section class="wpo-faq-section section-padding pt-0">
            <div class="container">
                <div class="wpo-faq-wrap">
                    <div class="row">
                        <div class="col-lg-5 col-12">
                            <div class="wpo-faq-box wow fadeInLeftSlow" data-wow-duration="1200ms">
                                <div class="wpo-section-title-s2">
                                    <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>faq</span>
                                    <h2 class="slide-title wow">Freequently ask questions...</h2>
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
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                        aria-expanded="true" aria-controls="collapseOne">
                                                        What types of cleaning services do you offer?
                                                    </button>
                                                </h3>
                                                <div id="collapseOne" class="accordion-collapse collapse show"
                                                    aria-labelledby="headingOne"
                                                    data-bs-parent="#accordionExample">
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
                                                    aria-labelledby="headingTwo"
                                                    data-bs-parent="#accordionExample">
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
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseThree" aria-expanded="false"
                                                        aria-controls="collapseThree">
                                                        What cleaning products do you use?
                                                    </button>
                                                </h3>
                                                <div id="collapseThree" class="accordion-collapse collapse"
                                                    aria-labelledby="headingThree"
                                                    data-bs-parent="#accordionExample">
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
                                                    aria-labelledby="headingFour"
                                                    data-bs-parent="#accordionExample">
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
        </section> --}}
        <!-- end wpo-faq-section -->

        <!-- start of wpo-contact-section -->
        {{-- <section class="wpo-contact-section section-padding pt-0">
            <div class="wpo-contact-section-wrapper box-style">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="wpo-contact-img left-to-right-light wow fadeInLeftSlow"
                                data-wow-duration="1000ms">
                                <img src="assets/images/contact.jpg" alt="">
                                <div class="bottom-logos">
                                    <div class="bottom-logo-item">
                                        <div class="b-logo"><img src="assets/images/contact-logo1.png" alt="">
                                        </div>
                                        <div class="b-logo"><img src="assets/images/contact-logo2.png" alt="">
                                        </div>
                                    </div>
                                    <div class="left-shape">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 105 111" preserveAspectRatio="xMidYMid meet">
                                            <path
                                                d="M0 0 C0 34.98 0 69.96 0 106 C-34.65 106 -69.3 106 -105 106 C-105 103.69 -105 101.38 -105 99 C-103.828 98.974 -102.656 98.948 -101.449 98.922 C-87.737 98.486 -76.616 96.421 -66 87 C-56.318 76.465 -54.445 63.636 -54.438 49.812 C-54.381 34.753 -51.779 23.127 -41.375 11.75 C-34.571 5.274 -25.654 1.108 -16.27 0.684 C-15.385 0.642 -14.5 0.6 -13.588 0.557 C-12.672 0.517 -11.756 0.478 -10.812 0.438 C-9.885 0.394 -8.958 0.351 -8.002 0.307 C-1.348 0 -1.348 0 0 0 Z "
                                                fill="#ffffff" transform="translate(105,5)" />
                                        </svg>
                                    </div>
                                    <div class="right-shape">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 105 111" preserveAspectRatio="xMidYMid meet">
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
                                    <h2 class="slide-title wow">Reach Out for a Sparkling
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
                                            <input type="time" class="form-control" name="time" id="time" value="13:00">
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
        </section> --}}
    @include('layouts.web_footer')
@endsection
