@extends('layouts.app')

@section('title', 'Payment Status')

@section('content')
<style>
    .status-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .status-card {
        background: var(--bs-card-bg, white);
        border-radius: 20px;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.1));
        max-width: 500px;
        width: 100%;
        text-align: center;
        padding: 2.5rem 2rem;
        animation: slideUp 0.5s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes checkmark {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .status-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }

    .status-icon.waiting {
        background: #fff3e0;
        border: 2px solid #ff9800;
        color: #ff9800;
    }

    .status-icon.success {
        background: #e8f5e9;
        border: 2px solid #4caf50;
        color: #4caf50;
        animation: checkmark 0.5s ease-out;
    }

    .status-icon.error {
        background: #ffebee;
        border: 2px solid #f44336;
        color: #f44336;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 3px solid #e0e0e0;
        border-top-color: #2196f3;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        animation: spin 1s linear infinite;
    }

    .ticket-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.2rem;
        margin-top: 1.5rem;
        text-align: left;
        border-left: 4px solid #2196f3;
    }

    .email-badge {
        display: inline-block;
        background: #e8f5e9;
        border: 1px solid #4caf50;
        border-radius: 50px;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        margin-top: 1rem;
        color: #2e7d32;
    }

    .btn-dashboard {
        background: #2196f3;
        border: none;
        border-radius: 50px;
        padding: 0.7rem 1.8rem;
        color: white;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        margin-top: 1.5rem;
    }

    .btn-dashboard:hover {
        background: #1976d2;
        transform: translateY(-2px);
        color: white;
    }

    .progress-bar {
        width: 100%;
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        margin: 1rem 0;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #2196f3;
        border-radius: 2px;
        animation: loading 3s ease-in-out infinite;
    }

    @keyframes loading {
        0% { width: 0%; }
        50% { width: 70%; }
        100% { width: 100%; }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .status-card {
            background: #1e1e2e;
            border-color: #313244;
        }
        
        .status-icon.waiting {
            background: rgba(255, 152, 0, 0.15);
            border-color: #ff9800;
        }
        
        .status-icon.success {
            background: rgba(76, 175, 80, 0.15);
            border-color: #4caf50;
        }
        
        .status-icon.error {
            background: rgba(244, 67, 54, 0.15);
            border-color: #f44336;
        }
        
        .loading-spinner {
            border-color: #313244;
            border-top-color: #64b5f6;
        }
        
        .ticket-card {
            background: #2a2a3e;
            border-left-color: #64b5f6;
        }
        
        .email-badge {
            background: rgba(76, 175, 80, 0.15);
            border-color: #4caf50;
            color: #81c784;
        }
        
        .progress-bar {
            background: #313244;
        }
        
        h3, p {
            color: #fff;
        }
        
        p {
            color: rgba(255,255,255,0.7);
        }
        
        .btn-dashboard {
            background: #64b5f6;
            color: #1a1a2e;
        }
        
        .btn-dashboard:hover {
            background: #90caf9;
        }
    }
</style>

<div class="status-container">
    <div class="status-card">
        <div id="waitingState">
            <div class="loading-spinner"></div>
            <h3 style="margin-bottom: 0.5rem;">Processing Payment</h3>
            <p style="color: var(--bs-secondary-color, #666);">Please wait while we confirm your payment...</p>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <p style="font-size: 0.85rem; margin-top: 1rem; color: #ff9800;">
                <i class="bi bi-clock"></i> Do not close this page
            </p>
        </div>

        <div id="successState" style="display: none;">
            <div class="status-icon success">
                <i class="bi bi-check-lg"></i>
            </div>
            <h3 style="color: #4caf50; margin-bottom: 0.5rem;">Payment Successful!</h3>
            <p style="color: var(--bs-secondary-color, #666);">Your booking has been confirmed.</p>
            
            <div class="ticket-card">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="font-size: 0.8rem; opacity: 0.7;">Booking Reference</span>
                    <span style="font-weight: 600; font-family: monospace;">{{ $booking->booking_reference ?? $booking->reference ?? 'N/A' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Route:</span>
                    <span><strong>{{ $booking->schedule->route->origin ?? 'N/A' }} → {{ $booking->schedule->route->destination ?? 'N/A' }}</strong></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Seat:</span>
                    <span><strong>{{ $booking->seat_number ?? 'N/A' }}</strong></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Amount Paid:</span>
                    <span><strong style="color: #4caf50;">KES {{ number_format($booking->total_amount ?? $booking->schedule->route->fare ?? 0, 0) }}</strong></span>
                </div>
            </div>

            <div class="email-badge">
                <i class="bi bi-envelope-check-fill"></i>
                <span>Ticket sent to your email</span>
            </div>

            <a href="{{ route('customer.dashboard') }}" class="btn-dashboard">
                <i class="bi bi-speedometer2"></i> Go to Dashboard
            </a>
        </div>

        <div id="errorState" style="display: none;">
            <div class="status-icon error">
                <i class="bi bi-x-lg"></i>
            </div>
            <h3 style="color: #f44336; margin-bottom: 0.5rem;">Payment Failed</h3>
            <p>Something went wrong. Please try again.</p>
            <a href="{{ route('customer.payment', $booking) }}" class="btn-dashboard" style="background: #f44336;">
                <i class="bi bi-arrow-repeat"></i> Retry Payment
            </a>
        </div>

        <div id="emailSentNotice" style="margin-top: 1rem; font-size: 0.8rem; color: var(--bs-secondary-color, #888);">
            <i class="bi bi-envelope"></i> Check {{ $booking->user->email ?? 'your email' }} for your ticket
        </div>
    </div>
</div>

<script>
    let checkCount = 0;
    let confirmationReceived = false;

    function checkPaymentStatus() {
        if (confirmationReceived) return;
        
        fetch('{{ route("customer.payment.check", $booking) }}')
            .then(response => response.json())
            .then(data => {
                console.log('Status check:', data);
                
                if (data.status === 'confirmed' || data.payment_status === 'completed') {
                    confirmationReceived = true;
                    document.getElementById('waitingState').style.display = 'none';
                    document.getElementById('successState').style.display = 'block';
                } else if (data.status === 'failed' || data.payment_status === 'failed') {
                    document.getElementById('waitingState').style.display = 'none';
                    document.getElementById('errorState').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
            });
        
        checkCount++;
        
        if (checkCount < 30) {
            setTimeout(checkPaymentStatus, 5000);
        } else if (!confirmationReceived) {
            document.getElementById('waitingState').style.display = 'none';
            document.getElementById('errorState').style.display = 'block';
            document.getElementById('errorState').querySelector('p').innerHTML = 'Payment taking longer than expected.<br>Please check your email or contact support.';
        }
    }

    setTimeout(checkPaymentStatus, 3000);
</script>
@endsection