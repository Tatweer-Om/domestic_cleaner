       <footer class="footer-common footer-section-s1">
    <div class="footer-wrap section-padding">
        <div class="footer-topbar">
            <div class="container">
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
            </div>
        </div>
        <div class="container">
            <div class="footer">
                <div class="item widget-newsletter fade_bottom">
                    <h2 class="title">Newslatter</h2>
                    <div class="newsletter">
                        <form class="form-fild">
                            <input class="fild" type="email" placeholder="Get News & Updates">
                            <button type="submit">
                                <img src="assets/images/air-plane.svg" alt="">
                            </button>
                            <div class="terms">
                                <input type="checkbox" id="checkbox" class="checkbox-input">
                                <label for="checkbox" class="checkbox-label">
                                    <span class="custom-checkbox"></span>I agree to all your terms
                                    and policies</label>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="item fade_bottom">
                    <h2 class="title">Quick Link</h2>
                    <ul>
                        <li><a href="about.html">About Company</a></li>
                        <li><a href="appoinment.html">Appoinment</a></li>
                        <li><a href="service.html">Our Services</a></li>
                        <li><a href="blog.html">Our Blogs</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                    </ul>
                </div>
                <div class="item fade_bottom">
                    <h2 class="title">Contact info</h2>
                    <ul>
                        <li>Germany —</li>
                        <li>785 15h Street,</li>
                        <li>Office 478 Berlin, De 81566</li>
                        <li>contact@cleanar.com</li>
                        <li>+1300 877 503</li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="footer-lower">
            <div class="container">
                <div class="lower-footer-wrap">
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
                </div>
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


    @endif
</body>


</html>
