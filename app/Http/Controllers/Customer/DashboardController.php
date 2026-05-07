<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['schedule.route', 'payment'])
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->get();

        return view('customer.dashboard', compact('bookings'));
    }
}