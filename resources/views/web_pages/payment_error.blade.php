@extends('layouts.web_header')

@section('main')
    @push('title')
        <title>{{ trans('messages.payment_error_title', [], session('locale')) }}</title>
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
            --accent-red: #ef4444;
            --shadow-glow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .error-container {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(to right, var(--bg-light), #fee2e2);
        }

        .error-card {
            max-width: 600px;
            width: 100%;
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: var(--shadow-glow);
            padding: 40px;
            text-align: center;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            border: 2px solid var(--accent-red);
        }

        .error-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
        }

        .error-icon {
            font-size: 4rem;
            color: var(--accent-red);
            margin-bottom: 20px;
        }

        .error-card h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .error-card p {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .error-card .btn-home {
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
            text-decoration: none;
            display: inline-block;
        }

        .error-card .btn-home:hover {
            background: linear-gradient(90deg, #1e3a8a, #2563eb);
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .order-details {
            background: #fef2f2;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid var(--accent-red);
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
        [dir="rtl"] .error-card {
            text-align: right;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .error-card {
                padding: 20px;
            }
            .error-card h2 {
                font-size: 2rem;
            }
            .error-card p {
                font-size: 1rem;
            }
            .error-card .btn-home {
                padding: 10px 20px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .error-container {
                padding: 10px;
            }
            .error-card h2 {
                font-size: 1.8rem;
            }
            .error-card .error-icon {
                font-size: 3rem;
            }
        }
    </style>

    <!-- Error Page Content -->
    <div class="error-container">
        <div class="error-card">
            <i class="fas fa-exclamation-circle error-icon"></i>
            <h2>{{ trans('messages.payment_failed', [], session('locale')) }}</h2>
            <p>{{ trans('messages.payment_error_message', [], session('locale')) }}</p>

            <!-- Optional: Display order details -->
            @if(isset($booking_details))
            <div class="order-details">
                <h5>{{ trans('messages.order_details', [], session('locale')) }}</h5>
                <p><strong>{{ trans('messages.booking_no', [], session('locale')) }}:</strong> {{ $booking_details['booking_no'] ?? 'N/A' }}</p>
                <p><strong>{{ trans('messages.package', [], session('locale')) }}:</strong> {{ $booking_details['package'] ?? 'N/A' }}</p>
                <p><strong>{{ trans('messages.total_amount', [], session('locale')) }}:</strong> OMR {{ number_format($booking_details['total_amount'] ?? 0, 2) }}</p>
            </div>
            @endif

            <a href="{{ url('/') }}" class="btn-home">{{ trans('messages.back_to_home', [], session('locale')) }}</a>
        </div>
    </div>

    @include('layouts.web_footer')
@endsection