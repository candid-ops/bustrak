@extends('layouts.app')

@section('title', 'Book a Seat')

@section('content')
<style>
    .booking-container {
        animation: fadeInUp 0.5s ease;
        padding: 2rem 0;
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

    .search-card {
        background: var(--bs-card-bg, white);
        border-radius: 20px;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.1));
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .search-header {
        background: linear-gradient(135deg, #2196f3, #1565c0);
        padding: 1.5rem;
        color: white;
        text-align: center;
    }

    .form-control-custom,
    .form-select-custom {
        background: var(--bs-tertiary-bg, #f8f9fa);
        border: 1px solid var(--bs-border-color, #ddd);
        border-radius: 10px;
        padding: 10px 14px;
        color: var(--bs-body-color, #333);
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        border-color: #2196f3;
        box-shadow: 0 0 0 3px rgba(33,150,243,0.1);
        outline: none;
    }

    label {
        font-weight: 500;
        margin-bottom: 6px;
        color: var(--bs-body-color, #555);
        font-size: 0.9rem;
    }

    .btn-search {
        background: #2196f3;
        border: none;
        border-radius: 50px;
        padding: 10px 32px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        cursor: pointer;
    }

    .btn-search:hover {
        background: #1976d2;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(33,150,243,0.3);
    }

    /* Bus Card Styles */
    .bus-card {
        background: var(--bs-card-bg, white);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--bs-border-color, #eee);
        transition: all 0.2s ease;
    }

    .bus-card:hover {
        border-color: #2196f3;
        box-shadow: 0 2px 8px rgba(33,150,243,0.1);
    }

    .bus-icon {
        width: 50px;
        height: 50px;
        background: #e3f2fd;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #2196f3;
    }

    .bus-details {
        flex: 1;
    }

    .bus-plate {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 4px;
    }

    .departure-time {
        font-size: 0.8rem;
        color: var(--bs-secondary-color, #666);
    }

    .seat-badge {
        background: #e8f5e9;
        border: 1px solid #4caf50;
        border-radius: 50px;
        padding: 4px 12px;
        font-size: 0.75rem;
        color: #2e7d32;
        display: inline-block;
    }

    .fare-badge {
        font-size: 1.2rem;
        font-weight: 700;
        color: #ff9800;
    }

    .btn-select {
        background: #4caf50;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        transition: all 0.2s ease;
        color: white;
        font-weight: 500;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-select:hover {
        background: #388e3c;
    }

    .loading-spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .no-results {
        text-align: center;
        padding: 3rem;
        background: var(--bs-tertiary-bg, #f8f9fa);
        border-radius: 12px;
    }

    .result-count {
        background: #2196f3;
        color: white;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-block;
    }

    /* Responsive styles */
    .bus-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
    }

    .bus-col-icon {
        flex-shrink: 0;
    }

    .bus-col-details {
        flex: 2;
        min-width: 150px;
    }

    .bus-col-seats {
        flex-shrink: 0;
        min-width: 100px;
    }

    .bus-col-fare {
        flex-shrink: 0;
        min-width: 90px;
    }

    .bus-col-action {
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .bus-row {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .bus-col-action {
            width: 100%;
        }
        
        .btn-select {
            width: 100%;
            text-align: center;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .bus-icon {
            background: rgba(33,150,243,0.15);
        }
        
        .seat-badge {
            background: rgba(76,175,80,0.15);
            color: #81c784;
        }
        
        .no-results {
            background: var(--bs-tertiary-bg, #2a2a3e);
        }
        
        .form-control-custom,
        .form-select-custom {
            background: var(--bs-tertiary-bg, #2a2a3e);
            color: var(--bs-body-color, #fff);
        }
    }
</style>

<div class="booking-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Search Card -->
            <div class="search-card">
                <div class="search-header">
                    <i class="bi bi-calendar-plus fs-2"></i>
                    <h3 class="mt-2 mb-0">Book Your Journey</h3>
                    <p class="mb-0 opacity-75">Smart travel starts here</p>
                </div>

                <div class="p-4">
                    <form method="POST" action="{{ route('customer.schedules') }}" id="bookingForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label><i class="bi bi-signpost-2 me-1"></i> Select Route</label>
                                <select name="route_id" id="route_id" class="form-select-custom" required>
                                    <option value="">-- Choose Route --</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}">
                                            {{ $route->origin }} → {{ $route->destination }} 
                                            (KSh {{ number_format($route->fare, 0) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label><i class="bi bi-calendar3 me-1"></i> Travel Date</label>
                                <input type="date" name="travel_date" id="travel_date" 
                                       class="form-control-custom" 
                                       required min="{{ date('Y-m-d') }}" 
                                       value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-search" id="searchBtn">
                                <i class="bi bi-search me-1"></i> Find Available Buses
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Section -->
            <div id="schedulesResult" style="display: none;">
                <div class="mt-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <h5 class="mb-0">
                            <i class="bi bi-bus-front-fill me-2" style="color: #2196f3;"></i>
                            Available Buses
                        </h5>
                        <span class="result-count" id="resultCount"></span>
                    </div>
                    <div id="schedulesList"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const searchBtn = document.getElementById('searchBtn');
        const originalText = searchBtn.innerHTML;
        
        searchBtn.innerHTML = '<span class="loading-spinner"></span> Searching...';
        searchBtn.disabled = true;
        
        const formData = new FormData(this);
        const routeSelect = document.getElementById('route_id');
        const routeName = routeSelect.options[routeSelect.selectedIndex]?.text || 'selected route';
        const travelDate = document.getElementById('travel_date').value;
        
        fetch('{{ route("customer.schedules") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('schedulesResult');
            const schedulesList = document.getElementById('schedulesList');
            const resultCount = document.getElementById('resultCount');
            
            if (data.schedules && data.schedules.length > 0) {
                let html = '';
                
                data.schedules.forEach((schedule) => {
                    const busPlate = schedule.bus?.plate_number || 'N/A';
                    const departureTime = schedule.departure_time || '08:00';
                    const availableSeats = schedule.available_seats || 0;
                    const fare = schedule.fare || 0;
                    
                    html += `
                        <div class="bus-card">
                            <div class="bus-row">
                                <div class="bus-col-icon">
                                    <div class="bus-icon">
                                        <i class="bi bi-bus-front-fill"></i>
                                    </div>
                                </div>
                                <div class="bus-col-details">
                                    <div class="bus-plate">${busPlate}</div>
                                    <div class="departure-time">
                                        <i class="bi bi-clock me-1"></i> Departure: ${departureTime}
                                    </div>
                                </div>
                                <div class="bus-col-seats">
                                    <span class="seat-badge">
                                        <i class="bi bi-person-check me-1"></i> ${availableSeats} seats left
                                    </span>
                                </div>
                                <div class="bus-col-fare">
                                    <span class="fare-badge">KSh ${fare.toLocaleString()}</span>
                                </div>
                                <div class="bus-col-action">
                                    <a href="/customer/seats/${schedule.id}" class="btn-select">
                                        <i class="bi bi-check-circle me-1"></i> Select Seats
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                schedulesList.innerHTML = html;
                resultCount.textContent = `${data.schedules.length} bus${data.schedules.length > 1 ? 'es' : ''}`;
                resultDiv.style.display = 'block';
                
                resultDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
            } else {
                schedulesList.innerHTML = `
                    <div class="no-results">
                        <i class="bi bi-bus-front fs-1" style="color: #ff9800;"></i>
                        <h5 class="mt-3">No buses available</h5>
                        <p>No buses found for ${routeName} on ${travelDate}.</p>
                        <p class="small text-muted mt-2">Try selecting a different date or route.</p>
                    </div>
                `;
                resultCount.textContent = '0 buses';
                resultDiv.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('schedulesList').innerHTML = `
                <div class="no-results">
                    <i class="bi bi-exclamation-triangle-fill fs-1" style="color: #f44336;"></i>
                    <h5 class="mt-3">Something went wrong</h5>
                    <p>Please try again later.</p>
                </div>
            `;
            document.getElementById('schedulesResult').style.display = 'block';
        })
        .finally(() => {
            searchBtn.innerHTML = originalText;
            searchBtn.disabled = false;
        });
    });
</script>
@endsection