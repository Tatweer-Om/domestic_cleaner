         @extends('layouts.web_header')

@section('main')
    @push('title')
        <title> {{ trans('messages.about', [], session('locale')) }}</title>
    @endpush

              <div class="breadcumb-area box-style">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcumb-wrap">
                                            <h2>Because Clean Feels Better</h2>
                                                                <h3>About us</h3>
                                    </div>
            </div>
        </div>
    </div>
</div>

<!-- end page-title -->
        <!-- start about -->
        <section class="about-section section-padding">
            <div class="container">
                <div class="about-title">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="sub-title wow fadeInLeftSlow" data-wow-duration="1000ms">
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>about Us</span>
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
                                    <div class="about-left-client-box wow fadeInRightSlow"
                                        data-wow-duration="1000ms">
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
                                    <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>why choose
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
    @include('layouts.web_footer')
@endsection
