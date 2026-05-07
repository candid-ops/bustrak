@extends('layouts.app')
@section('title', 'Driver Dashboard')

@section('content')
<style>
    .dashboard-card {
        background: var(--bs-card-bg, white);
        border-radius: 16px;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.08));
        transition: all 0.2s ease;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--bs-body-color, #1a1f5e);
    }
    
    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--bs-secondary-color, #6c757d);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--bs-border-color, #e9ecef);
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-size: 0.8rem;
        color: var(--bs-secondary-color, #6c757d);
    }
    
    .info-value {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-active {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .badge-inactive {
        background: #ffebee;
        color: #c62828;
    }
    
    .badge-city {
        background: #e3f2fd;
        color: #1565c0;
    }
    
    .badge-intercity {
        background: #f3e5f5;
        color: #6a1b9a;
    }
    
    .schedule-row {
        transition: all 0.2s;
    }
    
    .schedule-row:hover {
        background: var(--bs-tertiary-bg, #f8f9fa);
    }
    
    @media (max-width: 768px) {
        .stat-number {
            font-size: 1.3rem;
        }
    }
</style>

<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1 fw-bold">
                👋 Welcome, {{ auth()->user()->name }}
            </h4>
            <p class="text-muted small mb-0">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('l, d F Y') }}
            </p>
        </div>
        <a href="{{ route('map') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-map me-2"></i> Live Map
        </a>
    </div>

    <!-- Driver Info Card -->
    <div class="dashboard-card mb-4">
        <div class="p-4">
            <div class="row align-items-center">
                <div class="col-md-auto mb-3 mb-md-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-badge fs-1 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-4">
                            <div class="text-center">
                                <div class="fw-bold">{{ $driver->bus->plate_number ?? '—' }}</div>
                                <div class="text-muted small">Bus Plate</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <div class="fw-bold">{{ $schedules->count() }}</div>
                                <div class="text-muted small">Schedules</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <div class="fw-bold">{{ $driver->licence_number ?? '—' }}</div>
                                <div class="text-muted small">Licence</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $driver->bus->plate_number ?? '—' }}</div>
                        <div class="stat-label">Assigned Bus</div>
                    </div>
                    <i class="bi bi-bus-front fs-2 text-muted opacity-50"></i>
                </div>
                <div class="mt-2 small text-muted">{{ $driver->bus->name ?? 'No bus assigned' }}</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $schedules->count() }}</div>
                        <div class="stat-label">Total Schedules</div>
                    </div>
                    <i class="bi bi-calendar-week fs-2 text-muted opacity-50"></i>
                </div>
                <div class="mt-2 small text-muted">Active routes assigned</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $schedules->where('departure_date', now()->format('Y-m-d'))->count() }}</div>
                        <div class="stat-label">Today's Trips</div>
                    </div>
                    <i class="bi bi-clock-history fs-2 text-muted opacity-50"></i>
                </div>
                <div class="mt-2 small text-muted">Scheduled for today</div>
            </div>
        </div>
    </div>

    <!-- Bus Details -->
    @if($driver->bus)
    <div class="dashboard-card mb-4">
        <div class="p-4">
            <h6 class="mb-3 fw-bold"><i class="bi bi-info-circle me-2"></i>Bus Details</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label">Plate Number</span>
                        <span class="info-value">{{ $driver->bus->plate_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bus Name</span>
                        <span class="info-value">{{ $driver->bus->name ?? '—' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label">Capacity</span>
                        <span class="info-value">{{ $driver->bus->capacity }} seats</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Type</span>
                        <span class="info-value">{{ ucfirst($driver->bus->type) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Schedules Section -->
    <div class="dashboard-card">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-week me-2"></i>My Schedules</h6>
                <span class="badge bg-secondary">{{ $schedules->count() }} routes</span>
            </div>
            
            @if($schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Route</th>
                                <th>Type</th>
                                <th>Departure Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr class="schedule-row">
                                <td>
                                    <span class="fw-medium">
                                        {{ $schedule->route->origin ?? 'N/A' }}
                                        <i class="bi bi-arrow-right mx-1"></i>
                                        {{ $schedule->route->destination ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status {{ $schedule->route->type === 'intercity' ? 'badge-intercity' : 'badge-city' }}">
                                        {{ ucfirst($schedule->route->type ?? 'city') }}
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-clock me-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                                </td>
                                <td>
                                    <span class="badge-status badge-active">
                                        <i class="bi bi-circle-fill" style="font-size: 6px;"></i> Active
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted opacity-50"></i>
                    <h6 class="mt-3">No Schedules Yet</h6>
                    <p class="text-muted small mb-0">No routes have been assigned to you yet.</p>
                    <p class="text-muted small">Please contact your administrator.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection