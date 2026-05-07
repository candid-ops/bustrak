<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:20px; }
        .container { max-width:600px; margin:0 auto; background:white;
                     border-radius:12px; overflow:hidden; }
        .header { background:linear-gradient(135deg,#0d7a45,#1aad65);
                  padding:30px; text-align:center; }
        .header h1 { color:white; margin:0; font-size:1.5rem; }
        .header p { color:rgba(255,255,255,0.8); margin:4px 0 0; }
        .body { padding:30px; }
        .amount { background:#e8fff3; border-radius:8px; padding:20px;
                  text-align:center; margin-bottom:24px; }
        .amount span { font-size:2rem; font-weight:700; color:#0d7a45; }
        .row { display:flex; justify-content:space-between;
               border-bottom:1px solid #f0f0f0; padding:10px 0; }
        .label { color:#888; font-size:0.85rem; }
        .value { font-weight:600; color:#222; font-size:0.9rem; }
        .footer { background:#f8f9ff; padding:20px; text-align:center;
                  color:#999; font-size:0.8rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>✅ Payment Received</h1>
        <p>Thank you for your payment!</p>
    </div>
    <div class="body">
        <p>Hi <strong>{{ $payment->booking->user->name }}</strong>,</p>
        <p>We have received your payment. Here is your receipt:</p>
        <div class="amount">
            <div style="color:#888; font-size:0.8rem; margin-bottom:4px;">Amount Paid</div>
            <span>KES {{ number_format($payment->amount) }}</span>
        </div>
        <div class="row">
            <span class="label">M-Pesa Receipt</span>
            <span class="value" style="color:#0d7a45;">{{ $payment->mpesa_receipt }}</span>
        </div>
        <div class="row">
            <span class="label">Booking Reference</span>
            <span class="value">{{ $payment->booking->booking_reference }}</span>
        </div>
        <div class="row">
            <span class="label">Route</span>
            <span class="value">
                {{ $payment->booking->schedule->route->origin }}
                → {{ $payment->booking->schedule->route->destination }}
            </span>
        </div>
        <div class="row">
            <span class="label">Travel Date</span>
            <span class="value">
                {{ \Carbon\Carbon::parse($payment->booking->travel_date)->format('d M Y') }}
            </span>
        </div>
        <div class="row">
            <span class="label">Paid At</span>
            <span class="value">
                {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y h:i A') }}
            </span>
        </div>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} BusTrak. All rights reserved.
    </div>
</div>
</body>
</html>