       <footer class="footer-common footer-section-s1">
    <div class="footer-wrap section-padding">
        <div class="footer-topbar">
            {{-- <div class="container">
                <div class="wraper">
                    <h2 class="scroll-text-animation">
                        <span>New Creative Ideas</span> <br> send me an e-mail – <span class="color">
                             info@example.com</span>
                    </h2>
                    <div class="booking-btn wow zoomIn " data-wow-duration="1000ms"><a
                            class="btn-wrapper btn-move" href="appoinment.html"><small><i><img
                                        src="assets/images/arrow-up-black.svg" alt=""></i>Get in
                                tocuh</small></a></div>
                </div>
            </div> --}}
        </div>
        <div class="container">
            <div class="footer">
                <div class="item widget-newsletter fade_bottom">
                    <h2 class="title">{{ trans('messages.footer_contact_us', [], session('locale')) }}</h2>
                    <div class="newsletter">
                        <form class="form-fild">
                            <input class="fild" type="email" placeholder="{{ trans('messages.footer_newsletter_placeholder', [], session('locale')) }}">
                            <button type="submit">
                                <img src="assets/images/air-plane.svg" alt="">
                            </button>
                            <div class="terms">
                                <input type="checkbox" id="checkbox" class="checkbox-input">
                                <label for="checkbox" class="checkbox-label">
                                    <span class="custom-checkbox"></span>{{ trans('messages.footer_terms_agreement', [], session('locale')) }}</label>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="item fade_bottom">
                    <h2 class="title">{{ trans('messages.footer_quick_links', [], session('locale')) }}</h2>
                    <ul>
                        <li><a href="about.html">{{ trans('messages.footer_about_company', [], session('locale')) }}</a></li>
                        <li><a href="appoinment.html">{{ trans('messages.footer_appointment', [], session('locale')) }}</a></li>
                        <li><a href="service.html">{{ trans('messages.footer_our_services', [], session('locale')) }}</a></li>
                        <li><a href="blog.html">{{ trans('messages.footer_our_blogs', [], session('locale')) }}</a></li>
                        <li><a href="contact.html">{{ trans('messages.footer_contact_us', [], session('locale')) }}</a></li>
                    </ul>
                </div>
                <div class="item fade_bottom">
                    <h2 class="title">{{ trans('messages.footer_contact_info', [], session('locale')) }}</h2>
                    <ul>
                        <li>{{ trans('messages.footer_location_country', [], session('locale')) }}</li>
                        <li>{{ trans('messages.footer_address_line1', [], session('locale')) }}</li>
                        <li>{{ trans('messages.footer_address_line2', [], session('locale')) }}</li>
                        <li>contact@cleanar.com</li>
                        <li>+96872537389</li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="footer-lower">
            <div class="container">
                {{-- <div class="lower-footer-wrap">
                    <div class="row align-items-center g-0">
                        <div class="col-lg-5 col-12">
                            <p class="copyright">Copyright &copy; <span>2025</span> Wpocean by
                                All rights reserved.</p>
                        </div>
                        <div class="col-lg-3 col-12 text-center">
                            <p>Saturday - Thursday</p>
                        </div>
                        <div class="col-lg-4 col-12">
                            <ul class="widget-social">
                                <li><a href="#"><i class="ti-facebook"></i></a></li>
                                <li><a href="#"><i class="ti-twitter-alt"></i></a></li>
                                <li><a href="#"><i class="ti-instagram"></i></a></li>
                                <li><a href="#"><i class="ti-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="f-shape">
                <img src="assets/images/footer-shape.png" alt="">
            </div>
        </div>
    </div>
</footer>        <!-- end wpo-site-footer -->

    </div>
    <!-- end of page-wrapper -->

    <!-- All JavaScript files
================================================== -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- Plugins for this template -->
<script src="{{ asset('assets/js/modernizr.custom.js') }}"></script>
<script src="{{ asset('assets/js/jquery-plugin-collection.js') }}"></script>
<!-- Custom script for this template -->
<script src="{{ asset('assets/js/gsap-script.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('vendor/toastr/js/toastr.min.js')}}"></script>
    <script src="{{ asset('js/plugins-init/toastr-init.js')}}"></script>
    <script src="https://test.amwalpg.com:7443/js/SmartBox.js?v=1.1"></script>

<script src="https://unpkg.com/@mapbox/leaflet-pip@latest/leaflet-pip.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://unpkg.com/leaflet-control-search/dist/leaflet-search.min.js"></script>
<script src="https://unpkg.com/leaflet-geosearch@3.8.0/dist/geosearch.umd.js"></script>
<script src="https://unpkg.com/@mapbox/leaflet-pip@1.1.0/leaflet-pip.js"></script>


@include('custom_js.custom_js')
@php

    $routeName = Route::currentRouteName();
    $segments = explode('.', $routeName);
    $route_name = isset($segments[0]) ? $segments[0] : null;

@endphp

    @if ($route_name == 'index')
         @include('custom_js.web_js')
         @elseif ($route_name == 'worker_profile')
         @include('custom_js.worker_profile_js')
         @elseif ($route_name == 'user_profile')
         @include('custom_js.user_profile_js')
          @elseif ($route_name == 'service_page')
         @include('custom_js.web_js')
          @elseif ($route_name == 'checkout')
         @include('custom_js.booking_js')


    @endif

   <script>
  const IS_RTL = window.APP_IS_RTL === true;

  // Owl Carousel
  if (window.jQuery && $.fn.owlCarousel) {
    $('.owl-carousel').each(function(){
      $(this).owlCarousel(Object.assign({ rtl: IS_RTL }, $(this).data('owl-options') || {}));
    });
  }

  // Slick
  if (window.jQuery && $.fn.slick) {
    $('.slick-slider').each(function(){
      $(this).slick(Object.assign({ rtl: IS_RTL }, $(this).data('slick-options') || {}));
    });
  }

  // Swiper (auto-detects dir, usually no flag needed)
  // Toastr
  if (window.toastr) {
    toastr.options = Object.assign({
      rtl: IS_RTL,
      positionClass: IS_RTL ? 'toast-top-left' : 'toast-top-right'
    }, toastr.options || {});
  }
</script>
</body>



</html>
