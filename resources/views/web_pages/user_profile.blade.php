@extends('layouts.web_header')

@section('main')
@push('title')
<title> {{ trans('messages.worker_profile_lang', [], session('locale')) }}</title>
@endpush

<style>
    :root {
        --primary-blue: #1e40af;
        --secondary-blue: #3b82f6;
        --bg-light: #f1f5f9;
        --card-bg: #ffffff;
        --text-dark: #111827;
        --text-muted: #6b7280;
        --accent-teal: #14b8a6;
        --shadow-glow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 24px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-glow);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }

    .stat-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 1.8rem !important;
        color: var(--text-dark);
        font-weight: 700;
    }

    /* RTL Support for Arabic */
    [dir="rtl"] .stat-card {
        text-align: right;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .stat-card {
            padding: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
        }

        .stat-value {
            font-size: 1.5rem !important;
        }

        .stat-label {
            font-size: 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .stat-card {
            padding: 12px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
        }

        .stat-value {
            font-size: 1.3rem !important;
        }
    }

    #historyTabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        /* smooth scroll */
        gap: .5rem;
        /* space between buttons */
    }

    /* Hide scrollbar in WebKit browsers */
    #historyTabs::-webkit-scrollbar {
        display: none;
    }

    /* Make pills fill width nicely on desktop */
    @media (min-width: 768px) {
        #historyTabs {
            justify-content: center;
        }
    }

    /* keep tab look consistent with previous snippet */
    .card-tabs .nav-link {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #e9ecef;
        color: #212529;
    }

    .card-tabs .nav-link.active {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }

    .bg-dark-subtle {
        background: #f1f3f5;
    }

    .worker-card {
        background: #fff;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        height: 28px;
        border-radius: 999px;
        padding: 0 .6rem;
        font-size: .8rem;
        border: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    /* star rating */
    .rating {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 6px;
    }

    .rating input {
        display: none;
    }

    .rating label {
        font-size: 24px;
        cursor: pointer;
        color: #d0d5dd;
        line-height: 1;
        filter: drop-shadow(0 1px 0 rgba(0, 0, 0, .04));
    }

    .rating label:hover,
    .rating label:hover~label {
        color: #ffb800;
    }

    /* hover fill */
    .rating input:checked~label {
        color: #ffb800;
    }

    /* selected fill */

    .card-tabs .nav-link {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #e9ecef;
        color: #212529;
    }

    .card-tabs .nav-link.active {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }

    .bg-dark-subtle {
        background: #f1f3f5;
    }

    .list.sleek-list {
        display: grid;
        gap: 12px;
    }

    .sleek-item {
        background: #fff;
        border: 1px solid #edf0f2;
        border-radius: 14px;
        padding: 12px 14px;
        transition: all .2s ease;
    }

    .sleek-item:hover {
        box-shadow: 0 .75rem 1.25rem rgba(0, 0, 0, .06);
        transform: translateY(-2px);
    }

    .sleek-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .sleek-title {
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .sleek-sub {
        color: #6c757d;
        font-size: .9rem;
    }

    .sleek-aside {
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .sleek-amount {
        font-weight: 700;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        height: 28px;
        border-radius: 999px;
        padding: 0 .6rem;
        font-size: .8rem;
        border: 1px solid transparent;
    }

    .chip-id {
        background: #f8f9fa;
        border-color: #e9ecef;
    }

    .chip-status.upcoming {
        background: rgba(13, 110, 253, .1);
        color: #0d6efd;
    }

    .chip-status.completed {
        background: rgba(25, 135, 84, .12);
        color: #198754;
    }

    .stat-card {
        background: #fff;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08);
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        font-size: 18px;
    }

    .stat-label {
        letter-spacing: .2px;
    }

    .stat-value {
        font-weight: 700;
        font-size: 1.25rem;
        line-height: 1.2;
    }

    .bg-primary-subtle {
        background: rgba(13, 110, 253, .12);
    }

    .bg-info-subtle {
        background: rgba(13, 202, 240, .12);
    }

    .bg-warning-subtle {
        background: rgba(255, 193, 7, .15);
    }

    .bg-success-subtle {
        background: rgba(25, 135, 84, .12);
    }
</style>
<!-- start wpo-page-title -->
<div class="breadcumb-area box-style py-4 py-md-5">
    <div class="container">
        <!-- User info -->
        <div class="row align-items-center mb-4">
            <div class="col-12 text-center">
                <h3 class="mb-1">{{ $user->user_name ?? '' }}</h3>
                <div class="text-muted small">
                    {{ trans('messages.account_overview', [], session('locale')) ?? 'Account Overview' }}
                </div>
                <div class="mt-2">
                    @if (!empty($user->phone))
                    <span class="badge rounded-pill bg-light text-dark border">
                        <i class="fas fa-phone me-1"></i> {{ $user->user_phone }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats cards -->
        <div class="row g-4 justify-content-center">
            <!-- Total Bookings -->
            <div class="col-6 col-md-3">
                <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="stat-icon bg-primary-subtle text-primary mx-auto mb-3">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div class="stat-label text-muted small mb-1">
                        {{ trans('messages.total_bookings', [], session('locale')) ?? 'Total Bookings' }}
                    </div>
                    <div class="stat-value fs-3 fw-bold">{{ $totalBookings ?? 0 }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="stat-icon bg-primary-subtle text-primary mx-auto mb-3">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div class="stat-label text-muted small mb-1">
                        {{ trans('messages.total_visits', [], session('locale')) ?? 'Total Visits' }}
                    </div>
                    <div class="stat-value fs-3 fw-bold">{{ $visitCount ?? 0 }}</div>
                </div>
            </div>

            <!-- Pending Visits -->
            <div class="col-6 col-md-3">
                <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="stat-icon bg-warning-subtle text-warning mx-auto mb-3">
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                    <div class="stat-label text-muted small mb-1">
                        {{ trans('messages.pending_visits', [], session('locale')) ?? 'Pending Visits' }}
                    </div>
                    <div class="stat-value fs-3 fw-bold">{{ $pending ?? 0 }}</div>
                </div>
            </div>

            <!-- Completed Visits -->
            <div class="col-6 col-md-3">
                <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="stat-icon bg-success-subtle text-success mx-auto mb-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div class="stat-label text-muted small mb-1">
                        {{ trans('messages.completed_visits', [], session('locale')) ?? 'Completed Visits' }}
                    </div>
                    <div class="stat-value fs-3 fw-bold">{{ $completed ?? 0 }}</div>
                </div>
            </div>

            <!-- Next Visit Date and Time -->
            <!-- <div class="col-6 col-md-3">
                <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="stat-icon bg-info-subtle text-info mx-auto mb-3">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <div class="stat-label text-muted small mb-1">
                        {{ trans('messages.next_visit', [], session('locale')) ?? 'Next Visit' }}
                    </div>
                    <div class="stat-value fs-1">
                        {{ $nextVisitDateTime ?? trans('messages.no_upcoming_visit', [], session('locale')) }}
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>


<!-- Current Booking -->


<!-- Payments -->

</div>

</div>
</div>

<div id="user-profile" data-user-id="{{ $user->id }}"></div>
<!-- start blog-single-section -->
<section class="section-padding">
    <div class="container">

        <!-- Tabs -->
        <div class="w-100">
            <ul class="nav nav-pills card-tabs mb-3" id="historyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="bookings-tab" data-bs-toggle="pill"
                        data-bs-target="#bookings-pane" type="button" role="tab">
                        <i class="fas fa-calendar-check me-2"></i>{{ trans('messages.bookings', [], session('locale')) }}
                        <span class="badge bg-dark-subtle text-dark ms-2"></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="visits-tab" data-bs-toggle="pill" data-bs-target="#visits-pane"
                        type="button" role="tab">
                        <i class="fas fa-walking me-2"></i>{{ trans('messages.visits', [], session('locale')) }}
                        <span class="badge bg-dark-subtle text-dark ms-2"></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="workers-tab" data-bs-toggle="pill" data-bs-target="#workers-pane"
                        type="button" role="tab">
                        <i class="fas fa-users me-2"></i>{{ trans('messages.feedback', [], session('locale')) }}
                        <span class="badge bg-dark-subtle text-dark ms-2"></span>
                    </button>
                </li>
            </ul>
        </div>
        <hr>

        <div class="tab-content">
            <!-- BOOKINGS TAB -->
            <div class="tab-pane fade show active" id="bookings-pane" role="tabpanel" aria-labelledby="bookings-tab">

                <!-- Dummy Bookings -->
                <div class="list sleek-list">

                </div>

            </div>

            <!-- VISITS TAB -->
            <div class="tab-pane fade" id="visits-pane" role="tabpanel" aria-labelledby="visits-tab">

                <!-- Dummy Visits -->
                <div class="list sleek-list">

                </div>

            </div>
            <div class="tab-pane fade" id="workers-pane" role="tabpanel" aria-labelledby="workers-tab">

                <div class="row g-3">
                    <!-- Worker Card 1 (Dummy) -->
                    <div class="col-12 col-md-6">
                        <div class="worker-card shadow-sm rounded-4 p-3 h-100">

                            <hr class="my-3">

                            <form action="{{ url('feedback/worker') }}" method="post" class="worker-feedback-form">
                                {{-- @csrf --}}
                                <input type="hidden" name="worker_id" value="101">

                                <!-- Stars -->
                                <div class="mb-2">
                                    <div class="rating" data-for="worker-101">
                                        <input type="radio" id="w101s5" name="rating" value="5"><label
                                            for="w101s5" title="5 stars">★</label>
                                        <input type="radio" id="w101s4" name="rating" value="4"><label
                                            for="w101s4" title="4 stars">★</label>
                                        <input type="radio" id="w101s3" name="rating" value="3"><label
                                            for="w101s3" title="3 stars">★</label>
                                        <input type="radio" id="w101s2" name="rating" value="2"><label
                                            for="w101s2" title="2 stars">★</label>
                                        <input type="radio" id="w101s1" name="rating" value="1"><label
                                            for="w101s1" title="1 star">★</label>
                                    </div>
                                    <small class="text-muted d-block">Tap a star to rate</small>
                                </div>

                                <!-- Feedback -->
                                <div class="mb-3">
                                    <textarea name="feedback" class="form-control" rows="3"
                                        placeholder="Share your experience (punctuality, attitude, quality)…"></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i> Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Worker Card 2 (Dummy) -->


                </div>
            </div>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.worker-feedback-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // remove this when wiring to backend

                const workerId = form.querySelector('input[name="worker_id"]').value;
                const rating = form.querySelector('input[name="rating"]:checked')?.value || '0';
                const feedback = form.querySelector('textarea[name="feedback"]').value.trim();

                alert(
                    `(Demo) Feedback captured:\nWorker ID: ${workerId}\nStars: ${rating}\nFeedback: ${feedback || '(empty)'}`);
                form.reset();
            });
        });
    });
</script>

@include('layouts.web_footer')
@endsection