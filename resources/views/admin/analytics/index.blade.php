@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<style>
    .analytics-container {
        padding: 1.5rem;
    }
    
    .stat-card {
        background: var(--bs-card-bg, white);
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.1));
        transition: all 0.3s;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-icon.revenue { background: linear-gradient(135deg, #4caf50, #2e7d32); color: white; }
    .stat-icon.bookings { background: linear-gradient(135deg, #2196f3, #1565c0); color: white; }
    .stat-icon.buses { background: linear-gradient(135deg, #ff9800, #e65100); color: white; }
    .stat-icon.customers { background: linear-gradient(135deg, #9c27b0, #6a1b9a); color: white; }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: var(--bs-secondary-color, #666);
        font-size: 0.85rem;
    }
    
    .trend-up { color: #4caf50; }
    .trend-down { color: #f44336; }
    
    .chart-card {
        background: var(--bs-card-bg, white);
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.1));
        margin-bottom: 1.5rem;
    }
    
    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .period-selector {
        display: flex;
        gap: 10px;
    }
    
    .period-btn {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        background: var(--bs-tertiary-bg, #f0f0f0);
        color: var(--bs-body-color, #333);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .period-btn.active {
        background: #2196f3;
        color: white;
    }
    
    .period-btn:hover:not(.active) {
        background: var(--bs-secondary-bg, #e0e0e0);
    }
    
    .comparison-card {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        border-radius: 20px;
        padding: 1.2rem;
        text-align: center;
        color: white;
    }
    
    .comparison-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .route-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--bs-border-color, #eee);
    }
    
    .route-name {
        font-weight: 500;
    }
    
    .route-stats {
        text-align: right;
    }
    
    .route-bookings {
        font-size: 0.8rem;
        color: var(--bs-secondary-color, #666);
    }
    
    .progress {
        height: 6px;
        background: var(--bs-tertiary-bg, #e0e0e0);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 5px;
    }
    
    .progress-bar {
        background: #2196f3;
        border-radius: 3px;
    }
    
    @media (max-width: 768px) {
        .analytics-container {
            padding: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>

<div class="analytics-container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="mb-1">Analytics Dashboard</h2>
            <p class="text-muted mb-0">Real-time insights and performance metrics</p>
        </div>
        <div class="period-selector">
            <a href="{{ route('admin.analytics', ['period' => 'week']) }}" 
               class="period-btn {{ $period == 'week' ? 'active' : '' }}">Weekly</a>
            <a href="{{ route('admin.analytics', ['period' => 'month']) }}" 
               class="period-btn {{ $period == 'month' ? 'active' : '' }}">Monthly</a>
            <a href="{{ route('admin.analytics', ['period' => 'year']) }}" 
               class="period-btn {{ $period == 'year' ? 'active' : '' }}">Yearly</a>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-value">KSh {{ number_format($stats['total_revenue'], 0) }}</div>
                <div class="stat-label">Total Revenue</div>
                @if($comparison['revenue']['change'] != 0)
                    <small class="{{ $comparison['revenue']['change'] > 0 ? 'trend-up' : 'trend-down' }}">
                        <i class="bi bi-arrow-{{ $comparison['revenue']['change'] > 0 ? 'up' : 'down' }}"></i>
                        {{ abs($comparison['revenue']['change']) }}% vs previous period
                    </small>
                @endif
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon bookings">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_bookings']) }}</div>
                <div class="stat-label">Total Bookings</div>
                @if($comparison['bookings']['change'] != 0)
                    <small class="{{ $comparison['bookings']['change'] > 0 ? 'trend-up' : 'trend-down' }}">
                        <i class="bi bi-arrow-{{ $comparison['bookings']['change'] > 0 ? 'up' : 'down' }}"></i>
                        {{ abs($comparison['bookings']['change']) }}% vs previous period
                    </small>
                @endif
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon buses">
                    <i class="bi bi-bus-front"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['active_buses']) }}</div>
                <div class="stat-label">Active Buses</div>
                <small>Currently on road</small>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon customers">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
                <div class="stat-label">Active Customers</div>
                <small>Registered users</small>
            </div>
        </div>
    </div>
    
    <!-- Secondary Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Average Ticket Value</div>
                        <div class="stat-value" style="font-size: 1.8rem;">KSh {{ number_format($stats['avg_booking_value'], 0) }}</div>
                    </div>
                    <i class="bi bi-ticket-perforated" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Cancellation Rate</div>
                        <div class="stat-value" style="font-size: 1.8rem;">{{ $stats['cancellation_rate'] }}%</div>
                    </div>
                    <i class="bi bi-x-circle" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Occupancy Rate</div>
                        <div class="stat-value" style="font-size: 1.8rem;">{{ $stats['occupancy_rate'] }}%</div>
                    </div>
                    <i class="bi bi-person-check" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue Chart -->
    <div class="chart-card">
        <div class="chart-title">
            <span><i class="bi bi-graph-up me-2" style="color: #4caf50;"></i>Revenue Overview</span>
        </div>
        <canvas id="revenueChart" height="100"></canvas>
    </div>
    
    <!-- Daily Bookings Chart -->
    <div class="chart-card">
        <div class="chart-title">
            <span><i class="bi bi-bar-chart-steps me-2" style="color: #2196f3;"></i>Booking Trends</span>
        </div>
        <canvas id="bookingsChart" height="100"></canvas>
    </div>
    
    <div class="row g-4 mb-4">
        <!-- Popular Routes -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">
                    <span><i class="bi bi-signpost-2 me-2" style="color: #ff9800;"></i>Most Popular Routes</span>
                </div>
                <div class="popular-routes">
                    @foreach($popularRoutes as $route)
                        <div class="route-item">
                            <div class="route-name">
                                {{ $route->origin }} → {{ $route->destination }}
                            </div>
                            <div class="route-stats">
                                <div>{{ number_format($route->total_bookings) }} bookings</div>
                                <div class="route-bookings">KSh {{ number_format($route->total_revenue, 0) }}</div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ min(100, ($route->total_bookings / $popularRoutes->first()->total_bookings) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Peak Hours -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">
                    <span><i class="bi bi-clock me-2" style="color: #9c27b0;"></i>Peak Booking Times</span>
                </div>
                <canvas id="peakHoursChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Payment Distribution -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">
                    <span><i class="bi bi-credit-card me-2" style="color: #00c864;"></i>Payment Methods</span>
                </div>
                <canvas id="paymentChart" height="200"></canvas>
            </div>
        </div>
        
        <!-- Top Customers -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">
                    <span><i class="bi bi-trophy me-2" style="color: #ff9800;"></i>Top Customers</span>
                </div>
                <div class="top-customers">
                    @foreach($topCustomers as $customer)
                        <div class="route-item">
                            <div class="route-name">
                                {{ $customer->name }}
                                <div class="route-bookings">{{ $customer->email }}</div>
                            </div>
                            <div class="route-stats">
                                <div>{{ $customer->total_bookings }} trips</div>
                                <div class="route-bookings">KSh {{ number_format($customer->total_spent, 0) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Route Performance Table -->
    <div class="chart-card">
        <div class="chart-title">
            <span><i class="bi bi-table me-2"></i>Route Performance Details</span>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Route</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Avg Ticket</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routePerformance as $route)
                        @php
                            $maxRevenue = $routePerformance->max('revenue');
                            $percentage = $maxRevenue > 0 ? ($route->revenue / $maxRevenue) * 100 : 0;
                        @endphp
                        <tr>
                            <td><strong>{{ $route->route_name }}</strong></td>
                            <td>{{ number_format($route->bookings) }}</td>
                            <td>KSh {{ number_format($route->revenue, 0) }}</td>
                            <td>KSh {{ number_format($route->avg_ticket, 0) }}</td>
                            <td style="width: 150px;">
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $percentage }}%; background: #4caf50;"></div>
                                </div>
                                <small class="text-muted">{{ round($percentage) }}% of top route</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueData['labels']) !!},
            datasets: [{
                label: 'Revenue (KSh)',
                data: {!! json_encode($revenueData['revenue']) !!},
                borderColor: '#4caf50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#4caf50',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { 
                    callbacks: {
                        label: function(context) {
                            return 'KSh ' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KSh ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    new Chart(bookingsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dailyBookings['labels']) !!},
            datasets: [{
                label: 'Number of Bookings',
                data: {!! json_encode($dailyBookings['bookings']) !!},
                backgroundColor: '#2196f3',
                borderRadius: 8,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
    
    // Peak Hours Chart
    const peakCtx = document.getElementById('peakHoursChart').getContext('2d');
    new Chart(peakCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($peakHours['labels']) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($peakHours['values']) !!},
                borderColor: '#9c27b0',
                backgroundColor: 'rgba(156, 39, 176, 0.1)',
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#9c27b0'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
    
    // Payment Distribution - Pie Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($paymentDistribution['labels']) !!},
            datasets: [{
                data: {!! json_encode($paymentDistribution['values']) !!},
                backgroundColor: ['#00c864', '#ff9800'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + '%';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection