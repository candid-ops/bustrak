@extends('layouts.app')

@section('title', 'Select Seats')

@section('content')
<style>
    .seat-container {
        animation: fadeInUp 0.5s ease;
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

    /* Alert Info Box */
    .alert-info-custom {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 1rem 1.2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .alert-info-custom h5 {
        margin-bottom: 0.75rem;
        color: #1565c0;
    }

    .alert-info-custom p {
        margin-bottom: 0.5rem;
        color: #333;
    }

    .alert-info-custom p:last-child {
        margin-bottom: 0;
    }

    /* Seat Map */
    .seat-map {
        background: var(--bs-tertiary-bg, #f8f9fa);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--bs-border-color, #e0e0e0);
        max-height: 450px;
        overflow-y: auto;
    }

    .seat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 12px;
    }

    .seat-item {
        position: relative;
    }

    .seat-checkbox {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .seat-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        background: #4caf50;
        color: white;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .seat-label i {
        margin-right: 4px;
        font-size: 0.8rem;
    }

    .seat-checkbox:checked + .seat-label {
        background: #2196f3;
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(33,150,243,0.3);
    }

    .seat-checkbox:disabled + .seat-label {
        background: #f44336;
        opacity: 0.7;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    .seat-checkbox:disabled + .seat-label:hover {
        transform: none;
    }

    .seat-label:hover {
        transform: translateY(-2px);
        filter: brightness(1.05);
    }

    /* Summary Card */
    .summary-card {
        background: var(--bs-tertiary-bg, #f8f9fa);
        border-radius: 16px;
        padding: 1.2rem;
        border: 1px solid var(--bs-border-color, #e0e0e0);
        position: sticky;
        top: 20px;
    }

    .summary-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--bs-border-color, #ddd);
    }

    .selected-seats-list {
        max-height: 150px;
        overflow-y: auto;
        margin-bottom: 1rem;
    }

    .selected-badge {
        display: inline-block;
        background: #2196f3;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin: 0 4px 8px 0;
    }

    .total-amount {
        font-size: 1.3rem;
        font-weight: 700;
        color: #ff9800;
    }

    .btn-confirm {
        background: #4caf50;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-confirm:hover:not(:disabled) {
        background: #388e3c;
        transform: translateY(-2px);
    }

    .btn-confirm:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Legend */
    .legend {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
    }

    .legend-color {
        width: 24px;
        height: 24px;
        border-radius: 6px;
    }

    .legend-color.available {
        background: #4caf50;
    }

    .legend-color.selected {
        background: #2196f3;
    }

    .legend-color.booked {
        background: #f44336;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .seat-grid {
            grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
            gap: 8px;
        }
        
        .seat-label {
            padding: 6px 8px;
            font-size: 0.75rem;
        }
        
        .summary-card {
            margin-top: 1.5rem;
            position: static;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .alert-info-custom {
            background: rgba(33,150,243,0.15);
        }
        
        .alert-info-custom h5 {
            color: #64b5f6;
        }
        
        .alert-info-custom p {
            color: rgba(255,255,255,0.8);
        }
        
        .seat-label {
            background: #2e7d32;
        }
        
        .seat-checkbox:checked + .seat-label {
            background: #1976d2;
        }
        
        .seat-checkbox:disabled + .seat-label {
            background: #c62828;
        }
        
        .summary-card {
            background: var(--bs-tertiary-bg, #2a2a3e);
        }
    }
</style>

<div class="seat-container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="content-card">
                <div class="card-header">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i> Select Your Seats
                </div>
                <div class="card-body">
                    <!-- Bus Info -->
                    <div class="alert-info-custom">
                        <h5><i class="bi bi-bus-front me-2"></i> {{ $schedule->bus->plate_number ?? 'N/A' }}</h5>
                        <p><i class="bi bi-signpost-2 me-2"></i> {{ $schedule->route->origin ?? 'N/A' }} → {{ $schedule->route->destination ?? 'N/A' }}</p>
                        <p><i class="bi bi-calendar3 me-2"></i> {{ \Carbon\Carbon::parse($schedule->departure_date)->format('d M Y') }} at {{ $schedule->departure_time }}</p>
                        <p class="mb-0"><i class="bi bi-cash-stack me-2"></i> Fare: KSh {{ number_format($farePerSeat, 0) }} per seat</p>
                    </div>

                    <form method="POST" action="{{ route('customer.book.store') }}" id="seatForm">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="seat_numbers" id="seat_numbers">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <label class="fw-semibold mb-2 mb-md-0">
                                        <i class="bi bi-pin-angle-fill me-1"></i> Select Seats
                                    </label>
                                    <div class="legend">
                                        <div class="legend-item">
                                            <div class="legend-color available"></div>
                                            <span>Available</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color selected"></div>
                                            <span>Selected</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color booked"></div>
                                            <span>Booked</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="seat-map">
                                    <div class="seat-grid">
                                        @for($i = 1; $i <= $schedule->bus->capacity; $i++)
                                            @php
                                                $isBooked = in_array($i, $bookedSeats);
                                            @endphp
                                            <div class="seat-item">
                                                <input class="seat-checkbox" 
                                                       type="checkbox" 
                                                       value="{{ $i }}"
                                                       id="seat_{{ $i }}"
                                                       {{ $isBooked ? 'disabled' : '' }}>
                                                <label class="seat-label" for="seat_{{ $i }}">
                                                    <i class="bi bi-chair"></i> {{ $i }}
                                                </label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="summary-card">
                                    <div class="summary-title">
                                        <i class="bi bi-cart-check me-2"></i> Booking Summary
                                    </div>
                                    <div class="selected-seats-list" id="selectedSeatsList">
                                        <p class="text-muted small mb-0" id="noSeatsMsg">No seats selected yet</p>
                                        <div id="selectedSeatsContainer"></div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Seats Selected:</span>
                                        <span><strong id="selectedCount">0</strong></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Total Amount:</span>
                                        <span class="total-amount" id="totalAmount">KSh 0</span>
                                    </div>
                                    <button type="submit" class="btn-confirm mt-3" id="bookBtn" disabled>
                                        <i class="bi bi-check-circle me-2"></i> Confirm Booking
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.seat-checkbox:not(:disabled)');
        const selectedCountSpan = document.getElementById('selectedCount');
        const totalAmountSpan = document.getElementById('totalAmount');
        const bookBtn = document.getElementById('bookBtn');
        const seatNumbersInput = document.getElementById('seat_numbers');
        const selectedSeatsContainer = document.getElementById('selectedSeatsContainer');
        const noSeatsMsg = document.getElementById('noSeatsMsg');
        const farePerSeat = {{ $farePerSeat ?? 0 }};

        function updateSelection() {
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            const selectedSeats = selected.map(cb => parseInt(cb.value)).sort((a,b) => a - b);
            const count = selected.length;
            const total = count * farePerSeat;
            
            // Update counters
            selectedCountSpan.textContent = count;
            totalAmountSpan.textContent = `KSh ${total.toLocaleString()}`;
            seatNumbersInput.value = selectedSeats.join(',');
            bookBtn.disabled = count === 0;
            
            // Update selected seats display
            if (selectedSeats.length > 0) {
                noSeatsMsg.style.display = 'none';
                let seatsHtml = '';
                selectedSeats.forEach(seat => {
                    seatsHtml += `<span class="selected-badge"><i class="bi bi-chair me-1"></i> Seat ${seat}</span>`;
                });
                selectedSeatsContainer.innerHTML = seatsHtml;
            } else {
                noSeatsMsg.style.display = 'block';
                selectedSeatsContainer.innerHTML = '';
            }
            
            // Update individual seat labels styling
            document.querySelectorAll('.seat-label').forEach(label => {
                const checkbox = document.getElementById(label.getAttribute('for'));
                if (checkbox && checkbox.checked) {
                    label.style.background = '#2196f3';
                } else if (checkbox && !checkbox.disabled) {
                    label.style.background = '#4caf50';
                }
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelection);
        });
        
        // Initialize tooltips
        updateSelection();
    });
</script>
@endsection