@extends('layouts.web_header')

@section('main')
@push('title')
    <title> {{ trans('messages.payment_page', [], session('locale')) }}</title>
@endpush

<!-- Bootstrap CSS -->

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

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }


    .payment-container {
        min-height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: linear-gradient(to right, var(--bg-light), #e0f2fe);
    }

    .payment-card {
        display: flex;
        flex-direction: row;
        width: 100%;
        max-width: 1200px;
        background: var(--card-bg);
        border-radius: 24px;
        box-shadow: var(--shadow-glow);
        overflow: hidden;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .payment-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
    }

    .payment-header {
        flex: 0 0 30%;
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        color: white;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        border-radius: 24px 0 0 24px;
    }

    .payment-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2), transparent);
        opacity: 0.3;
        z-index: 0;
    }

    .payment-header h3 {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 12px;
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .trust-badge {
        font-size: 0.95rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        z-index: 1;
        background: rgba(0, 0, 0, 0.1);
        padding: 8px 16px;
        border-radius: 20px;
    }

    .card-body {
        flex: 0 0 70%;
        padding: 30px;
        background: #fff;
    }

    .payment-details-table {
        font-size: 1rem;
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100%;
    }

    .payment-details-table th {
        font-weight: 600;
        color: var(--text-dark);
        padding: 12px 15px;
        width: 35%;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .payment-details-table td {
        padding: 12px 15px;
        background: #f8fafc;
        border-radius: 8px;
        color: var(--text-dark);
        border-bottom: 1px solid #e5e7eb;
    }

    .payment-details-table .total-row td {
        background: linear-gradient(90deg, #dbeafe, #bfdbfe);
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--primary-blue);
        border-radius: 8px;
    }

    .payment-details-table .visit-list li {
        padding: 6px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .payment-method-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 12px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .payment-method-card:hover, .payment-method-card.active {
        border-color: var(--primary-blue);
        box-shadow: 0 6px 20px rgba(31, 41, 55, 0.15);
        transform: scale(1.03);
    }

    .payment-method-card img {
        width: 40px;
        height: 40px;
        object-fit: contain;
        margin-right: 10px;
    }

    .payment-method-card h5 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .payment-method-card small {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .btn-pay-now {
        background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
        border: none;
        padding: 14px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-pay-now:hover {
        background: linear-gradient(90deg, #1e3a8a, #2563eb);
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .btn-pay-now::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    .btn-pay-now:hover::after {
        width: 350px;
        height: 350px;
    }

    .trust-indicators {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .trust-indicators img {
        height: 35px;
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    .trust-indicators img:hover {
        transform: scale(1.1);
        filter: brightness(1.1);
    }

    .trust-indicators p {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .voucher-entry {
        max-width: 320px;
        transition: all 0.3s ease;
    }

    .voucher-entry input {
        border-radius: 8px 0 0 8px;
        border: 1px solid #d1d5db;
    }

    .voucher-entry button {
        border-radius: 0 8px 8px 0;
        background: var(--accent-teal);
        border: none;
        color: white;
    }

    .voucher-entry button:hover {
        background: #0d9488;
    }

    #voucher-feedback {
        font-size: 0.85rem;
        margin-top: 6px;
        transition: opacity 0.3s ease;
    }

    .alert {
        border-radius: 8px;
        padding: 10px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Mobile Responsiveness */
    @media (max-width: 992px) {
        .payment-card {
            flex-direction: column;
            border-radius: 16px;
        }
        .payment-header {
            flex: 0 0 auto;
            border-radius: 16px 16px 0 0;
            padding: 20px;
        }
        .card-body {
            flex: 0 0 auto;
            padding: 20px;
        }
        .payment-header h3 {
            font-size: 1.6rem;
        }
    }

    @media (max-width: 768px) {
        .payment-container {
            padding: 10px;
        }
        .payment-card {
            margin: 0;
            border-radius: 12px;
        }
        .payment-details-table th, .payment-details-table td {
            font-size: 0.9rem;
            padding: 8px;
        }
        .btn-pay-now {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
        }
        .payment-method-card {
            padding: 10px;
        }
        .payment-method-card img {
            width: 35px;
            height: 35px;
        }
        .voucher-entry {
            max-width: 100%;
        }
        .payment-header h3 {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 576px) {
        .payment-header {
            padding: 15px;
        }
        .payment-details-table th {
            width: 45%;
            font-size: 0.85rem;
        }
        .payment-details-table td {
            font-size: 0.85rem;
        }
        .trust-indicators {
            flex-direction: column;
            gap: 8px;
        }
        .payment-method-card h5 {
            font-size: 0.9rem;
        }
        .payment-method-card small {
            font-size: 0.75rem;
        }
        .btn-pay-now {
            font-size: 0.95rem;
            padding: 10px;
        }
        .payment-header .trust-badge {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
    }
</style>

<div class="payment-container">
    <div class="payment-card">
        <!-- Payment Header -->
        <div class="payment-header">
            <h3>{{ trans('messages.checkout', [], session('locale')) }}</h3>
            <p class="trust-badge">
                <i class="fas fa-lock me-2"></i> {{ trans('messages.secure_payment', [], session('locale')) }}
            </p>
        </div>

        <!-- Payment Body -->
        <div class="card-body">
            <!-- Error Message (if any) -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Booking Summary -->
            <h4 class="mb-4 text-dark">{{ trans('messages.booking_summary', [], session('locale')) }}</h4>
            <h6 class="mb-4 text-dark" id="timer"></h6>
            <input type="hidden" class="booking_no" value="{{ $booking_details['booking_no'] }}">
            <table class="table payment-details-table">
                <tbody>
                    <tr>
                        <th scope="row">{{ trans('messages.package', [], session('locale')) }}</th>
                        <td>{{ $booking_details['package'] ?? trans('messages.unknown', [], session('locale')) }}</td>
                    </tr>
                    <tr>
                        <th scope="row">{{ trans('messages.worker', [], session('locale')) }}</th>
                        <td>{{ $booking_details['worker'] ?? trans('messages.unknown', [], session('locale')) }}</td>
                    </tr>
                    <tr>
                        <th scope="row">{{ trans('messages.location', [], session('locale')) }}</th>
                        <td>{{ $booking_details['location'] ?? trans('messages.unknown', [], session('locale')) }}</td>
                    </tr>

                    <tr>
                        <th scope="row">{{ trans('messages.subtotal', [], session('locale')) }}</th>
                        <td>
                            OMR <span id="subtotal-amount">{{ number_format($booking_details['subtotal'] ?? 0, 2) }}</span>
                            <div class="form-check mt-2">
                                <input class="form-check-input js-use-voucher" type="checkbox" id="use-voucher">
                                <label class="form-check-label" for="use-voucher">
                                    {{ trans('messages.have_voucher', [], session('locale')) ?? 'Have a voucher?' }}
                                </label>
                            </div>
                            <form id= "voucher_form" method="post">
                                @csrf
                          <div id="voucher-entry" class="input-group input-group-sm mt-2 d-none js-voucher-entry">
                                <input type="text" class="form-control" id="voucher-code"
                                       placeholder="{{ trans('messages.enter_voucher', [], session('locale')) ?? 'Enter voucher code' }}">
                                <button class="btn btn-outline-primary" type="button" id="apply-voucher-btn">
                                    Apply
                                </button>
                            </div>
                        </form>

                            <div id="voucher-feedback" class="form-text d-none"></div>
                        </td>
                    </tr>
                    <tr>
                        <input type="hidden" class="total_discount" value="{{ number_format($booking_details['discount'] ?? 0, 3) }}">
                        <th scope="row">{{ trans('messages.discount', [], session('locale')) }}</th>
                        <td>OMR <span id="base-discount-amount">{{ number_format($booking_details['discount'] ?? 0, 2) }}</span></td>
                    </tr>
                    <tr id="voucher-discount-row" class="d-none">
                        <input type="hidden" class="total_voucher" value="0.000">
                        <th scope="row">{{ trans('messages.voucher_discount', [], session('locale')) ?? 'Voucher Discount' }}</th>
                        <td>OMR <span id="voucher-discount-amount">0.00</span></td>
                    </tr>
                    <tr class="total-row">
                        <input type="hidden" class="total_amount" value="{{ number_format($booking_details['total_amount'] ?? 0, 3) }}">
                        <th scope="row">{{ trans('messages.total_amount', [], session('locale')) }}</th>
                        <td>OMR <span id="total-amount">{{ number_format($booking_details['total_amount'] ?? 0, 2) }}</span></td>
                    </tr>
                </tbody>
            </table>

            <!-- Payment Methods -->
            <h4 class="mt-4 mb-3 text-dark">{{ trans('messages.payment_method', [], session('locale')) }}</h4>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="payment-method-card card h-100 active" data-method="OmanNET">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/card/Apple-Pay-logo.png') }}" alt="{{ trans('messages.oman_net_alt', [], session('locale')) }}" class="me-2">
                            <div>
                                <h5>Amwal</h5>
                                <small>{{ trans('messages.pay_with_apple', [], session('locale')) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-6 col-md-3">
                    <div class="payment-method-card card h-100" data-method="Visa">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/card/Visa-Logo.png') }}" alt="{{ trans('messages.visa_alt', [], session('locale')) }}" class="me-2">
                            <div>
                                <h5>Visa</h5>
                                <small>{{ trans('messages.credit_debit_card', [], session('locale')) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="payment-method-card card h-100" data-method="MasterCard">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/card/master_card.jpg') }}" alt="{{ trans('messages.mastercard_alt', [], session('locale')) }}" class="me-2">
                            <div>
                                <h5>MasterCard</h5>
                                <small>{{ trans('messages.credit_debit_card', [], session('locale')) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="payment-method-card card h-100" data-method="PayPal">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/card/paypal.png') }}" alt="{{ trans('messages.paypal_alt', [], session('locale')) }}" class="me-2">
                            <div>
                                <h5>PayPal</h5>
                                <small>{{ trans('messages.secure_online_payment', [], session('locale')) }}</small>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
                <div class="form-check mt-4 text-start">
                    <input class="form-check-input" type="checkbox" id="accept-policy">
                    <label class="form-check-label" for="accept-policy">
                        {{ trans('messages.i_accept_policy', [], session('locale')) }}
                        <a href="{{ url('policy') }}" target="_blank">
                            {{ trans('messages.view_policy', [], session('locale')) }}
                        </a>
                    </label>
                </div>
            <!-- Pay Now Button -->
            <div class="mt-4 text-center">
                <button type="button" class="btn btn-pay-now" id="pay-now-btn" data-worker-id="{{ $worker_id }}" {{ empty($booking_details['visits']) ? 'disabled' : '' }}>
                    {{ trans('messages.pay_now', [], session('locale')) }} <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-3 trust-indicators">
                <p><i class="fas fa-shield-alt me-2"></i> {{ trans('messages.secured_by', [], session('locale')) }} TLS Encryption</p>
                <img src="{{ asset('images/card/secure.png') }}" alt="{{ trans('messages.secure_payment_alt', [], session('locale')) }}">
                <img src="{{ asset('images/card/trust.png') }}" alt="{{ trans('messages.trusted_payment_alt', [], session('locale')) }}">
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg theme-modal" 
    id="expire-order-modal" 
    tabindex="-1" 
    role="dialog" 
    aria-hidden="true" 
    data-backdrop="static" 
    data-keyboard="false">
    <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
        <div class="modal-content quick-view-modal">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 rtl-text">
                        <h3 class="mt-2 text-center">انتهى الوقت</h3>
                        <hr>
                        <div style="padding:10px;height:100px;">
                            <h3 class="text-danger text-center">
                                تم الوصول إلى حد وقت الدفع.<br>
                                تم إلغاء طلبك.
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
  function toggleEntry(entry, checked) {
    if (!entry) return;
    if (checked) {
      entry.classList.remove('d-none');
      entry.style.display = '';                 // clear any inline "display:none"
      const firstInput = entry.querySelector('input,textarea');
      if (firstInput) firstInput.focus();
    } else {
      entry.classList.add('d-none');
      entry.style.display = 'none';             // hard-hide as fallback
      const firstInput = entry.querySelector('input,textarea');
      if (firstInput) firstInput.value = '';    // optional: clear
    }
  }

  function initScope(scope) {
    const cb    = scope.querySelector('#use-voucher, .js-use-voucher');
    const entry = scope.querySelector('#voucher-entry, .js-voucher-entry');
    if (!cb || !entry) return;

    // set initial state
    toggleEntry(entry, cb.checked);

    // direct listener
    cb.addEventListener('change', function () {
      toggleEntry(entry, cb.checked);
    });
  }

  // init on ready (handles multiple cards)
  function initAll() {
    document.querySelectorAll('.payment-card, .payment-container, body').forEach(initScope);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }

  // delegated handler for dynamically-added content
  document.addEventListener('change', function (e) {
    if (!e.target.matches('#use-voucher, .js-use-voucher')) return;
    const scope = e.target.closest('.payment-card, .payment-container') || document;
    const entry = scope.querySelector('#voucher-entry, .js-voucher-entry');
    toggleEntry(entry, e.target.checked);
  });
})();
</script>





@include('layouts.web_footer')
@endsection
