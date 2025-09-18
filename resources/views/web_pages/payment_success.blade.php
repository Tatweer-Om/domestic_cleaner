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
                        <div class="col-lg-12">
                            <div class="wpo-section-title">
                                <h2 class="poort-text poort-in-right">success</h2>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </section>
      
    @include('layouts.web_footer')
@endsection
