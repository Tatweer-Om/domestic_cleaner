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
                                            <h2>{{ trans('messages.clean_feels_better', [], session('locale')) }}</h2>
                                                                <h3>{{ trans('messages.about_us_heading', [], session('locale')) }}</h3>
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
                                <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>{{ trans('messages.about_us_heading', [], session('locale')) }}</span>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="main-title">
                                <h2 class="text-opacity-animation">{{ trans('messages.we_believe_clean_space', [], session('locale')) }}</h2>
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
                                            <p>{{ trans('messages.clients_serviced', [], session('locale')) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="wow fadeInUp" data-wow-duration="1000ms">{{ trans('messages.about_company_description', [], session('locale')) }}</p>
                                <div class="wow fadeInUp" data-wow-duration="1200ms">
                                    <a href="appoinment.html" class="theme-btn">{{ trans('messages.book_now', [], session('locale')) }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="about-right-side">
                                <div class="about-right-img wow fadeInRightSlow" data-wow-duration="1000ms">
                                    <img src="assets/images/about/about-img-2.jpg" alt="">
                                    <div class="content-box wow fadeInLeftSlow" data-wow-duration="1000ms">
                                        <span>{{ trans('messages.office_cleaning', [], session('locale')) }}</span>
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
                                            <p>{{ trans('messages.team_member', [], session('locale')) }}</p>
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
                                    <span><i><img src="assets/images/cleaning-icon.svg" alt=""></i>{{ trans('messages.why_choose_us', [], session('locale')) }}</span>
                                    <h2 class="poort-text poort-in-right">{{ trans('messages.why_choose_heading', [], session('locale')) }}</h2>
                                    <p>{{ trans('messages.why_choose_desc', [], session('locale')) }}</p>
                                </div>
                                <ul>
                                    <li><span>{{ trans('messages.trusted_cleaners', [], session('locale')) }}</span></li>
                                    <li class="active"><span>{{ trans('messages.custom_plans', [], session('locale')) }}</span></li>
                                    <li><span>{{ trans('messages.affordable_pricing', [], session('locale')) }}</span></li>
                                    <li><span>{{ trans('messages.satisfaction_guarantee', [], session('locale')) }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @include('layouts.web_footer')
@endsection
