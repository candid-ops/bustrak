<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Route;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $routes = Route::where('is_active', true)->get();
        return view('customer.bookings.index', compact('routes'));
    }

    public function schedules(Request $request)
    {
        try {
            $request->validate([
                'route_id' => 'required|exists:routes,id',
                'travel_date' => 'required|date|after_or_equal:today',
            ]);

            // Get schedules for the selected route and date
            $schedules = Schedule::where('route_id', $request->route_id)
                ->whereDate('departure_date', $request->travel_date)
                ->where('status', 'active')
                ->with(['bus', 'route'])
                ->get();

            $formattedSchedules = [];

            foreach ($schedules as $schedule) {
                if ($schedule->bus && $schedule->route) {
                    // Count booked seats for this schedule
                    $bookedSeatsCount = Booking::where('schedule_id', $schedule->id)
                        ->where('status', 'confirmed')
                        ->count();
                    
                    // Calculate available seats
                    $availableSeats = $schedule->bus->capacity - $bookedSeatsCount;
                    
                    if ($availableSeats > 0) {
                        $formattedSchedules[] = [
                            'id' => $schedule->id,
                            'bus' => [
                                'id' => $schedule->bus->id,
                                'plate_number' => $schedule->bus->plate_number,
                                'capacity' => $schedule->bus->capacity,
                            ],
                            'departure_time' => $schedule->departure_time,
                            'fare' => (float)$schedule->route->fare,
                            'available_seats' => $availableSeats,
                        ];
                    }
                }
            }

            return response()->json(['schedules' => $formattedSchedules]);

        } catch (\Exception $e) {
            Log::error('Schedule fetch error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch schedules', 'schedules' => []], 500);
        }
    }

    public function seats(Schedule $schedule)
    {
        // Get booked seats for this schedule
        $bookedSeats = Booking::where('schedule_id', $schedule->id)
            ->where('status', 'confirmed')
            ->pluck('seat_number')
            ->toArray();

        // Get fare per seat from the route
        $farePerSeat = $schedule->route->fare;

        return view('customer.bookings.seats', compact('schedule', 'bookedSeats', 'farePerSeat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_numbers' => 'required|string',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        
        // Get seat numbers (can be multiple comma-separated)
        $seatNumbers = explode(',', $request->seat_numbers);
        $seatCount = count($seatNumbers);
        $farePerSeat = $schedule->route->fare;
        $totalAmount = $farePerSeat * $seatCount;
        
        // Generate unique reference
        $reference = 'BKT-' . strtoupper(Str::random(8));
        
        // Debug log to verify calculation
        Log::info('Booking calculation:', [
            'seats' => $seatNumbers,
            'seat_count' => $seatCount,
            'fare_per_seat' => $farePerSeat,
            'total_amount' => $totalAmount
        ]);
        
        // Create booking with ALL seats and total amount
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'schedule_id' => $request->schedule_id,
            'seat_number' => implode(',', $seatNumbers), // Store ALL seats
            'seat_numbers' => $request->seat_numbers,
            'seats_count' => $seatCount,
            'total_amount' => $totalAmount,
            'travel_date' => $schedule->departure_date,
            'status' => 'pending',
            'booking_reference' => $reference,
            'reference' => $reference,
        ]);

        return redirect()->route('customer.payment', $booking)
            ->with('success', 'Booking created! Please complete payment.');
    }

    public function show($id)
    {
        $booking = Booking::with(['schedule.route'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        
        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }
        
        $booking->update(['status' => 'cancelled']);
        
        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }
}