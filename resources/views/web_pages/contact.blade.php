
    @extends('layouts.web_header')

@section('main')
    @push('title')
        <title> {{ trans('messages.contact', [], session('locale')) }}</title>
    @endpush
                <div class="breadcumb-area box-style">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcumb-wrap">
                                            <h2>{{ trans('messages.clean_feels_better', [], session('locale')) }}</h2>
                                                                <h3>{{ trans('messages.contact_us_page_title', [], session('locale')) }}</h3>
                                    </div>
            </div>
        </div>
    </div>
</div>        <!-- end page-title -->
        <!--start of contact-page -->
        <section class="contact-page section-padding">
            <div class="container">
                <div class="office-info">
                    <div class="row">
                        <div class="col col-lg-4 col-md-6 col-12">
                            <div class="office-info-item">
                                <div class="office-info-icon">
                                    <div class="icon">
                                        <i class="fi flaticon-home-address"></i>
                                    </div>
                                </div>
                                <div class="office-info-text">
                                    <h2>{{ trans('messages.contact_address_title', [], session('locale')) }}</h2>
                                    <p>{{ trans('messages.footer_address_line1', [], session('locale')) }}
                                        <br> {{ trans('messages.footer_address_line2', [], session('locale')) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col col-lg-4 col-md-6 col-12">
                            <div class="office-info-item active">
                                <div class="office-info-icon">
                                    <div class="icon">
                                        <i class="fi flaticon-phone-call"></i>
                                    </div>
                                </div>
                                <div class="office-info-text">
                                    <h2>{{ trans('messages.contact_phone_title', [], session('locale')) }}</h2>
                                    <p>+96872537389 <br>
                                        {{ trans('messages.contact_phone_available', [], session('locale')) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col col-lg-4 col-md-6 col-12">
                            <div class="office-info-item">
                                <div class="office-info-icon">
                                    <div class="icon">
                                        <i class="fi flaticon-mail-1"></i>
                                    </div>
                                </div>
                                <div class="office-info-text">
                                    <h2>{{ trans('messages.contact_email_title', [], session('locale')) }}</h2>
                                    <p>contact@cleanar.com <br> info@cleanar.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-wrap">
                    <div class="row">
                        <div class="col-12">
                            <div class="contact-content text-center mb-5">
                                <h2 class="mb-4">{{ trans('messages.contact_get_in_touch', [], session('locale')) }}</h2>
                                <p class="mb-0">{{ trans('messages.contact_description', [], session('locale')) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map Section -->
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="map-wrapper">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d116834.00977329636!2d58.38569!3d23.58589!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e91ff99c3c1c7a3%3A0x3962d370c718b9f1!2sMuscat%2C%20Oman!5e0!3m2!1sen!2s!4v1647528325671"
                                    width="100%" height="450" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



            @include('layouts.web_footer')
@endsection
