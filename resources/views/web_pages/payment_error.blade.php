             @extends('layouts.web_header')

@section('main')
    @push('title')
        <title> {{ trans('messages.services', [], session('locale')) }}</title>
    @endpush

          <!-- end page-title -->
        <!-- start wpo-service-section -->
        <section class="wpo-service-section style-3 section-padding">
            <div class="wpo-service-wrap">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-12">
                            <div class="wpo-section-title">
                                <h2 class="poort-text poort-in-right">{{ $msg }}</h2>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </section>
      
    @include('layouts.web_footer')
@endsection
