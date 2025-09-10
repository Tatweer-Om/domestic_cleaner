
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
                                            <h2>Because Clean Feels Better</h2>
                                                                <h3>Contact Us</h3>
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
                                    <h2>address line</h2>
                                    <p>Bowery St, New York, 37 USA
                                        <br> NY 10013,USA
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
                                    <h2>Phone Number</h2>
                                    <p>+1255 - 568 - 6523 4374-221 <br>
                                        +1255 - 568 - 6523</p>
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
                                    <h2>Address</h2>
                                    <p>contact@cleanar.com <br> info@cleanar.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-wrap">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="contact-left">
                                <h2>Get in touch</h2>
                                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit mattis
                                    faucibus odio feugiat arc dolor.</p>
                                <div class="map">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.9147703055!2d-74.11976314309273!3d40.69740344223377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew+York%2C+NY%2C+USA!5e0!3m2!1sen!2sbd!4v1547528325671"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="contact-right">
                                <div class="title">
                                    <h2>Fill Up The Form</h2>
                                    <p>Your email address will not be published. Required fields are marked *
                                    </p>
                                </div>
                                <form class="contact-form contact-validation-active" id="contact-form">
                                    <div class="input-item">
                                        <input id="name" class="fild" type="text" placeholder="Your Name*"
                                            required>
                                        <label><i class="flaticon-user"></i></label>
                                    </div>
                                    <div class="input-item">
                                        <input id="email" name="email" class="fild" type="email" placeholder="Email Address*"
                                            required>
                                        <label><i class="flaticon-email"></i></label>
                                    </div>
                                    <div class="input-item">
                                        <textarea id="message" class="fild textarea"
                                            placeholder="Enter Your Message here" required></textarea>
                                        <label><i class="flaticon-edit"></i></label>
                                    </div>
                                    <div class="input-item submitbtn">
                                        <input class="fild" type="submit" value="Get In Touch">
                                    </div>
                                    <div class="clearfix error-handling-messages">
                                        <div id="success">Thank you</div>
                                        <div id="error"> Error occurred while sending email. Please try again
                                            later.
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



            @include('layouts.web_footer')
@endsection
