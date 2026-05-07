<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index(Request $request)  // Add Request parameter here
    {
        $query = Driver::with(['user', 'bus']);
        
        // Search filter - by name, phone, or licence number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('phone', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('licence_number', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Sort and paginate
        $drivers = $query->latest()->paginate(12);
        
        // Preserve filters when paginating
        $drivers->appends($request->query());
        
        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        $buses = Bus::where('status', 'active')->get();
        return view('admin.drivers.create', compact('buses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'required|string|max:15',
            'password'       => 'required|min:6|confirmed',
            'licence_number' => 'required|string|unique:drivers,licence_number',
            'bus_id'         => 'nullable|exists:buses,id',
        ]);

        // Create user account
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        
        // Assign driver role
        $user->assignRole('driver');

        // Create driver profile
        Driver::create([
            'user_id'        => $user->id,
            'bus_id'         => $request->bus_id,
            'licence_number' => $request->licence_number,
            'status'         => 'active',
        ]);

        return redirect()->route('admin.drivers.index')
                         ->with('success', 'Driver added successfully!');
    }

    public function edit(Driver $driver)
    {
        $buses = Bus::where('status', 'active')->get();
        return view('admin.drivers.edit', compact('driver', 'buses'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:15',
            'licence_number' => 'required|string|unique:drivers,licence_number,' . $driver->id,
            'bus_id'         => 'nullable|exists:buses,id',
            'status'         => 'required|in:active,inactive',
        ]);

        // Update user
        $driver->user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        // Update driver
        $driver->update([
            'bus_id'         => $request->bus_id,
            'licence_number' => $request->licence_number,
            'status'         => $request->status,
        ]);

        return redirect()->route('admin.drivers.index')
                         ->with('success', 'Driver updated successfully!');
    }

    public function destroy(Driver $driver)
    {
        // Check if driver has any active bookings/schedules
        if ($driver->bus && $driver->bus->schedules()->where('departure_date', '>=', now())->exists()) {
            return redirect()->route('admin.drivers.index')
                             ->with('error', 'Cannot delete driver with upcoming schedules!');
        }
        
        // Delete the user (cascade will delete driver)
        $driver->user->delete();
        
        return redirect()->route('admin.drivers.index')
                         ->with('success', 'Driver deleted successfully!');
    }
}