<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:20px; }
        .container { max-width:600px; margin:0 auto; background:white;
                     border-radius:12px; overflow:hidden; }
        .header { background:linear-gradient(135deg,#1a1f5e,#2d3494);
                  padding:30px; text-align:center; }
        .header h1 { color:white; margin:0; font-size:1.5rem; }
        .header p { color:rgba(255,255,255,0.8); margin:4px 0 0; }
        .body { padding:30px; }
        .ref { background:#f0f4ff; border-radius:8px; padding:16px;
               text-align:center; margin-bottom:24px; }
        .ref span { font-size:1.4rem; font-weight:700; color:#1a1f5e; }
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
        <h1>🚌 BusTrak</h1>
        <p>Booking Confirmed!</p>
    </div>
    <div class="body">
        <p>Hi <strong>{{ $booking->user->name }}</strong>,</p>
        <p>Your booking has been confirmed. Here are your details:</p>
        <div class="ref">
            <div style="color:#888; font-size:0.8rem; margin-bottom:4px;">Booking Reference</div>
            <span>{{ $booking->booking_reference }}</span>
        </div>
        <div class="row">
            <span class="label">Route</span>
            <span class="value">
                {{ $booking->schedule->route->origin }}
                → {{ $booking->schedule->route->destination }}
            </span>
        </div>
        <div class="row">
            <span class="label">Travel Date</span>
            <span class="value">
                {{ \Carbon\Carbon::parse($booking->travel_date)->format('d M Y') }}
            </span>
        </div>
        <div class="row">
            <span class="label">Departure</span>
            <span class="value">
                {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}
            </span>
        </div>
        <div class="row">
            <span class="label">Seat Number</span>
            <span class="value">Seat {{ $booking->seat_number }}</span>
        </div>
        <div class="row">
            <span class="label">Bus</span>
            <span class="value">{{ $booking->schedule->bus->plate_number }}</span>
        </div>
        <p style="margin-top:24px; color:#666; font-size:0.88rem;">
            Please arrive at the bus stop at least 15 minutes before departure.
            Show this email or your booking reference at the gate.
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} BusTrak. All rights reserved.
    </div>
</div>
</body>
</html>