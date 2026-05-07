<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Schedule;

class DashboardController extends Controller
{
    public function index()
    {
        $driver   = Driver::where('user_id', auth()->id())->with('bus')->first();
        $schedules = Schedule::where('driver_id', $driver?->id)
                        ->with('route')
                        ->where('status', 'active')
                        ->get();

        return view('driver.dashboard', compact('driver', 'schedules'));
    }
}