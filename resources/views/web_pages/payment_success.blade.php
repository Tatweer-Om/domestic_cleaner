@extends('layouts.web_header')

@section('main')
    @push('title')
        <title>{{ trans('messages.payment_success_title', [], session('locale')) }}</title>
    @endpush

    <!-- Custom Styles -->
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

        .success-container {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(to right, var(--bg-light), #e0f2fe);
        }

        .success-card {
            max-width: 600px;
            width: 100%;
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: var(--shadow-glow);
            padding: 40px;
            text-align: center;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .success-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
        }

        .success-icon {
            font-size: 4rem;
            color: var(--accent-teal);
            margin-bottom: 20px;
        }

        .success-card h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .success-card p {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .success-card .btn-home {
            background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .success-card .btn-home:hover {
            background: linear-gradient(90deg, #1e3a8a, #2563eb);
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .order-details {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .order-details h5 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .order-details p {
            font-size: 1rem;
            color: var(--text-muted);
            margin: 5px 0;
        }

        /* RTL Support for Arabic */
        [dir="rtl"] .success-card {
            text-align: right;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .success-card {
                padding: 20px;
            }
            .success-card h2 {
                font-size: 2rem;
            }
            .success-card p {
                font-size: 1rem;
            }
            .success-card .btn-home {
                padding: 10px 20px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .success-container {
                padding: 10px;
            }
            .success-card h2 {
                font-size: 1.8rem;
            }
            .success-card .success-icon {
                font-size: 3rem;
            }
        }
    </style>

    <!-- Success Page Content -->
    <div class="success-container">
        <div class="success-card">
            <i class="fas fa-check-circle success-icon"></i>
            <h2>{{ trans('messages.payment_success', [], session('locale')) }}</h2>
            <p>{{ trans('messages.payment_success_message', [], session('locale')) }}</p>

            <!-- Optional: Display order details -->
            <!-- <div class="order-details">
                <h5>{{ trans('messages.order_details', [], session('locale')) }}</h5>
                <p><strong>{{ trans('messages.booking_no', [], session('locale')) }}:</strong> {{ $booking_details['booking_no'] ?? 'N/A' }}</p>
                <p><strong>{{ trans('messages.package', [], session('locale')) }}:</strong> {{ $booking_details['package'] ?? 'N/A' }}</p>
                <p><strong>{{ trans('messages.total_amount', [], session('locale')) }}:</strong> OMR {{ number_format($booking_details['total_amount'] ?? 0, 2) }}</p>
            </div> -->

            <a href="{{ url('/') }}" class="btn-home">{{ trans('messages.back_to_home', [], session('locale')) }}</a>
        </div>
    </div>

    @include('layouts.web_footer')
@endsection