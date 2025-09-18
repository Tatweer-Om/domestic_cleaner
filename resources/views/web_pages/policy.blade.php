

    @extends('layouts.web_header')

@section('main')
    @push('title')
        <title> {{ trans('messages.policy', [], session('locale')) }}</title>
    @endpush
             <div class="breadcumb-area box-style">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcumb-wrap">
                    <h2>{{ trans('messages.clean_feels_better', [], session('locale')) }}</h2>
                    <h3>{{ trans('messages.terms_conditions_title', [], session('locale')) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>        <!-- end page-title -->
        <!-- start blog-single-section -->
        <section class="blog-single-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col col-lg-10 col-12 offset-lg-1">
                        <div class="post format-standard-image">
                            <div class="entry-details">
                                <h3>{{ trans('messages.terms_conditions_heading', [], session('locale')) }}</h3>
                                <p>{{ trans('messages.terms_conditions_content', [], session('locale')) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @include('layouts.web_footer')
@endsection
