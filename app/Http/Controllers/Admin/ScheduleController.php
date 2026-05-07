<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Route;
use App\Models\Bus;
use App\Models\Driver;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['route', 'bus', 'driver.user'])->latest()->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $routes  = Route::where('is_active', true)->get();
        $buses   = Bus::where('status', 'active')->get();
        $drivers = Driver::with('user')->where('status', 'active')->get();
        return view('admin.schedules.create', compact('routes', 'buses', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_id'       => 'required|exists:routes,id',
            'bus_id'         => 'required|exists:buses,id',
            'driver_id'      => 'required|exists:drivers,id',
            'departure_time' => 'required',
            'days'           => 'required|array',
        ]);

        Schedule::create([
            'route_id'       => $request->route_id,
            'bus_id'         => $request->bus_id,
            'driver_id'      => $request->driver_id,
            'departure_time' => $request->departure_time,
            'days'           => $request->days,
            'status'         => 'active',
        ]);

        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Schedule added successfully!');
    }

    public function edit(Schedule $schedule)
    {
        $routes  = Route::where('is_active', true)->get();
        $buses   = Bus::where('status', 'active')->get();
        $drivers = Driver::with('user')->where('status', 'active')->get();
        return view('admin.schedules.edit', compact('schedule', 'routes', 'buses', 'drivers'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'route_id'       => 'required|exists:routes,id',
            'bus_id'         => 'required|exists:buses,id',
            'driver_id'      => 'required|exists:drivers,id',
            'departure_time' => 'required',
            'days'           => 'required|array',
            'status'         => 'required|in:active,cancelled',
        ]);

        $schedule->update($request->all());
        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Schedule updated successfully!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Schedule deleted successfully!');
    }
}