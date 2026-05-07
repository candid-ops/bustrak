@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<style>
    .payment-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .payment-card {
        background: linear-gradient(135deg, rgba(11,15,26,0.95), rgba(6,10,18,0.95));
        backdrop-filter: blur(20px);
        border-radius: 32px;
        border: 1px solid rgba(26,77,255,0.2);
        overflow: hidden;
        max-width: 500px;
        width: 100%;
        position: relative;
        animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .payment-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #1A4DFF, #FF8C42, #00c864, #1A4DFF);
        background-size: 300% 100%;
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; background-position: 0% 0%; }
        100% { left: 100%; background-position: 200% 0%; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(50px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 20px rgba(26,77,255,0.3); }
        50% { box-shadow: 0 0 40px rgba(26,77,255,0.6); }
    }

    .payment-header {
        background: linear-gradient(135deg, #1A4DFF, #0033cc);
        padding: 2rem;
        text-align: center;
        position: relative;
    }

    .payment-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 40px;
        background: #0B0F1A;
        border-radius: 50%;
    }

    .mpesa-logo {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 0 30px rgba(255,255,255,0.3);
        animation: pulse 2s ease-in-out infinite;
    }

    .mpesa-logo i {
        font-size: 3rem;
        color: #1A4DFF;
    }

    .amount {
        font-size: 3rem;
        font-weight: 900;
        font-family: 'Orbitron', monospace;
        text-shadow: 0 0 20px rgba(26,77,255,0.5);
    }

    .booking-summary {
        background: rgba(26,77,255,0.1);
        border-radius: 20px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border: 1px solid rgba(26,77,255,0.2);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px dashed rgba(255,255,255,0.1);
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: rgba(255,255,255,0.6);
        font-size: 0.85rem;
    }

    .summary-value {
        font-weight: 600;
        color: white;
    }

    .phone-input-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .phone-input-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #1A4DFF;
        font-size: 1.1rem;
        z-index: 1;
    }

    .phone-input {
        width: 100%;
        padding: 1rem 1rem 1rem 45px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 50px;
        color: white;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .phone-input:focus {
        outline: none;
        border-color: #1A4DFF;
        background: rgba(26,77,255,0.15);
        box-shadow: 0 0 20px rgba(26,77,255,0.3);
    }

    .pay-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #00c864, #008040);
        border: none;
        border-radius: 50px;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .pay-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .pay-btn:hover::before {
        left: 100%;
    }

    .pay-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,200,100,0.4);
    }

    .pay-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-right: 10px;
    }

    .success-animation {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0,200,100,0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        z-index: 2000;
        animation: glowPulse 0.5s ease-out;
        display: none;
    }

    .success-animation i {
        font-size: 4rem;
        animation: pulse 0.5s ease-out;
    }

    .toast-message {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: linear-gradient(135deg, #00c864, #008040);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 2000;
        animation: slideUp 0.3s ease-out;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
</style>

<div class="payment-container">
    <div class="payment-card">
        <div class="payment-header">
            <div class="mpesa-logo">
                <i class="bi bi-phone-fill"></i>
            </div>
            <h2 style="color: white; margin-bottom: 0.5rem;">Complete Payment</h2>
            <p style="color: rgba(255,255,255,0.8);">Pay securely with M-Pesa</p>
        </div>

        <div style="padding: 2rem;">
            <!-- Booking Summary -->
            <div class="booking-summary">
                <div class="summary-row">
                    <span class="summary-label">Booking Reference</span>
                    <span class="summary-value">{{ $booking->booking_reference ?? $booking->reference }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Route</span>
                    <span class="summary-value">{{ $booking->schedule->route->origin ?? 'N/A' }} → {{ $booking->schedule->route->destination ?? 'N/A' }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Travel Date</span>
                    <span class="summary-value">{{ \Carbon\Carbon::parse($booking->travel_date)->format('d F Y') }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Seat Number</span>
                    <span class="summary-value">{{ $booking->seat_number ?? 'N/A' }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Amount to Pay</span>
                    <span class="summary-value" style="color: #00c864; font-size: 1.5rem; font-weight: 700;">KES {{ number_format($booking->total_amount ?? $booking->schedule->route->fare, 2) }}</span>
                </div>
            </div>

            <!-- M-Pesa Payment Form -->
            <form method="POST" action="{{ route('customer.payment.initiate', $booking) }}" id="paymentForm">
                @csrf
                <div class="phone-input-wrapper">
                    <i class="bi bi-phone"></i>
                    <input type="text" 
                           name="phone" 
                           class="phone-input" 
                           placeholder="0712345678" 
                           required 
                           pattern="[0-9]{10,12}"
                           title="Enter valid M-Pesa number">
                </div>

                <button type="submit" class="pay-btn" id="payBtn">
                    <i class="bi bi-shield-check"></i> Pay with M-Pesa
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="{{ route('customer.dashboard') }}" style="color: rgba(255,255,255,0.5); text-decoration: none; font-size: 0.85rem;">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="successToast" class="toast-message" style="display: none;">
    <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
    <div>
        <strong>Payment Initiated!</strong><br>
        <small>Check your phone for M-Pesa prompt</small>
    </div>
</div>

<script>
    document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('payBtn');
        const originalText = btn.innerHTML;
        
        // Show loading state
        btn.innerHTML = '<span class="loading-spinner"></span> Processing...';
        btn.disabled = true;
        
        // Optional: Show toast
        const toast = document.getElementById('successToast');
        toast.style.display = 'flex';
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.style.display = 'none';
                toast.style.opacity = '1';
            }, 3000);
        }, 2000);
        
        // Form will submit normally
    });

    // Phone number formatting
    const phoneInput = document.querySelector('.phone-input');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('0') && value.length > 10) {
                value = value.slice(0, 10);
            } else if (!value.startsWith('0') && value.length > 12) {
                value = value.slice(0, 12);
            }
            this.value = value;
        });
    }
</script>
@endsection