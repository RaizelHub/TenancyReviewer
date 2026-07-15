<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout — RaizelHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --stripe-purple: #635bff;
            --stripe-purple-dark: #4f46e5;
            --stripe-purple-light: #ede9fe;
            --stripe-text: #1a1a2e;
            --stripe-muted: #6b7280;
            --stripe-border: #e5e7eb;
            --stripe-bg: #f6f9fc;
            --stripe-white: #ffffff;
            --stripe-success: #10b981;
            --stripe-error: #ef4444;
            --stripe-warn: #f59e0b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--stripe-bg);
            min-height: 100vh;
            color: var(--stripe-text);
        }

        /* ── TOP NAV ─────────────────────────────────────────── */
        .checkout-nav {
            background: var(--stripe-white);
            border-bottom: 1px solid var(--stripe-border);
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #635bff, #9b59b6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }
        .nav-brand-name {
            font-weight: 700;
            font-size: 17px;
            color: var(--stripe-text);
        }
        .nav-secure {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--stripe-muted);
            font-weight: 500;
        }
        .nav-secure i { color: var(--stripe-success); font-size: 12px; }
        .nav-divider {
            width: 1px;
            height: 24px;
            background: var(--stripe-border);
            margin: 0 16px;
        }
        .nav-return {
            font-size: 13px;
            color: var(--stripe-purple);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: opacity .2s;
        }
        .nav-return:hover { opacity: .7; }

        /* ── LAYOUT ──────────────────────────────────────────── */
        .checkout-container {
            max-width: 960px;
            margin: 0 auto;
            padding: 40px 24px 80px;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 32px;
            align-items: start;
        }
        @media (max-width: 768px) {
            .checkout-container { grid-template-columns: 1fr; }
            .checkout-summary { order: -1; }
        }

        /* ── LEFT: FORM ──────────────────────────────────────── */
        .checkout-form-section { display: flex; flex-direction: column; gap: 24px; }

        .form-card {
            background: var(--stripe-white);
            border-radius: 12px;
            border: 1px solid var(--stripe-border);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }
        .form-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--stripe-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-card-header h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--stripe-text);
        }
        .form-card-header .step-badge {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--stripe-purple);
            color: white;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-card-body { padding: 24px; }

        /* CONTACT */
        .contact-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid var(--stripe-border);
        }
        .contact-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #635bff, #9b59b6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 15px;
            flex-shrink: 0;
        }
        .contact-details { flex: 1; min-width: 0; }
        .contact-name { font-size: 13px; font-weight: 600; color: var(--stripe-text); }
        .contact-email { font-size: 12px; color: var(--stripe-muted); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .contact-edit { font-size: 12px; color: var(--stripe-purple); font-weight: 500; text-decoration: none; white-space: nowrap; }

        /* PAYMENT METHOD TABS */
        .payment-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .payment-tab {
            flex: 1;
            min-width: 80px;
            padding: 10px 14px;
            border: 1.5px solid var(--stripe-border);
            border-radius: 8px;
            background: white;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            color: var(--stripe-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            transition: all .2s;
        }
        .payment-tab i { font-size: 16px; }
        .payment-tab.active {
            border-color: var(--stripe-purple);
            color: var(--stripe-purple);
            background: var(--stripe-purple-light);
        }
        .payment-tab:hover:not(.active) { border-color: #d1d5db; background: #f9fafb; }

        /* FORM FIELDS */
        .form-group { margin-bottom: 16px; }
        .form-group:last-child { margin-bottom: 0; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .form-row-3 { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; }

        .stripe-input-wrapper {
            position: relative;
            border: 1.5px solid var(--stripe-border);
            border-radius: 8px;
            background: white;
            transition: border-color .2s, box-shadow .2s;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        .stripe-input-wrapper:focus-within {
            border-color: var(--stripe-purple);
            box-shadow: 0 0 0 3px rgba(99,91,255,.12);
        }
        .stripe-input-wrapper.error { border-color: var(--stripe-error); }
        .stripe-input-wrapper.error:focus-within { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }

        .stripe-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 11px 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--stripe-text);
            background: transparent;
            width: 100%;
        }
        .stripe-input::placeholder { color: #9ca3af; }
        .input-icon {
            padding: 0 12px 0 0;
            color: #9ca3af;
            font-size: 14px;
            pointer-events: none;
        }
        .card-brand-icon {
            padding: 0 10px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .card-brand-icon img { height: 20px; }

        .input-note {
            font-size: 11px;
            color: var(--stripe-muted);
            margin-top: 4px;
        }
        .input-error {
            font-size: 11px;
            color: var(--stripe-error);
            margin-top: 4px;
            display: none;
        }
        .has-error .input-error { display: block; }
        .has-error .stripe-input-wrapper { border-color: var(--stripe-error); }
        .has-error .stripe-input-wrapper:focus-within { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }

        /* BILLING */
        .billing-same {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #374151;
            cursor: pointer;
            margin-bottom: 16px;
        }
        .billing-same input[type=checkbox] {
            width: 16px; height: 16px; accent-color: var(--stripe-purple); cursor: pointer;
        }

        /* SUBMIT BUTTON */
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--stripe-purple), #7c3aed);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all .25s;
            position: relative;
            overflow: hidden;
        }
        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #7c3aed, var(--stripe-purple));
            opacity: 0;
            transition: opacity .25s;
        }
        .submit-btn:hover::before { opacity: 1; }
        .submit-btn:active { transform: scale(.99); }
        .submit-btn span, .submit-btn i { position: relative; z-index: 1; }
        .submit-btn .lock-icon { font-size: 13px; }

        /* LOADING STATE */
        .submit-btn.loading { pointer-events: none; }
        .btn-spinner {
            width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: none;
            position: relative; z-index: 1;
        }
        .submit-btn.loading .btn-spinner { display: block; }
        .submit-btn.loading .btn-text { display: none; }
        .submit-btn.loading .lock-icon { display: none; }

        @keyframes spin { to { transform: rotate(360deg); } }

        .form-security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            color: var(--stripe-muted);
            margin-top: 12px;
        }
        .form-security-note i { color: var(--stripe-success); }

        /* RIGHT: SUMMARY ─────────────────────────────────────── */
        .checkout-summary {
            position: sticky;
            top: 88px;
        }
        .summary-card {
            background: var(--stripe-white);
            border-radius: 12px;
            border: 1px solid var(--stripe-border);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }

        .plan-badge {
            padding: 24px;
            background: linear-gradient(135deg, #635bff 0%, #7c3aed 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        .plan-badge::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
        }
        .plan-badge::after {
            content: '';
            position: absolute;
            bottom: -20px; left: -20px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        .plan-badge-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
            position: relative; z-index: 1;
        }
        .plan-name {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -.3px;
        }
        .plan-label {
            font-size: 11px;
            font-weight: 500;
            opacity: .8;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 2px;
        }
        .plan-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: rgba(255,255,255,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .plan-price-row {
            display: flex;
            align-items: baseline;
            gap: 4px;
            position: relative; z-index: 1;
        }
        .plan-currency { font-size: 16px; font-weight: 600; margin-top: 4px; }
        .plan-amount { font-size: 36px; font-weight: 700; line-height: 1; letter-spacing: -1px; }
        .plan-period { font-size: 14px; opacity: .8; }

        .summary-body { padding: 20px 24px; }

        .summary-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--stripe-muted);
            padding: 8px 0;
            border-bottom: 1px solid var(--stripe-border);
        }
        .summary-line:last-of-type { border-bottom: none; }
        .summary-line .label { display: flex; align-items: center; gap: 6px; }
        .summary-line .value { font-weight: 500; color: var(--stripe-text); }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0 4px;
            border-top: 2px solid var(--stripe-border);
            margin-top: 8px;
        }
        .summary-total .label { font-size: 15px; font-weight: 600; color: var(--stripe-text); }
        .summary-total .value { font-size: 18px; font-weight: 700; color: var(--stripe-text); }

        .promo-code {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--stripe-border);
        }
        .promo-toggle {
            font-size: 13px;
            color: var(--stripe-purple);
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .promo-toggle:hover { opacity: .8; }
        .promo-input-row {
            display: none;
            margin-top: 10px;
            gap: 8px;
        }
        .promo-input-row.open { display: flex; }
        .promo-field {
            flex: 1;
            padding: 9px 12px;
            border: 1.5px solid var(--stripe-border);
            border-radius: 7px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .2s;
        }
        .promo-field:focus { border-color: var(--stripe-purple); }
        .promo-apply {
            padding: 9px 16px;
            background: var(--stripe-text);
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: opacity .2s;
            white-space: nowrap;
        }
        .promo-apply:hover { opacity: .85; }

        .features-list {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--stripe-border);
        }
        .features-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--stripe-muted);
            margin-bottom: 12px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #374151;
            margin-bottom: 8px;
        }
        .feature-item:last-child { margin-bottom: 0; }
        .feature-check {
            width: 18px; height: 18px;
            border-radius: 50%;
            background: #d1fae5;
            color: var(--stripe-success);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            flex-shrink: 0;
        }

        .guarantee-badge {
            margin-top: 20px;
            padding: 12px 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .guarantee-badge i { color: var(--stripe-success); margin-top: 2px; }
        .guarantee-text { font-size: 12px; color: #065f46; line-height: 1.5; }
        .guarantee-text strong { font-weight: 600; }

        /* STRIPE BRANDING ─────────────────────────────────────── */
        .powered-by {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            color: var(--stripe-muted);
            margin-top: 32px;
        }
        .powered-by svg { height: 18px; }
        .stripe-logo-text {
            font-weight: 700;
            font-size: 14px;
            color: #635bff;
        }

        /* ALERT BANNER ─────────────────────────────────────────── */
        .test-mode-banner {
            background: linear-gradient(135deg, #fff7ed, #fef3c7);
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #92400e;
            margin-bottom: 24px;
        }
        .test-mode-banner i { color: var(--stripe-warn); }
        .test-mode-banner strong { font-weight: 600; }

        /* OVERLAY ─────────────────────────────────────────────── */
        .processing-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(4px);
            z-index: 999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        .processing-overlay.active { display: flex; }

        .processing-card {
            background: white;
            border-radius: 16px;
            padding: 48px 56px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
            max-width: 380px;
            width: 90%;
        }
        .processing-spinner {
            width: 60px; height: 60px;
            border: 4px solid #ede9fe;
            border-top-color: var(--stripe-purple);
            border-radius: 50%;
            animation: spin .8s linear infinite;
            margin: 0 auto 20px;
        }
        .processing-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--stripe-text);
            margin-bottom: 8px;
        }
        .processing-subtitle {
            font-size: 14px;
            color: var(--stripe-muted);
            line-height: 1.6;
        }
        .processing-steps {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            text-align: left;
        }
        .processing-step {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--stripe-muted);
        }
        .step-dot {
            width: 20px; height: 20px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .step-dot.pending { background: #f3f4f6; color: #9ca3af; }
        .step-dot.active { background: #ede9fe; border: 2px solid var(--stripe-purple); }
        .step-dot.active::after {
            content: '';
            width: 8px; height: 8px;
            background: var(--stripe-purple);
            border-radius: 50%;
            animation: pulse 1s ease-in-out infinite;
        }
        .step-dot.done { background: #d1fae5; color: var(--stripe-success); }
        @keyframes pulse {
            0%,100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: .7; }
        }
        .processing-step.done-step { color: var(--stripe-text); }

        /* CARD NUMBER FORMATTING ─────────────────────────────── */
        .card-number-display {
            display: flex;
            gap: 8px;
            padding: 0 10px 0 0;
            align-items: center;
        }
        .card-number-display .card-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #9ca3af;
        }
    </style>
</head>
<body>

    {{-- TOP NAV --}}
    <nav class="checkout-nav">
        <a href="{{ route('subscription.plan') }}" class="nav-brand">
            <div class="nav-logo">R</div>
            <span class="nav-brand-name">RaizelHub</span>
        </a>
        <div style="display:flex;align-items:center;">
            <div class="nav-secure">
                <i class="fas fa-lock"></i>
                <span>Secure Checkout</span>
            </div>
            <div class="nav-divider"></div>
            <a href="{{ route('subscription.plan') }}" class="nav-return">
                <i class="fas fa-arrow-left"></i> Back to plans
            </a>
        </div>
    </nav>

    {{-- PROCESSING OVERLAY --}}
    <div class="processing-overlay" id="processingOverlay">
        <div class="processing-card">
            <div class="processing-spinner"></div>
            <div class="processing-title">Processing Payment</div>
            <div class="processing-subtitle">Please wait while we securely process your subscription.</div>
            <div class="processing-steps">
                <div class="processing-step done-step" id="step1">
                    <div class="step-dot done"><i class="fas fa-check"></i></div>
                    <span>Verifying card details</span>
                </div>
                <div class="processing-step" id="step2">
                    <div class="step-dot active"></div>
                    <span>Processing payment</span>
                </div>
                <div class="processing-step" id="step3">
                    <div class="step-dot pending">3</div>
                    <span>Activating subscription</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="checkout-container">

        {{-- LEFT: FORM --}}
        <div class="checkout-form-section">

            {{-- TEST MODE BANNER --}}
            <div class="test-mode-banner">
                <i class="fas fa-flask"></i>
                <span><strong>Demo Mode</strong> — Use card number <strong>4242 4242 4242 4242</strong>, any future expiry, and any CVC.</span>
            </div>

            {{-- CONTACT --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="step-badge">1</div>
                    <h3>Contact Information</h3>
                </div>
                <div class="form-card-body">
                    <div class="contact-info">
                        <div class="contact-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                        <div class="contact-details">
                            <div class="contact-name">{{ Auth::user()->name ?? 'User' }}</div>
                            <div class="contact-email">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                        <a href="{{ route('tenant.settings.edit') }}" class="contact-edit">Edit</a>
                    </div>
                </div>
            </div>

            {{-- PAYMENT --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="step-badge">2</div>
                    <h3>Payment Method</h3>
                </div>
                <div class="form-card-body">
                    <div class="payment-tabs">
                        <button type="button" class="payment-tab active" data-tab="card" onclick="switchTab('card', this)">
                            <i class="fas fa-credit-card"></i>
                            Card
                        </button>
                        <button type="button" class="payment-tab" data-tab="gcash" onclick="switchTab('gcash', this)">
                            <i class="fas fa-mobile-alt"></i>
                            GCash
                        </button>
                        <button type="button" class="payment-tab" data-tab="maya" onclick="switchTab('maya', this)">
                            <i class="fas fa-wallet"></i>
                            Maya
                        </button>
                        <button type="button" class="payment-tab" data-tab="bank" onclick="switchTab('bank', this)">
                            <i class="fas fa-university"></i>
                            Bank
                        </button>
                    </div>

                    {{-- CARD TAB --}}
                    <div id="tab-card">
                        <div class="form-group">
                            <label class="form-label">Card Number</label>
                            <div class="stripe-input-wrapper" id="cardNumWrapper">
                                <input type="text" class="stripe-input" id="cardNumber"
                                    placeholder="1234 1234 1234 1234"
                                    maxlength="19"
                                    oninput="formatCardNumber(this)"
                                    autocomplete="cc-number">
                                <div class="card-brand-icon" id="cardBrandIcon">
                                    <i class="fas fa-credit-card" style="color:#9ca3af;font-size:18px;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Expiration Date</label>
                                <div class="stripe-input-wrapper">
                                    <input type="text" class="stripe-input" id="expiry"
                                        placeholder="MM / YY"
                                        maxlength="7"
                                        oninput="formatExpiry(this)"
                                        autocomplete="cc-exp">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    CVC
                                    <span style="color:#9ca3af;font-weight:400;margin-left:4px;">
                                        <i class="fas fa-question-circle" title="3-digit code on back of card"></i>
                                    </span>
                                </label>
                                <div class="stripe-input-wrapper">
                                    <input type="text" class="stripe-input" id="cvc"
                                        placeholder="CVC"
                                        maxlength="4"
                                        autocomplete="cc-csc">
                                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Name on Card</label>
                            <div class="stripe-input-wrapper">
                                <input type="text" class="stripe-input" id="cardName"
                                    placeholder="Full name as it appears on card"
                                    autocomplete="cc-name"
                                    value="{{ Auth::user()->name ?? '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- GCASH TAB --}}
                    <div id="tab-gcash" style="display:none;">
                        <div style="padding:20px;text-align:center;background:#f0fdf4;border-radius:10px;border:1px solid #bbf7d0;">
                            <div style="font-size:42px;margin-bottom:12px;">📱</div>
                            <div style="font-weight:600;color:#065f46;margin-bottom:6px;">GCash Mobile Payment</div>
                            <div style="font-size:13px;color:#047857;line-height:1.6;">Enter your GCash-registered mobile number. A payment request will be sent to your GCash app.</div>
                            <div style="margin-top:16px;">
                                <div class="stripe-input-wrapper" style="max-width:260px;margin:0 auto;">
                                    <span style="padding:0 8px 0 12px;color:#9ca3af;font-size:13px;">+63</span>
                                    <input type="text" class="stripe-input" placeholder="9XX XXX XXXX" maxlength="11">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MAYA TAB --}}
                    <div id="tab-maya" style="display:none;">
                        <div style="padding:20px;text-align:center;background:#eff6ff;border-radius:10px;border:1px solid #bfdbfe;">
                            <div style="font-size:42px;margin-bottom:12px;">💳</div>
                            <div style="font-weight:600;color:#1e40af;margin-bottom:6px;">Maya Payment</div>
                            <div style="font-size:13px;color:#1d4ed8;line-height:1.6;">You will be redirected to Maya to complete your payment securely.</div>
                            <div style="margin-top:16px;padding:10px 16px;background:#dbeafe;border-radius:8px;font-size:12px;color:#1e40af;">
                                <i class="fas fa-info-circle"></i> Click "Pay Now" to continue to Maya's secure payment page.
                            </div>
                        </div>
                    </div>

                    {{-- BANK TAB --}}
                    <div id="tab-bank" style="display:none;">
                        <div style="padding:20px;background:#fafafa;border-radius:10px;border:1px solid #e5e7eb;">
                            <div style="font-weight:600;color:#1f2937;margin-bottom:12px;font-size:14px;">Bank Transfer Details</div>
                            <div style="display:grid;gap:8px;">
                                <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                                    <span style="color:#6b7280;">Bank</span>
                                    <span style="font-weight:500;">BDO Unibank</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                                    <span style="color:#6b7280;">Account Name</span>
                                    <span style="font-weight:500;">RaizelHub Inc.</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;">
                                    <span style="color:#6b7280;">Account Number</span>
                                    <span style="font-weight:500;">0034-2847-6190</span>
                                </div>
                            </div>
                            <div style="margin-top:12px;padding:10px;background:#fef3c7;border-radius:6px;font-size:12px;color:#92400e;">
                                <i class="fas fa-exclamation-triangle"></i> Transfer processing takes 1-2 business days.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BILLING --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="step-badge">3</div>
                    <h3>Billing Address</h3>
                </div>
                <div class="form-card-body">
                    <label class="billing-same">
                        <input type="checkbox" id="sameAsContact" checked onchange="toggleBilling(this)">
                        Same as contact information
                    </label>
                    <div id="billingFields" style="display:none;">
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <div class="stripe-input-wrapper">
                                <select class="stripe-input" style="cursor:pointer;">
                                    <option value="PH" selected>🇵🇭 Philippines</option>
                                    <option value="US">🇺🇸 United States</option>
                                    <option value="SG">🇸🇬 Singapore</option>
                                    <option value="AU">🇦🇺 Australia</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <div class="stripe-input-wrapper">
                                <input type="text" class="stripe-input" placeholder="Street address">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <div class="stripe-input-wrapper">
                                    <input type="text" class="stripe-input" placeholder="City">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <div class="stripe-input-wrapper">
                                    <input type="text" class="stripe-input" placeholder="Postal code">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <form method="POST" action="{{ route('subscription.change-plan') }}" id="checkoutForm">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan }}">
                <button type="submit" class="submit-btn" id="submitBtn" onclick="handleSubmit(event)">
                    <i class="fas fa-lock lock-icon"></i>
                    <span class="btn-text">Pay {{ $plans[$plan]['price'] }} / month</span>
                    <div class="btn-spinner"></div>
                </button>
                <div class="form-security-note">
                    <i class="fas fa-shield-alt"></i>
                    <span>256-bit SSL encrypted · PCI DSS compliant</span>
                </div>
            </form>
        </div>

        {{-- RIGHT: SUMMARY --}}
        <div class="checkout-summary">
            <div class="summary-card">
                <div class="plan-badge">
                    <div class="plan-badge-top">
                        <div>
                            <div class="plan-label">Subscribing to</div>
                            <div class="plan-name">{{ $plan }} Plan</div>
                        </div>
                        <div class="plan-icon">
                            <i class="fas {{ $plans[$plan]['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="plan-price-row">
                        <span class="plan-currency">₱</span>
                        <span class="plan-amount">{{ number_format(floatval(preg_replace('/[^0-9.]/', '', $plans[$plan]['price']))) }}</span>
                        <span class="plan-period">/ month</span>
                    </div>
                </div>

                <div class="summary-body">
                    <div class="summary-line">
                        <span class="label"><i class="fas fa-tag" style="width:14px;"></i> {{ $plan }} Plan</span>
                        <span class="value">{{ $plans[$plan]['price'] }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="label"><i class="fas fa-sync" style="width:14px;"></i> Billing cycle</span>
                        <span class="value">Monthly</span>
                    </div>
                    <div class="summary-line">
                        <span class="label"><i class="fas fa-calendar" style="width:14px;"></i> Next billing</span>
                        <span class="value">{{ now()->addMonth()->format('M d, Y') }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="label"><i class="fas fa-percent" style="width:14px;"></i> VAT (12%)</span>
                        <span class="value">Included</span>
                    </div>

                    <div class="summary-total">
                        <span class="label">Due today</span>
                        <span class="value">{{ $plans[$plan]['price'] }}</span>
                    </div>

                    <div class="promo-code">
                        <div class="promo-toggle" onclick="togglePromo()">
                            <i class="fas fa-tag"></i> Add promo code
                        </div>
                        <div class="promo-input-row" id="promoRow">
                            <input type="text" class="promo-field" placeholder="Enter code" id="promoInput">
                            <button class="promo-apply" type="button" onclick="applyPromo()">Apply</button>
                        </div>
                    </div>

                    <div class="features-list">
                        <div class="features-title">What's included</div>
                        @foreach($plans[$plan]['features'] as $feature)
                            <div class="feature-item">
                                <div class="feature-check"><i class="fas fa-check"></i></div>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                        <div class="feature-item">
                            <div class="feature-check"><i class="fas fa-check"></i></div>
                            <span>{{ $plans[$plan]['max_subjects'] }} Subjects</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-check"><i class="fas fa-check"></i></div>
                            <span>{{ $plans[$plan]['max_students'] }} Students</span>
                        </div>
                    </div>

                    <div class="guarantee-badge">
                        <i class="fas fa-shield-alt"></i>
                        <div class="guarantee-text">
                            <strong>30-day money-back guarantee.</strong> Cancel anytime with no questions asked.
                        </div>
                    </div>
                </div>
            </div>

            <div class="powered-by">
                <i class="fas fa-lock" style="font-size:11px;color:#635bff;"></i>
                <span>Powered by</span>
                <span class="stripe-logo-text">RaizelHub Pay</span>
                <span>·</span>
                <span>Secured by SSL</span>
            </div>
        </div>
    </div>

    <script>
        // ── CARD NUMBER FORMATTING ────────────────────────────
        function formatCardNumber(input) {
            let v = input.value.replace(/\D/g, '').substring(0, 16);
            let formatted = v.match(/.{1,4}/g)?.join(' ') || v;
            input.value = formatted;
            updateCardBrand(v);
        }

        function updateCardBrand(num) {
            const icon = document.getElementById('cardBrandIcon');
            let html = '<i class="fas fa-credit-card" style="color:#9ca3af;font-size:18px;"></i>';
            if (/^4/.test(num)) {
                html = '<svg viewBox="0 0 48 16" height="20"><text x="0" y="13" font-family="Arial" font-weight="bold" font-size="13" fill="#1a1f71">VISA</text></svg>';
            } else if (/^5[1-5]/.test(num) || /^2[2-7]/.test(num)) {
                html = '<svg viewBox="0 0 40 26" height="22"><circle cx="15" cy="13" r="13" fill="#eb001b"/><circle cx="25" cy="13" r="13" fill="#f79e1b"/></svg>';
            } else if (/^3[47]/.test(num)) {
                html = '<svg viewBox="0 0 50 16" height="16"><text x="0" y="13" font-family="Arial" font-weight="bold" font-size="10" fill="#2557D6">AMEX</text></svg>';
            }
            icon.innerHTML = html;
        }

        function formatExpiry(input) {
            let v = input.value.replace(/\D/g, '').substring(0, 4);
            if (v.length >= 2) {
                v = v.substring(0, 2) + ' / ' + v.substring(2);
            }
            input.value = v;
        }

        // ── PAYMENT TABS ─────────────────────────────────────
        function switchTab(tab, btn) {
            document.querySelectorAll('.payment-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            ['card', 'gcash', 'maya', 'bank'].forEach(t => {
                document.getElementById('tab-' + t).style.display = (t === tab) ? 'block' : 'none';
            });
        }

        // ── BILLING TOGGLE ────────────────────────────────────
        function toggleBilling(cb) {
            document.getElementById('billingFields').style.display = cb.checked ? 'none' : 'block';
        }

        // ── PROMO CODE ────────────────────────────────────────
        function togglePromo() {
            const row = document.getElementById('promoRow');
            row.classList.toggle('open');
            if (row.classList.contains('open')) document.getElementById('promoInput').focus();
        }

        function applyPromo() {
            const code = document.getElementById('promoInput').value.trim().toUpperCase();
            if (code === 'DEMO10') {
                showToast('Promo code applied! 10% off.', 'success');
            } else if (code === '') {
                showToast('Please enter a promo code.', 'warn');
            } else {
                showToast('Invalid promo code.', 'error');
            }
        }

        // ── TOAST ─────────────────────────────────────────────
        function showToast(msg, type) {
            const colors = { success: '#10b981', error: '#ef4444', warn: '#f59e0b' };
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:24px;right:24px;background:white;border-left:4px solid ${colors[type]};
                border-radius:8px;padding:14px 18px;font-size:13px;font-family:Inter,sans-serif;color:#1a1a2e;
                box-shadow:0 8px 30px rgba(0,0,0,.15);z-index:9999;animation:slideIn .3s ease;max-width:320px;`;
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 3500);
        }

        // ── SUBMIT / PROCESSING ───────────────────────────────
        function handleSubmit(e) {
            e.preventDefault();

            // Basic validation for card tab
            const activeTab = document.querySelector('.payment-tab.active').dataset.tab;
            if (activeTab === 'card') {
                const num = document.getElementById('cardNumber').value.replace(/\s/g, '');
                const exp = document.getElementById('expiry').value;
                const cvc = document.getElementById('cvc').value;

                if (num.length < 16) { showToast('Please enter a valid 16-digit card number.', 'error'); return; }
                if (exp.length < 7)  { showToast('Please enter a valid expiry date.', 'error'); return; }
                if (cvc.length < 3)  { showToast('Please enter a valid CVC.', 'error'); return; }
            }

            // Show loading
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            document.getElementById('processingOverlay').classList.add('active');

            // Animate processing steps
            setTimeout(() => {
                const s2 = document.getElementById('step2');
                s2.classList.add('done-step');
                s2.querySelector('.step-dot').className = 'step-dot done';
                s2.querySelector('.step-dot').innerHTML = '<i class="fas fa-check"></i>';

                const s3 = document.getElementById('step3');
                s3.querySelector('.step-dot').className = 'step-dot active';
                s3.querySelector('.step-dot').innerHTML = '';
            }, 1200);

            setTimeout(() => {
                const s3 = document.getElementById('step3');
                s3.classList.add('done-step');
                s3.querySelector('.step-dot').className = 'step-dot done';
                s3.querySelector('.step-dot').innerHTML = '<i class="fas fa-check"></i>';
            }, 2400);

            // Submit form after delay (simulates processing)
            setTimeout(() => {
                document.getElementById('checkoutForm').submit();
            }, 3000);
        }
    </script>

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</body>
</html>
