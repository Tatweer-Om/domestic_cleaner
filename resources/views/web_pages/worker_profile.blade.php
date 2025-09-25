@extends('layouts.web_header')

@section('main')
@push('title')
<title> {{ trans('messages.worker_profile_lang', [], session('locale')) }}</title>
@endpush



<style>
    .availability-message {
        font-size: 0.85rem;
        margin-top: 10px;
        padding: 5px;
        border-left: 3px solid #dc3545;
        /* Red border for emphasis */
    }

    /* Icon inputs consistency */
    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap .icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        opacity: .75;
    }

    .input-icon-wrap .form-control {
        padding-left: 2.25rem !important;
    }

    /* Keep button heights compact & consistent */
    #authShell .btn {
        padding: 6px 10px;
        font-size: 14px;
    }

    /* Optional: subtle section divider */
    #authShell {
        border-top: 1px dashed rgba(0, 0, 0, .08);
        padding-top: .5rem;
    }

    /* Background & layout */
    /* Background */
    .bg-gradient-clean {
        background: linear-gradient(135deg, #2563eb 0%, #22c55e 50%, #1e293b 100%);
        padding: 3.5rem 1.25rem;
    }

    @media (min-width: 992px) {
        .bg-gradient-clean {
            padding: 5rem 2rem;
        }
    }

    /* Chips */
    .chip {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        padding: .4rem .7rem;
        border-radius: 999px;
        font-size: .8rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(5px);
    }

    /* Circle photo */
    .hero-photo-circle {
        width: 260px;
        height: 260px;
        border-radius: 50%;
        padding: .5rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, .35), rgba(255, 255, 255, .05));
        border: 2px solid rgba(255, 255, 255, .25);
        backdrop-filter: blur(6px);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .hero-photo-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    /* Floating badge */
    .photo-badge {
        position: absolute;
        bottom: 10px;
        right: -10px;
        background: #fff;
        color: #111827;
        border-radius: 999px;
        padding: .35rem .8rem;
        font-size: .75rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        box-shadow: 0 3px 6px rgba(0, 0, 0, .15);
    }

    .photo-badge .dot {
        width: .45rem;
        height: .45rem;
        border-radius: 50%;
        background: #16a34a;
    }

    /* Ambient blur shapes */
    .hero-shape-1,
    .hero-shape-2 {
        position: absolute;
        pointer-events: none;
        filter: blur(40px);
    }

    .hero-shape-1 {
        width: 380px;
        height: 380px;
        right: -120px;
        top: -120px;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, .35), transparent 60%);
    }

    .hero-shape-2 {
        width: 320px;
        height: 320px;
        left: -100px;
        bottom: -120px;
        background: radial-gradient(circle at 70% 70%, rgba(255, 255, 255, .2), transparent 60%);
    }

    /* width: .45rem;
        height: .45rem;
        border-radius: 50%;
        background: #16a34a;
        } */

    .shift-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        border-radius: 1rem;
        background: #fff;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .shift-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .btn-check:checked+.shift-option {
        border-color: #0d6efd;
        background: #f0f7ff;
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    }

    .shift-icon {
        font-size: 1.4rem;
        margin-bottom: 0.25rem;
    }

    .shift-title {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .shift-time {
        font-size: 0.75rem;
        color: #6c757d;
    }


    /* Ambient shapes */
    .hero-shape-1,
    .hero-shape-2 {
        position: absolute;
        pointer-events: none;
        z-index: 0;
        filter: blur(40px);
    }

    .hero-shape-1 {
        width: 380px;
        height: 380px;
        right: -120px;
        top: -120px;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, .35), transparent 60%);
    }

    .hero-shape-2 {
        width: 320px;
        height: 320px;
        left: -100px;
        bottom: -120px;
        background: radial-gradient(circle at 70% 70%, rgba(255, 255, 255, .2), transparent 60%);
    }

    /* Tidy up icon badge */
    .badge.bg-white {
        border: 1px solid rgba(0, 0, 0, .06);
    }

    /* XS variant: tighter everything */
    .mini-cal--xs {
        max-width: 240px;
        border: 1px solid #e8eef2;
        background: #fff;
    }

    .mini-cal-head--xs {
        padding: 8px 10px;
        background: linear-gradient(135deg, #22c55e10, #16a34a10);
        border-bottom: 1px solid #eef2f6;
    }

    .mini-cal-head--xs .label {
        font-size: .72rem;
        font-weight: 700;
        color: #0f5132;
    }

    .date-wrap--xs {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 5px 8px;
        border: 1px solid #dbe7dc;
        border-radius: 8px;
        background: #f6fff8;
    }

    .mini-date--xs {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        font-size: .78rem;
        color: #0f5132;
        line-height: 1.1;
    }

    .mini-cal-body--xs {
        padding: 8px 10px;
    }

    /* 2×2 compact grid for pills */
    .pills--grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        border: 1px solid #e6eaf0;
        background: #fff;
        color: #374151;
        cursor: pointer;
        user-select: none;
        transition: all .12s ease;
    }

    .pill-xxs {
        padding: 4px 8px;
        font-size: .72rem;
        font-weight: 700;
        line-height: 1;
    }

    .pill:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, .06);
    }

    /* Variants */
    .pill-success {
        border-color: #bfe7cc;
        color: #0f5132;
        background: #f4fff7;
    }

    .btn-check:checked+.pill-success {
        background: #22c55e;
        border-color: #22c55e;
        color: #fff;
        box-shadow: 0 6px 14px rgba(34, 197, 94, .2);
    }

    .pill-primary {
        border-color: #cfe0ff;
        color: #1e40af;
        background: #f6faff;
    }

    .btn-check:checked+.pill-primary {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
        box-shadow: 0 6px 14px rgba(59, 130, 246, .2);
    }

    /* Make booking section wider on large screens */
    .booking-shell .glass-card {
        max-width: 100%;
        /* Allow full container width */
    }


    @media (min-width: 1200px) {
        .booking-shell .col-12.col-xl-10 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    /* Keep pills nice in one line where possible */
    .d-flex.flex-wrap.gap-2 label.pill {
        white-space: nowrap;
    }

    /* Mini calendar card (compact) */
    .mini-cal {
        border: 1px solid #e8eef2;
        background: #fff;
        overflow: hidden;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    }

    .mini-cal--sm {
        max-width: 280px;
    }

    /* make box smaller; tweak 260–300 as you like */

    /* Head */
    .mini-cal-head {
        padding: 10px 12px;
        background: linear-gradient(135deg, #22c55e12 0%, #16a34a12 100%);
        border-bottom: 1px solid #eef2f6;
    }

    .mini-cal-head .label {
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: .02em;
        color: #0f5132;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .date-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 8px;
        border: 1px solid #dbe7dc;
        border-radius: 9px;
        background: #f6fff8;
        color: #0f5132;
    }

    .mini-date {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        color: #0f5132;
        font-size: .8rem;
        line-height: 1.2;
    }

    .mini-date::-webkit-calendar-picker-indicator {
        cursor: pointer;
    }

    /* Body */
    .mini-cal-body {
        padding: 10px 12px;
    }

    .group {
        margin-bottom: 8px;
    }

    .group:last-child {
        margin-bottom: 0;
    }

    .group-title {
        font-size: .75rem;
        color: #6b7280;
        font-weight: 700;
        margin-bottom: 6px;
    }

    /* Pills (radio-as-button) */
    .pills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .btn-check {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        font-size: .78rem;
        font-weight: 700;
        border-radius: 999px;
        border: 1px solid #e6eaf0;
        color: #374151;
        background: #fff;
        cursor: pointer;
        user-select: none;
        transition: all .15s ease;
        line-height: 1.1;
    }

    .pill-xs {
        padding: 4px 9px;
        font-size: .75rem;
    }

    /* extra compact */
    .pill .ico {
        font-size: .9em;
        line-height: 1;
    }

    .pill:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, .05);
    }

    /* Success variant (Shift) */
    .pill-success {
        border-color: #bfe7cc;
        color: #0f5132;
        background: #f3fff7;
    }

    .btn-check:checked+.pill-success {
        background: #22c55e;
        border-color: #22c55e;
        color: #fff;
        box-shadow: 0 6px 16px rgba(34, 197, 94, .22);
    }

    /* Primary variant (Duration) */
    .pill-primary {
        border-color: #cfe0ff;
        color: #1e40af;
        background: #f6faff;
    }

    .btn-check:checked+.pill-primary {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
        box-shadow: 0 6px 16px rgba(59, 130, 246, .22);
    }

    /* Subtle shadow + radius utilities */
    .shadow-sm {
        box-shadow: 0 8px 24px rgba(16, 24, 40, .06);
    }

    .rounded-4 {
        border-radius: 14px;
    }


    /* Hero */
    .service-hero {
        background: radial-gradient(1200px 600px at 10% -10%, #2ec5ce33, transparent 60%),
            radial-gradient(1200px 600px at 100% 10%, #845ef733, transparent 50%),
            linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
        color: #fff;
        padding: 48px 0 56px;
        position: relative;
    }

    .hero-copy .badge {
        background: rgba(255, 255, 255, .85);
        border: 1px solid #e9ecef;
    }

    .hero-art {
        height: 140px;
        border-radius: 16px;
        background: linear-gradient(135deg, #3d8bff 0%, #845ef7 60%, #22d3ee 100%);
        opacity: .25;
        filter: blur(6px);
    }

    .hero-shape-1,
    .hero-shape-2 {
        position: absolute;
        inset: auto 0 -40px auto;
        height: 80px;
        filter: blur(20px);
        opacity: .35;
    }

    .hero-shape-1 {
        left: 5%;
        right: 50%;
        background: linear-gradient(90deg, #22d3ee, #3d8bff);
    }

    .hero-shape-2 {
        left: 55%;
        right: 5%;
        background: linear-gradient(90deg, #845ef7, #22d3ee);
    }

    /* Glass card */
    .booking-shell {
        margin-top: -36px;
        margin-bottom: 40px;
    }

    .glass-card {
        background: rgba(255, 255, 255, .75);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid #eef1f6;
    }

    .glass-head {
        padding: 14px 18px;
        border-bottom: 1px solid #eef1f6;
        background: rgba(255, 255, 255, .55);
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .glass-head .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3d8bff, #22d3ee);
        box-shadow: 0 0 0 4px rgba(61, 139, 255, .15);
    }

    .glass-body {
        padding: 18px;
    }

    /* Inputs with icons + pills */
    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap .icon {
        position: absolute;
        inset: 0 auto 0 10px;
        display: grid;
        place-items: center;
        color: #6c757d;
        pointer-events: none;
    }

    .pill {
        border-radius: 999px;
        padding-inline: .9rem;
    }

    /* Button polish */
    .btn-primary {
        box-shadow: 0 8px 22px rgba(61, 139, 255, .25);
    }

    .btn-primary:active {
        transform: translateY(1px);
    }

    .mini-cal {
        width: 100%;
        max-width: 360px;
        border: 1px solid #e8eef2;
        background: #fff;
        overflow: hidden;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }

    .mini-cal-head {
        padding: 12px 14px;
        background: linear-gradient(135deg, #22c55e14 0%, #16a34a14 100%);
        /* success tint */
        border-bottom: 1px solid #eef2f6;
    }

    .mini-cal-head .label {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .02em;
        color: #0f5132;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .date-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border: 1px solid #dbe7dc;
        border-radius: 10px;
        background: #f6fff8;
        color: #0f5132;
    }

    .mini-date {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        color: #0f5132;
        font-size: .9rem;
    }

    .mini-date::-webkit-calendar-picker-indicator {
        cursor: pointer;
    }

    .mini-cal-body {
        padding: 12px 14px;
    }

    .group {
        margin-bottom: 10px;
    }

    .group-title {
        font-size: .8rem;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 6px;
    }

    /* Pills (radio-as-button) */
    .pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .btn-check {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .pill {
        display: inline-block;
        padding: 6px 12px;
        font-size: .8rem;
        font-weight: 600;
        border-radius: 999px;
        border: 1px solid #e6eaf0;
        color: #374151;
        background: #fff;
        cursor: pointer;
        user-select: none;
        transition: all .15s ease;
    }

    .pill:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }

    /* Success style pills (Shift) */
    .pill-success {
        border-color: #bfe7cc;
        color: #0f5132;
        background: #f3fff7;
    }

    .btn-check:checked+.pill-success {
        background: #22c55e;
        border-color: #22c55e;
        color: #fff;
        box-shadow: 0 6px 18px rgba(34, 197, 94, .25);
    }

    /* Primary style pills (Duration) */
    .pill-primary {
        border-color: #cfe0ff;
        color: #1e40af;
        background: #f6faff;
    }

    .btn-check:checked+.pill-primary {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
        box-shadow: 0 6px 18px rgba(59, 130, 246, .25);
    }

    /* Small shadow utility */
    .shadow-sm {
        box-shadow: 0 10px 30px rgba(16, 24, 40, .06);
    }

    .rounded-4 {
        border-radius: 16px;
    }

    /* Tiny day label inside the date chip */
    .date-wrap small.day-name {
        font-size: 0.7rem;
        font-weight: 600;
        color: #6c757d;
        line-height: 1;
        margin-left: 6px;
        white-space: nowrap;
    }

    /* Keep the date chip compact */
    .mini-date {
        min-width: 0;
    }

    /* keep your existing booking styles */

    /* Beautiful Datepicker Styles */
    .flatpickr-calendar {
        background: #fff;
        border: 1px solid #e1e5e9;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        font-family: inherit;
    }

    .flatpickr-months {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 12px 12px 0 0;
        padding: 12px;
    }

    .flatpickr-month {
        color: white;
    }

    .flatpickr-prev-month,
    .flatpickr-next-month {
        color: white !important;
    }

    .flatpickr-prev-month:hover,
    .flatpickr-next-month:hover {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
    }

    .flatpickr-weekdays {
        background: #f8fafc;
        border-bottom: 1px solid #e1e5e9;
    }

    .flatpickr-weekday {
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .flatpickr-days {
        padding: 8px;
    }

    .flatpickr-day {
        border-radius: 8px;
        margin: 1px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .flatpickr-day:hover {
        background: #e2e8f0;
        border-color: #e2e8f0;
    }

    .flatpickr-day.selected {
        background: #22c55e;
        border-color: #22c55e;
        color: white;
    }

    .flatpickr-day.today {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
    }

    .flatpickr-day.today.selected {
        background: #22c55e;
        border-color: #22c55e;
        color: white;
    }

    /* Friday styling - crossed out and disabled */
    .flatpickr-day.friday {
        background: #fee2e2;
        color: #dc2626;
        text-decoration: line-through;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .flatpickr-day.friday:hover {
        background: #fee2e2;
        border-color: #fee2e2;
    }

    .flatpickr-day.friday.selected {
        background: #fee2e2;
        border-color: #fee2e2;
        color: #dc2626;
    }

    /* Mini date input styling */
    .mini-date {
        border: 1px solid #dbe7dc;
        border-radius: 8px;
        background: #f6fff8;
        color: #0f5132;
        font-size: 0.8rem;
        padding: 6px 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .mini-date:hover {
        border-color: #22c55e;
        background: #f0fdf4;
    }

    .mini-date:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    /* Date input wrapper */
    .date-input-wrapper {
        position: relative;
    }

    .date-input-wrapper::after {
        content: "📅";
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        font-size: 14px;
    }
</style>



<div class="service-hero position-relative overflow-hidden bg-gradient-clean rounded-4">
    <div class="container position-relative">
        <div class="row align-items-center g-4">

            {{-- Copy --}}
            <div class="col-12 col-lg-7">
                <div class="hero-copy text-center text-lg-start">
                    <div class="d-inline-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('assets/images/cleaning-icon.svg') }}" alt="" width="22"
                            height="22">
                        <span class="badge rounded-pill bg-white text-dark shadow-sm">{{ trans('messages.domestic_workers', [], session('locale')) }}</span>
                    </div>

                    <h1 class="display-5 fw-bold text-white mb-3">
                        {{ $worker->worker_name ?? trans('messages.residential_cleaning', [], session('locale')) }}
                    </h1>

                    <p class="lead text-white-50 mb-4">
                        {{ trans('messages.clean_feels_better_emoji', [], session('locale')) }}
                    </p>
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-2">
                        {{-- Location name chip --}}
                        <span class="chip bg-light text-dark fw-semibold px-3 py-1 rounded-pill shadow-sm">
                            <i class="ti-location-pin me-1 text-primary"></i>
                            {{ $location_name ?? trans('messages.no_location', [], session('locale')) }}
                        </span>

                        {{-- Delivery status chip --}}
                        @if($delivery == 1)
                        <span class="chip bg-success text-white fw-semibold px-3 py-1 rounded-pill shadow-sm">
                            <i class="ti-truck me-1"></i> {{ trans('messages.delivery_available', [], session('locale')) }}
                        </span>
                        @elseif($delivery == 2)
                        <span class="chip bg-danger text-white fw-semibold px-3 py-1 rounded-pill shadow-sm">
                            <i class="ti-truck me-1"></i> {{ trans('messages.delivery_unavailable', [], session('locale')) }}
                        </span>
                        @else
                        <span class="chip bg-secondary text-white fw-semibold px-3 py-1 rounded-pill shadow-sm">
                            <i class="ti-truck me-1"></i> {{ trans('messages.delivery_unknown', [], session('locale')) }}
                        </span>
                        @endif

                        {{-- Example static chip --}}
                        <span class="chip bg-info text-white fw-semibold px-3 py-1 rounded-pill shadow-sm">
                            <i class="ti-time me-1"></i> {{ trans('messages.on_time', [], session('locale')) }}
                        </span>
                    </div>


                </div>
            </div>

            {{-- Circle Photo --}}
            <div class="col-12 col-lg-5 text-center">
                @php
                $fileName = $worker->worker_image ?? ($worker->image ?? null);
                $imgSrc = $fileName
                ? asset('images/worker_images/' . $fileName)
                : asset('assets/images/default-worker.png');
                @endphp

                <div class="hero-photo-circle mx-auto position-relative">
                    <img src="{{ $imgSrc }}" alt="{{ $worker->worker_name ?? trans('messages.worker_photo', [], session('locale')) }}"
                        class="img-fluid rounded-circle shadow-lg hero-photo-img"
                        onerror="this.onerror=null;this.src='<?php echo asset('images/dummy_images/1.png'); ?>';">

                    {{-- Floating badge --}}
                <div class="photo-badge shadow-sm">
    <span class="dot"></span> {{ trans('messages.ready_to_work', [], session('locale')) }}
</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Background shapes --}}
    <div class="hero-shape-1"></div>
    <div class="hero-shape-2"></div>
</div>



<section class="booking-shell py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="glass-card shadow-lg rounded-4 border-0">
                    <div class="glass-head d-flex align-items-center justify-content-between p-4 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <span class="dot"></span>
                            <h5 class="mb-0 fw-bold">{{ trans('messages.book_this_service', [], session('locale')) }}</h5>
                        </div>
                        <small class="text-muted d-none d-sm-block">{{ trans('messages.choose_package_date_shift_hours', [], session('locale')) }}</small>
                    </div>

                    <div class="glass-body p-4">
                        <form id="bookingForm" class="needs-validation" novalidate>
                            @csrf
                            <div class="row g-4 align-items-end">

                                <div class="col-12 col-md-3">
                                    <label class="form-label small fw-semibold text-secondary">
                                        📦 {{ trans('messages.package', [], session('locale')) }}
                                    </label>
                                    <div class="input-icon-wrap">
                                        <select class="form-select ps-5" id="packageSelect" required>
                                            <option value="" selected disabled>{{ trans('messages.select_package', [], session('locale')) }}</option>
                                            @foreach ($packages as $package)
                                            <option value="{{ $package->id }}" data-name="{{ $package->package_name }}">
                                                {{ $package->package_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="worker_id" id="worker_id" value="{{ $worker->id ?? '' }}">
                                    <div class="invalid-feedback">
                                        {{ trans('messages.please_select_package', [], session('locale')) }}
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label class="form-label small fw-semibold text-secondary">
                                        🗓️ {{ trans('messages.start_date', [], session('locale')) }}
                                    </label>
                                    <div class="input-icon-wrap">
                                        <input type="text" class="form-control ps-5" id="startDate" placeholder="{{ trans('messages.select_start_date', [], session('locale')) }}" readonly required>
                                    </div>
                                    <div class="invalid-feedback">
                                        {{ trans('messages.please_choose_start_date', [], session('locale')) }}
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label class="form-label small fw-semibold text-secondary d-block mb-2">
                                        ☀️ {{ trans('messages.shifts', [], session('locale')) }}
                                    </label>
                                    <div class="d-flex align-items-center gap-2 flex-nowrap">
                                        <input type="radio" class="btn-check" id="shiftMorning" name="shift" value="morning" autocomplete="off">
                                        <label class="btn btn-outline-primary btn-sm pill w-100" for="shiftMorning">
                                            {{ trans('messages.morning', [], session('locale')) }}
                                            <small class="text-muted d-block">({{ trans('messages.morning_hours', [], session('locale')) }})</small>
                                        </label>

                                        <input type="radio" class="btn-check" id="shiftEvening" name="shift" value="evening" autocomplete="off">
                                        <label class="btn btn-outline-primary btn-sm pill w-100" for="shiftEvening">
                                            {{ trans('messages.evening', [], session('locale')) }}
                                            <small class="text-muted d-block">({{ trans('messages.evening_hours', [], session('locale')) }})</small>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label class="form-label small fw-semibold text-secondary d-block mb-2">
                                       ⏱️ {{ trans('messages.duration', [], session('locale')) }}
                                    </label>
                                    <div class="d-flex align-items-center gap-2 flex-nowrap">
                                        <input type="checkbox" class="btn-check duration-check" id="dur4" value="4" autocomplete="off">
                                        <label class="btn btn-outline-primary btn-sm pill w-100" for="dur4">
                                            {{ trans('messages.duration_4h', [], session('locale')) }}
                                        </label>

                                        <input type="checkbox" class="btn-check duration-check" id="dur5" value="5" autocomplete="off">
                                        <label class="btn btn-outline-primary btn-sm pill w-100" for="dur5">
                                            {{ trans('messages.duration_5h', [], session('locale')) }}
                                        </label>
                                    </div>
                                </div>
                                    <div class="col-lg-12 col-12 mt-3" id="packagePriceBox"></div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-success rounded-3 w-100 py-2 fw-semibold" id="generateBtn">
                                        {{ trans('messages.generate', [], session('locale')) }}
                                    </button>
                                </div>

                                <div class="col-lg-12 col-12 mt-3" id="packagePriceBox">
                                </div>
                            </div>
                        </form>
                        <div id="previewCard" class="alert alert-info mt-4 d-none mb-0 rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary">Preview</span>
                                <strong>Booking summary</strong>
                            </div>
                            <ul class="mb-1 ps-3 small" id="previewList"></ul>
                            <small class="text-muted">This is only a preview. Submission will be wired later.</small>
                        </div>

                        <div id="visitTiles" class="row g-3 mt-1"></div>

                        <div id="authShell" class="mt-4 d-none">
                            <hr class="my-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="dot"></span>
                                    <h5 class="mb-0 fw-bold">Sign in or Register</h5>
                                </div>
                                <small class="text-muted">Access account to continue</small>
                            </div>

                            <div class="row g-3 align-items-start">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="button" id="tabRegister" class="btn btn-success btn-sm">Register</button>
                                        <button type="button" id="tabLogin" class="btn btn-outline-success btn-sm">Login</button>
                                    </div>
                                </div>

                                <div class="col-12" id="registerPane">
                                    <form id="registerForm" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <div class="input-icon-wrap">
                                                    <input type="number" name="phone" id="phone" class="form-control form-control-sm ps-5" placeholder="Phone Number" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="input-icon-wrap">
                                                    <input type="text" name="user_name" class="form-control form-control-sm ps-5 user_name" id="user_name" placeholder="User Name" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="input-icon-wrap">
                                                    <input type="password" name="password" class="form-control form-control-sm ps-5" placeholder="Password" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success btn-sm rounded-3 w-100" style="background:#198754;border-color:#198754;">Register</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-12 d-none" id="loginPane">
                                    <form id="loginForm" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <div class="input-icon-wrap">
                                                    <input type="text" name="phone_name" class="form-control form-control-sm ps-5 phone_name" id="phone_name" placeholder="Phone or User Name" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="input-icon-wrap">
                                                    <input type="password" name="password" class="form-control form-control-sm ps-5" placeholder="Password" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success btn-sm rounded-3 w-100" id="loginbutton" style="background:#198754;border-color:#198754;">Login</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .glass-card {
        background: #fff;
    }

    .glass-head {
        padding: 14px 16px;
        border-bottom: 1px solid #eef2f6;
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        display: inline-block;
    }

    .glass-body {
        padding: 14px 16px;
    }

    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap .icon {
        position: absolute;
        inset-inline-start: 10px;
        top: 50%;
        transform: translateY(-50%);
        opacity: .7;
    }

    .input-icon-wrap .form-control,
    .input-icon-wrap .form-select {
        padding-inline-start: 36px !important;
    }

    .pill {
        border-radius: 999px;
    }

    /* Keep inline items tight with no wrap on md+ screens */
    @media (min-width: 768px) {
        .flex-nowrap {
            flex-wrap: nowrap !important;
        }
    }
</style>






<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('layouts.web_footer')
@endsection