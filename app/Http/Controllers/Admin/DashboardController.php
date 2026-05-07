<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuses     = Bus::count();
        $totalDrivers   = Driver::count();
        $totalRoutes    = Route::count();
        $totalBookings  = Booking::count();
        $totalRevenue   = Payment::where('status', 'completed')->sum('amount');
        $recentBookings = Booking::with(['user', 'schedule.route'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalBuses',
            'totalDrivers',
            'totalRoutes',
            'totalBookings',
            'totalRevenue',
            'recentBookings'
        ));
    }
}