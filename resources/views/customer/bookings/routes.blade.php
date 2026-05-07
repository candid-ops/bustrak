@'
@extends('layouts.app')

@section('title', 'Book a Seat')

@section('content')
<div class="content-card">
    <div class="card-header">
        <i class="bi bi-calendar-plus me-2"></i> Book a Bus Ticket
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('customer.schedules') }}" id="bookingForm">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="route_id" class="form-label">Select Route</label>
                    <select name="route_id" id="route_id" class="form-select" required>
                        <option value="">-- Select Route --</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}">{{ $route->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="travel_date" class="form-label">Travel Date</label>
                    <input type="date" name="travel_date" id="travel_date" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-search"></i> Find Available Buses
                </button>
            </div>
        </form>
    </div>
</div>

<div id="schedulesResult" class="mt-4" style="display: none;">
    <div class="content-card">
        <div class="card-header">
            <i class="bi bi-bus-front"></i> Available Buses
        </div>
        <div class="card-body" id="schedulesList"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('{{ route("customer.schedules") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('schedulesResult');
            const schedulesList = document.getElementById('schedulesList');
            if (data.schedules && data.schedules.length > 0) {
                let html = '<div class="table-responsive"><table class="table"><thead><tr><th>Bus</th><th>Departure Time</th><th>Available Seats</th><th>Fare (KSh)</th><th>Action</th></tr></thead><tbody>';
                data.schedules.forEach(schedule => {
                    html += `<tr><td>${schedule.bus?.plate_number || 'N/A'}</td><td>${schedule.departure_time}</td><td>${schedule.available_seats || 0}</td><td>${schedule.fare || 'N/A'}</td><td><a href="/customer/seats/${schedule.id}" class="btn btn-sm btn-success">Select Seats</a></td></tr>`;
                });
                html += '</tbody></table></div>';
                schedulesList.innerHTML = html;
                resultDiv.style.display = 'block';
            } else {
                schedulesList.innerHTML = '<div class="alert alert-warning">No buses available.</div>';
                resultDiv.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
</script>
@endsection
'@ | Out-File -FilePath resources\views\customer\bookings\index.blade.php -Encoding UTF8