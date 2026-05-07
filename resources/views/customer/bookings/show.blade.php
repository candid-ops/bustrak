@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<style>
    .booking-container {
        animation: fadeInUp 0.5s ease;
        padding: 1rem 0;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .content-card {
        background: var(--bs-card-bg, white);
        border-radius: 20px;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.1));
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .card-header {
        background: linear-gradient(135deg, #2196f3, #1565c0);
        padding: 1.2rem 1.5rem;
        color: white;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Info Cards */
    .info-card {
        background: var(--bs-tertiary-bg, #f8f9fa);
        border-radius: 16px;
        padding: 1.2rem;
        height: 100%;
        border: 1px solid var(--bs-border-color, #e0e0e0);
    }

    .info-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #2196f3;
        display: inline-block;
    }

    .info-table {
        width: 100%;
    }

    .info-table tr {
        border-bottom: 1px solid var(--bs-border-color, #eee);
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table td, 
    .info-table th {
        padding: 10px 0;
        vertical-align: top;
    }

    .info-table th {
        width: 40%;
        font-weight: 500;
        color: var(--bs-secondary-color, #666);
    }

    .info-table td {
        width: 60%;
        font-weight: 500;
        color: var(--bs-body-color, #333);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-badge.confirmed {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #4caf50;
    }

    .status-badge.pending {
        background: #fff3e0;
        color: #e65100;
        border: 1px solid #ff9800;
    }

    .status-badge.cancelled {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #f44336;
    }

    .status-badge i {
        margin-right: 6px;
    }

    /* Seat Badges */
    .seat-badge-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .seat-badge-item {
        display: inline-flex;
        align-items: center;
        background: #2196f3;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .seat-badge-item i {
        margin-right: 4px;
        font-size: 0.7rem;
    }

    /* Price Highlight */
    .price-highlight {
        font-size: 1.2rem;
        font-weight: 700;
        color: #ff9800;
    }

    /* QR Code Placeholder */
    .qr-placeholder {
        background: var(--bs-tertiary-bg, #f8f9fa);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        border: 1px dashed var(--bs-border-color, #ddd);
    }

    .qr-placeholder i {
        font-size: 3rem;
        color: #2196f3;
        margin-bottom: 0.5rem;
    }

    /* Button */
    .btn-back {
        background: #6c757d;
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .info-card {
            margin-bottom: 1rem;
        }
        
        .info-table th,
        .info-table td {
            font-size: 0.9rem;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .status-badge.confirmed {
            background: rgba(76, 175, 80, 0.15);
            color: #81c784;
        }
        
        .status-badge.pending {
            background: rgba(255, 152, 0, 0.15);
            color: #ffb74d;
        }
        
        .status-badge.cancelled {
            background: rgba(244, 67, 54, 0.15);
            color: #ef9a9a;
        }
        
        .qr-placeholder {
            background: var(--bs-tertiary-bg, #2a2a3e);
        }
        
        .seat-badge-item {
            background: #1976d2;
        }
    }
</style>

<div class="booking-container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="content-card">
                <div class="card-header">
                    <i class="bi bi-ticket-perforated me-2"></i> Booking Details
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Left Column - Booking Info -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-title">
                                    <i class="bi bi-info-circle me-2"></i> Booking Information
                                </div>
                                <table class="info-table">
                                    <tr>
                                        <th>Reference:</th>
                                        <td><strong>{{ $booking->reference ?? $booking->booking_reference ?? 'N/A' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @php
                                                $status = strtolower($booking->status ?? 'pending');
                                                $statusIcon = [
                                                    'confirmed' => 'bi-check-circle-fill',
                                                    'pending' => 'bi-clock-fill',
                                                    'cancelled' => 'bi-x-circle-fill'
                                                ][$status] ?? 'bi-question-circle';
                                            @endphp
                                            <span class="status-badge {{ $status }}">
                                                <i class="{{ $statusIcon }}"></i>
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Booking Date:</th>
                                        <td>{{ isset($booking->created_at) ? \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount:</th>
                                        <td class="price-highlight">KSh {{ number_format($booking->total_amount ?? 0, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method:</th>
                                        <td><i class="bi bi-phone me-1"></i> M-Pesa</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Right Column - Trip Info -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-title">
                                    <i class="bi bi-bus-front me-2"></i> Trip Information
                                </div>
                                <table class="info-table">
                                    <tr>
                                        <th>Route:</th>
                                        <td>
                                            <strong>
                                                {{ $booking->schedule->route->origin ?? $booking->origin ?? 'N/A' }} 
                                                <i class="bi bi-arrow-right mx-1"></i> 
                                                {{ $booking->schedule->route->destination ?? $booking->destination ?? 'N/A' }}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bus:</th>
                                        <td>
                                            <i class="bi bi-bus-front me-1"></i> 
                                            {{ $booking->schedule->bus->plate_number ?? $booking->bus_number ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Departure Date:</th>
                                        <td>
                                            <i class="bi bi-calendar3 me-1"></i> 
                                            {{ isset($booking->departure_date) ? \Carbon\Carbon::parse($booking->departure_date)->format('d M Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Departure Time:</th>
                                        <td>
                                            <i class="bi bi-clock me-1"></i> 
                                            {{ $booking->schedule->departure_time ?? $booking->departure_time ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Seat Numbers:</th>
                                        <td>
                                            <div class="seat-badge-list">
                                                @php
                                                    $seats = explode(',', $booking->seat_numbers ?? '');
                                                @endphp
                                                @foreach($seats as $seat)
                                                    @if($seat)
                                                        <span class="seat-badge-item">
                                                            <i class="bi bi-chair"></i> Seat {{ trim($seat) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                                @if(empty($seats) || empty($seats[0]))
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code / Ticket Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="qr-placeholder">
                                <i class="bi bi-qr-code"></i>
                                <p class="mb-0 small text-muted">
                                    <i class="bi bi-envelope-check-fill me-1"></i> 
                                    A digital ticket has been sent to your email
                                </p>
                                <small class="text-muted">Show this ticket on your phone when boarding</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <a href="{{ route('customer.dashboard') }}" class="btn-back">
                                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                                </a>
                                <div class="d-flex gap-2">
                                    <button onclick="window.print()" class="btn-back" style="background: #2196f3;">
                                        <i class="bi bi-printer"></i> Print Ticket
                                    </button>
                                    <a href="#" class="btn-back" style="background: #ff9800;">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-hide success message after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alertMessages = document.querySelectorAll('.alert');
        alertMessages.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
</script>
@endsection