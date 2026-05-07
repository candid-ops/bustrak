@extends('layouts.app')
@section('title', 'Booking Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card content-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-ticket-perforated me-2"></i>Booking Details</span>
                <a href="{{ route('admin.bookings.index') }}"
                   class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8f9ff;">
                            <div class="text-muted small mb-1">Booking Reference</div>
                            <div class="fw-bold fs-5" style="color:#1a1f5e;">
                                {{ $booking->booking_reference }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8f9ff;">
                            <div class="text-muted small mb-1">Status</div>
                            @if($booking->status === 'confirmed')
                                <span class="badge rounded-pill bg-success px-3 py-2">Confirmed</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-2">Pending</span>
                            @else
                                <span class="badge rounded-pill bg-danger px-3 py-2">Cancelled</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Customer</div>
                        <div class="fw-semibold">{{ $booking->user->name }}</div>
                        <div class="text-muted small">{{ $booking->user->email }}</div>
                        <div class="text-muted small">{{ $booking->user->phone }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Route</div>
                        <div class="fw-semibold">
                            {{ $booking->schedule->route->origin }}
                            → {{ $booking->schedule->route->destination }}
                        </div>
                        <div class="text-muted small">
                            Fare: KES {{ number_format($booking->schedule->route->fare) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Travel Date</div>
                        <div class="fw-semibold">
                            {{ \Carbon\Carbon::parse($booking->travel_date)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Departure Time</div>
                        <div class="fw-semibold">
                            {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Seat Number</div>
                        <div class="fw-semibold">Seat {{ $booking->seat_number }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Bus</div>
                        <div class="fw-semibold">{{ $booking->schedule->bus->plate_number }}</div>
                        <div class="text-muted small">{{ $booking->schedule->bus->name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card content-card">
            <div class="card-header">
                <i class="bi bi-cash-stack me-2"></i>Payment Details
            </div>
            <div class="card-body">
                @if($booking->payment)
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Amount</div>
                        <div class="fw-bold fs-5">
                            KES {{ number_format($booking->payment->amount) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">M-Pesa Receipt</div>
                        <div class="fw-semibold" style="color:#1a1f5e;">
                            {{ $booking->payment->mpesa_receipt ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Payment Status</div>
                        @if($booking->payment->status === 'completed')
                            <span class="badge rounded-pill bg-success px-3">Paid</span>
                        @elseif($booking->payment->status === 'pending')
                            <span class="badge rounded-pill bg-warning text-dark px-3">Pending</span>
                        @else
                            <span class="badge rounded-pill bg-danger px-3">Failed</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Phone Used</div>
                        <div class="fw-semibold">{{ $booking->payment->phone }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Paid At</div>
                        <div class="fw-semibold">
                            {{ $booking->payment->paid_at
                                ? \Carbon\Carbon::parse($booking->payment->paid_at)->format('d M Y h:i A')
                                : '—' }}
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-credit-card fs-2 d-block mb-2"></i>
                    No payment record found
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection