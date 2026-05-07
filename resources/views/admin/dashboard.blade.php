@extends('layouts.app')
@section('title', 'Command Center')

@section('content')
<style>
    .fw-800 { font-weight: 800; }
    .card { transition: transform 0.2s, box-shadow 0.2s; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .stat-card { cursor: pointer; }
    
    @media (prefers-color-scheme: dark) {
        .card {
            background: var(--bs-card-bg, #1e1e2e) !important;
        }
        .table {
            color: var(--bs-body-color, #fff);
        }
        .badge {
            background: rgba(255,255,255,0.1);
        }
    }
</style>

{{-- Welcome Bar --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-800 mb-1" style="font-size:1.4rem;">
            Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
            <span style="color:#1a1f5e;">{{ auth()->user()->name }}</span> 👋
        </h4>
        <p class="mb-0 text-muted" style="font-size:.88rem;">
            {{ now()->format('l, d F Y') }} &nbsp;·&nbsp; Fleet Command Overview
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('map') }}" class="btn btn-sm rounded-pill px-3" style="background:linear-gradient(135deg,#1a1f5e,#2d3494);color:#fff;border:none;">
            <i class="bi bi-geo-alt-fill me-1"></i> Live Map
        </a>
        <a href="{{ route('admin.analytics') }}" class="btn btn-sm rounded-pill px-3" style="background:linear-gradient(135deg,#ff9800,#e65100);color:#fff;border:none;">
            <i class="bi bi-graph-up me-1"></i> Analytics
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-ticket-perforated me-1"></i> All Bookings
        </a>
    </div>
</div>

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    @php
        $kpis = [
            ['label'=>'Total Buses','value'=>$totalBuses,'icon'=>'bi-bus-front-fill','color'=>'#1a1f5e','bg'=>'#eef0ff','sub'=>'Fleet active','trend'=>'+2 this month'],
            ['label'=>'Total Drivers','value'=>$totalDrivers,'icon'=>'bi-person-badge-fill','color'=>'#0d7a45','bg'=>'#e8fff3','sub'=>'On duty','trend'=>'+1 this month'],
            ['label'=>'Total Routes','value'=>$totalRoutes,'icon'=>'bi-signpost-2-fill','color'=>'#7b3fc4','bg'=>'#f3eeff','sub'=>'Operational','trend'=>'Active'],
            ['label'=>'Total Bookings','value'=>$totalBookings,'icon'=>'bi-ticket-perforated-fill','color'=>'#b07800','bg'=>'#fff8e8','sub'=>'All time','trend'=> number_format(\App\Models\Booking::whereMonth('created_at', now()->month)->count()) . ' this month'],
        ];
    @endphp
    @foreach($kpis as $k)
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 h-100 stat-card" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);overflow:hidden;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-uppercase mb-1" style="font-size:.68rem;letter-spacing:1.5px;color:var(--bs-secondary-color, rgba(0,0,0,.4));font-weight:700;">{{ $k['label'] }}</p>
                        <h2 class="fw-800 mb-0" style="font-size:2rem;color:{{ $k['color'] }};font-weight:800;">{{ number_format($k['value']) }}</h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:{{ $k['bg'] }};">
                        <i class="bi {{ $k['icon'] }}" style="color:{{ $k['color'] }};font-size:1.2rem;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3" style="border-top:1px solid var(--bs-border-color, rgba(0,0,0,.06));">
                    <span style="font-size:.75rem;color:var(--bs-secondary-color, rgba(0,0,0,.4));">
                        <i class="bi bi-circle-fill me-1" style="color:#28a745;font-size:7px;"></i>
                        {{ $k['sub'] }}
                    </span>
                    <span class="ms-2" style="font-size:.7rem;">
                        <i class="bi bi-arrow-up-short"></i> {{ $k['trend'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Revenue Card --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0" style="border-radius:16px;background:linear-gradient(135deg,#1a1f5e 0%,#2d3494 50%,#1a6fa8 100%);box-shadow:0 8px 32px rgba(26,31,94,.3);overflow:hidden;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-uppercase mb-1" style="font-size:.68rem;letter-spacing:2px;color:rgba(255,255,255,.6);font-weight:700;">Total M-Pesa Revenue</p>
                        <h1 class="fw-800 mb-1" style="font-size:2.8rem;color:#fff;font-weight:800;">KES {{ number_format($totalRevenue) }}</h1>
                        <p style="color:rgba(255,255,255,.6);font-size:.85rem;margin:0;">
                            <i class="bi bi-arrow-up-right me-1" style="color:#00c864;"></i>
                            Collected via M-Pesa Daraja API
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="d-inline-flex gap-4">
                            <div>
                                <div style="font-size:1.4rem;font-weight:800;color:#fff;">{{ \App\Models\Booking::where('status','confirmed')->count() }}</div>
                                <div style="font-size:.72rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:1px;">Confirmed</div>
                            </div>
                            <div style="width:1px;background:rgba(255,255,255,.15);"></div>
                            <div>
                                <div style="font-size:1.4rem;font-weight:800;color:#ffc107;">{{ \App\Models\Booking::where('status','pending')->count() }}</div>
                                <div style="font-size:.72rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:1px;">Pending</div>
                            </div>
                            <div style="width:1px;background:rgba(255,255,255,.15);"></div>
                            <div>
                                <div style="font-size:1.4rem;font-weight:800;color:#ff6b6b;">{{ \App\Models\Booking::where('status','cancelled')->count() }}</div>
                                <div style="font-size:.72rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:1px;">Cancelled</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Table + Right Column --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 h-100" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center px-4 pt-4 pb-0 border-0">
                <div>
                    <h6 class="fw-700 mb-0" style="font-size:.95rem;">Recent Bookings</h6>
                    <p class="mb-0 mt-1 text-muted" style="font-size:.75rem;">Latest ticket purchases</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size:.78rem;">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body px-4 pb-4 pt-3">
                <div class="table-responsive">
                    <table class="table align-middle" style="font-size:.85rem;">
                        <thead>
                            <tr style="border-bottom:2px solid var(--bs-border-color, rgba(0,0,0,.06));">
                                <th class="text-uppercase fw-700 border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:var(--bs-secondary-color, rgba(0,0,0,.4));">Reference</th>
                                <th class="text-uppercase fw-700 border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:var(--bs-secondary-color, rgba(0,0,0,.4));">Customer</th>
                                <th class="text-uppercase fw-700 border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:var(--bs-secondary-color, rgba(0,0,0,.4));">Route</th>
                                <th class="text-uppercase fw-700 border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:var(--bs-secondary-color, rgba(0,0,0,.4));">Status</th>
                                <th class="text-uppercase fw-700 border-0 pb-3" style="font-size:.68rem;letter-spacing:1px;color:var(--bs-secondary-color, rgba(0,0,0,.4));">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                            <tr style="border-bottom:1px solid var(--bs-border-color, rgba(0,0,0,.04));">
                                <td class="border-0 py-3">
                                    <span class="fw-700" style="color:#1a1f5e;font-family:monospace;font-size:.82rem;">
                                        {{ $booking->booking_reference ?? $booking->reference ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="border-0 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-700"
                                             style="width:30px;height:30px;background:#eef0ff;color:#1a1f5e;font-size:.75rem;flex-shrink:0;">
                                            {{ strtoupper(substr($booking->user->name ?? 'U',0,1)) }}
                                        </div>
                                        <span>{{ $booking->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="border-0 py-3 text-muted">
                                    <i class="bi bi-arrow-right me-1" style="color:#1a1f5e;"></i>
                                    {{ $booking->schedule->route->origin ?? 'N/A' }} → {{ $booking->schedule->route->destination ?? 'N/A' }}
                                </td>
                                <td class="border-0 py-3">
                                    @if($booking->status === 'confirmed')
                                        <span class="badge rounded-pill px-3 py-1" style="background:#e8fff3;color:#0d7a45;font-size:.72rem;">Confirmed</span>
                                    @elseif($booking->status === 'pending')
                                        <span class="badge rounded-pill px-3 py-1" style="background:#fff8e8;color:#b07800;font-size:.72rem;">Pending</span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-1" style="background:#fff0f0;color:#c0392b;font-size:.72rem;">Cancelled</span>
                                    @endif
                                </td>
                                <td class="border-0 py-3 text-muted" style="font-size:.75rem;">
                                    {{ $booking->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 border-0">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                    <span class="text-muted" style="font-size:.85rem;">No bookings yet</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Chart --}}
        <div class="card border-0 mb-3" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
            <div class="card-body p-4">
                <h6 class="fw-700 mb-1" style="font-size:.95rem;">Booking Status Distribution</h6>
                <p class="mb-3 text-muted" style="font-size:.75rem;">Current distribution of all bookings</p>
                <canvas id="bookingChart" height="200"></canvas>
                <div class="row g-2 mt-3">
                    <div class="col-4 text-center">
                        <div class="fw-800" style="color:#0d7a45;font-size:1.1rem;">{{ \App\Models\Booking::where('status','confirmed')->count() }}</div>
                        <div style="font-size:.65rem;color:var(--bs-secondary-color, rgba(0,0,0,.4));text-transform:uppercase;letter-spacing:.5px;">Confirmed</div>
                        <div class="progress mt-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $totalBookings > 0 ? (\App\Models\Booking::where('status','confirmed')->count() / $totalBookings) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="fw-800" style="color:#b07800;font-size:1.1rem;">{{ \App\Models\Booking::where('status','pending')->count() }}</div>
                        <div style="font-size:.65rem;color:var(--bs-secondary-color, rgba(0,0,0,.4));text-transform:uppercase;letter-spacing:.5px;">Pending</div>
                        <div class="progress mt-1" style="height: 3px;">
                            <div class="progress-bar bg-warning" style="width: {{ $totalBookings > 0 ? (\App\Models\Booking::where('status','pending')->count() / $totalBookings) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="fw-800" style="color:#c0392b;font-size:1.1rem;">{{ \App\Models\Booking::where('status','cancelled')->count() }}</div>
                        <div style="font-size:.65rem;color:var(--bs-secondary-color, rgba(0,0,0,.4));text-transform:uppercase;letter-spacing:.5px;">Cancelled</div>
                        <div class="progress mt-1" style="height: 3px;">
                            <div class="progress-bar bg-danger" style="width: {{ $totalBookings > 0 ? (\App\Models\Booking::where('status','cancelled')->count() / $totalBookings) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="card border-0 mb-3" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
            <div class="card-body p-4">
                <h6 class="fw-700 mb-3" style="font-size:.95rem;">Quick Stats</h6>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Today's Bookings</span>
                    <span class="fw-700">{{ \App\Models\Booking::whereDate('created_at', today())->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">This Week's Revenue</span>
                    <span class="fw-700 text-success">KES {{ number_format(\App\Models\Booking::where('status','confirmed')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount'), 0) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Average Daily Bookings</span>
                    <span class="fw-700">{{ round(\App\Models\Booking::whereMonth('created_at', now()->month)->count() / max(now()->daysInMonth, 1), 1) }}</span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card border-0" style="border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
            <div class="card-body p-4">
                <h6 class="fw-700 mb-3" style="font-size:.95rem;">Quick Actions</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.buses.create') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:#eef0ff;transition:all .2s;"
                       onmouseover="this.style.background='#dde0ff'" onmouseout="this.style.background='#eef0ff'">
                        <div class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#1a1f5e;flex-shrink:0;">
                            <i class="bi bi-bus-front-fill text-white" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.82rem;color:#1a1f5e;">Add New Bus</div>
                            <div style="font-size:.7rem;color:rgba(0,0,0,.45);">Register a vehicle</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#1a1f5e;font-size:.75rem;"></i>
                    </a>
                    <a href="{{ route('admin.drivers.create') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:#e8fff3;transition:all .2s;"
                       onmouseover="this.style.background='#c8ffe3'" onmouseout="this.style.background='#e8fff3'">
                        <div class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#0d7a45;flex-shrink:0;">
                            <i class="bi bi-person-plus-fill text-white" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.82rem;color:#0d7a45;">Add Driver</div>
                            <div style="font-size:.7rem;color:rgba(0,0,0,.45);">Onboard a driver</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#0d7a45;font-size:.75rem;"></i>
                    </a>
                    <a href="{{ route('admin.routes.create') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:#f3eeff;transition:all .2s;"
                       onmouseover="this.style.background='#e8deff'" onmouseout="this.style.background='#f3eeff'">
                        <div class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#7b3fc4;flex-shrink:0;">
                            <i class="bi bi-signpost-2-fill text-white" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.82rem;color:#7b3fc4;">Add Route</div>
                            <div style="font-size:.7rem;color:rgba(0,0,0,.45);">Create a new route</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#7b3fc4;font-size:.75rem;"></i>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:#fff8e8;transition:all .2s;"
                       onmouseover="this.style.background='#fff0cc'" onmouseout="this.style.background='#fff8e8'">
                        <div class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#ff9800;flex-shrink:0;">
                            <i class="bi bi-graph-up text-white" style="font-size:.85rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.82rem;color:#e65100;">View Analytics</div>
                            <div style="font-size:.7rem;color:rgba(0,0,0,.45);">Revenue reports & charts</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#e65100;font-size:.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('bookingChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Confirmed', 'Pending', 'Cancelled'],
            datasets: [{
                data: [
                    {{ \App\Models\Booking::where('status','confirmed')->count() }},
                    {{ \App\Models\Booking::where('status','pending')->count() }},
                    {{ \App\Models\Booking::where('status','cancelled')->count() }},
                ],
                backgroundColor: ['#0d7a45','#b07800','#c0392b'],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            cutout: '70%',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = {{ $totalBookings }};
                            const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${context.raw} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection