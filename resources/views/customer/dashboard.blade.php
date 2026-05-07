@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')

{{-- Welcome --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-800 mb-1" style="font-size:1.4rem;">
            Hello, <span style="color:#1a1f5e;">{{ auth()->user()->name }}</span> 👋
        </h4>
        <p class="mb-0" style="color:rgba(0,0,0,.45);font-size:.88rem;">
            {{ now()->format('l, d F Y') }} &nbsp;·&nbsp; Your travel dashboard
        </p>
    </div>
    <a href="{{ route('customer.book') }}" class="btn btn-sm rounded-pill px-4 py-2"
       style="background:linear-gradient(135deg,#1a1f5e,#2d3494);color:#fff;border:none;font-weight:600;">
        <i class="bi bi-plus-lg me-1"></i> Book a Seat
    </a>
</div>

{{-- Hero Banner --}}
<div class="card border-0 mb-4" style="border-radius:20px;background:linear-gradient(135deg,#1a1f5e,#2d3494,#1a6fa8);overflow:hidden;box-shadow:0 8px 32px rgba(26,31,94,.3);">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p style="font-size:.72rem;text-transform:uppercase;letter-spacing:2px;color:rgba(255,255,255,.55);margin-bottom:8px;">Ready for your next journey?</p>
                <h3 class="fw-800 mb-2" style="color:#fff;">Book Intercity & City Buses</h3>
                <p style="color:rgba(255,255,255,.6);font-size:.9rem;margin-bottom:20px;">
                    Real-time seat selection, instant M-Pesa payment, and digital tickets sent to your email.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('customer.book') }}" class="btn btn-sm rounded-pill px-4 py-2" style="background:#fff;color:#1a1f5e;font-weight:700;border:none;">
                        <i class="bi bi-ticket-perforated-fill me-1"></i> Book Now
                    </a>
                    <a href="{{ route('map') }}" class="btn btn-sm rounded-pill px-4 py-2" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);font-weight:600;">
                        <i class="bi bi-geo-alt-fill me-1"></i> Track Bus
                    </a>
                </div>
            </div>
            <div class="col-md-4 text-center d-none d-md-block">
                <div style="font-size:5rem;line-height:1;filter:drop-shadow(0 8px 16px rgba(0,0,0,.3));">🚌</div>
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 h-100" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-uppercase mb-1" style="font-size:.68rem;letter-spacing:1.5px;color:rgba(0,0,0,.4);font-weight:700;">Total Bookings</p>
                        <h2 class="fw-800 mb-0" style="font-size:2.2rem;color:#1a1f5e;">{{ $bookings->count() }}</h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#eef0ff;">
                        <i class="bi bi-ticket-perforated-fill" style="color:#1a1f5e;font-size:1.2rem;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3" style="border-top:1px solid rgba(0,0,0,.06);">
                    <span style="font-size:.75rem;color:rgba(0,0,0,.4);">All time bookings</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 h-100" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-uppercase mb-1" style="font-size:.68rem;letter-spacing:1.5px;color:rgba(0,0,0,.4);font-weight:700;">Confirmed</p>
                        <h2 class="fw-800 mb-0" style="font-size:2.2rem;color:#0d7a45;">{{ $bookings->where('status','confirmed')->count() }}</h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#e8fff3;">
                        <i class="bi bi-check-circle-fill" style="color:#0d7a45;font-size:1.2rem;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3" style="border-top:1px solid rgba(0,0,0,.06);">
                    <span style="font-size:.75rem;color:rgba(0,0,0,.4);">Paid &amp; confirmed seats</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 h-100" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-uppercase mb-1" style="font-size:.68rem;letter-spacing:1.5px;color:rgba(0,0,0,.4);font-weight:700;">Pending Payment</p>
                        <h2 class="fw-800 mb-0" style="font-size:2.2rem;color:#b07800;">{{ $bookings->where('status','pending')->count() }}</h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#fff8e8;">
                        <i class="bi bi-hourglass-split" style="color:#b07800;font-size:1.2rem;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3" style="border-top:1px solid rgba(0,0,0,.06);">
                    <span style="font-size:.75rem;color:rgba(0,0,0,.4);">Awaiting M-Pesa payment</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bookings Table --}}
<div class="card border-0" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h6 class="fw-700 mb-1" style="font-size:.95rem;">My Bookings</h6>
                <p class="mb-0" style="font-size:.75rem;color:rgba(0,0,0,.4);">All your bus ticket bookings</p>
            </div>
            <a href="{{ route('customer.book') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size:.78rem;">
                <i class="bi bi-plus-lg me-1"></i> New Booking
            </a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle" style="font-size:.85rem;">
                <thead>
                    <tr style="border-bottom:2px solid rgba(0,0,0,.06);">
                        <th class="text-uppercase border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:rgba(0,0,0,.4);font-weight:700;">Reference</th>
                        <th class="text-uppercase border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:rgba(0,0,0,.4);font-weight:700;">Route</th>
                        <th class="text-uppercase border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:rgba(0,0,0,.4);font-weight:700;">Travel Date</th>
                        <th class="text-uppercase border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:rgba(0,0,0,.4);font-weight:700;">Seat</th>
                        <th class="text-uppercase border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:rgba(0,0,0,.4);font-weight:700;">Status</th>
                        <th class="text-uppercase border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:rgba(0,0,0,.4);font-weight:700;">Payment</th>
                        <th class="border-0 pb-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr style="border-bottom:1px solid rgba(0,0,0,.04);">
                        <td class="border-0 py-3">
                            <span class="fw-700" style="color:#1a1f5e;font-family:monospace;font-size:.8rem;">{{ $booking->booking_reference }}</span>
                        </td>
                        <td class="border-0 py-3" style="color:rgba(0,0,0,.7);">
                            <i class="bi bi-arrow-right me-1" style="color:#1a1f5e;"></i>
                            {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}
                        </td>
                        <td class="border-0 py-3" style="color:rgba(0,0,0,.6);">
                            <i class="bi bi-calendar3 me-1" style="color:#1a1f5e;"></i>
                            {{ \Carbon\Carbon::parse($booking->travel_date)->format('d M Y') }}
                        </td>
                        <td class="border-0 py-3">
                            <span class="badge rounded-pill px-3" style="background:#f0f2f5;color:rgba(0,0,0,.6);font-size:.72rem;">
                                Seat {{ $booking->seat_number }}
                            </span>
                        </td>
                        <td class="border-0 py-3">
                            @if($booking->status === 'confirmed')
                                <span class="badge rounded-pill px-3" style="background:#e8fff3;color:#0d7a45;font-size:.72rem;">Confirmed</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge rounded-pill px-3" style="background:#fff8e8;color:#b07800;font-size:.72rem;">Pending</span>
                            @else
                                <span class="badge rounded-pill px-3" style="background:#fff0f0;color:#c0392b;font-size:.72rem;">Cancelled</span>
                            @endif
                        </td>
                        <td class="border-0 py-3">
                            @if($booking->payment && $booking->payment->status === 'completed')
                                <span class="badge rounded-pill px-3" style="background:#e8fff3;color:#0d7a45;font-size:.72rem;">Paid</span>
                            @elseif($booking->status !== 'cancelled')
                                <a href="{{ route('customer.payment', $booking->id) }}"
                                   class="btn btn-sm rounded-pill px-3 py-1"
                                   style="background:linear-gradient(135deg,#b07800,#d4940a);color:#fff;border:none;font-size:.72rem;font-weight:600;">
                                    Pay Now
                                </a>
                            @else
                                <span style="color:rgba(0,0,0,.3);font-size:.78rem;">—</span>
                            @endif
                        </td>
                        <td class="border-0 py-3">
                            @if($booking->status !== 'cancelled')
                            <form action="{{ route('customer.booking.cancel', $booking) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Cancel this booking?')">
                                @csrf @method('POST')
                                <button class="btn btn-sm rounded-pill px-2" style="background:#fff0f0;color:#c0392b;border:none;font-size:.72rem;">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 border-0">
                            <div style="font-size:3rem;line-height:1;margin-bottom:12px;">🎫</div>
                            <div style="font-weight:700;font-size:.95rem;color:rgba(0,0,0,.5);margin-bottom:6px;">No bookings yet</div>
                            <div style="font-size:.82rem;color:rgba(0,0,0,.35);margin-bottom:16px;">Book your first bus seat today!</div>
                            <a href="{{ route('customer.book') }}" class="btn btn-sm rounded-pill px-4"
                               style="background:linear-gradient(135deg,#1a1f5e,#2d3494);color:#fff;border:none;font-weight:600;">
                                <i class="bi bi-search me-1"></i> Search Routes
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection