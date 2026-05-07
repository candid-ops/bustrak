<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index(Request $request)  // Add Request parameter here
    {
        $query = Bus::query();
        
        // Search filter - by plate number or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Type filter (optional - add if needed)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Order by latest and paginate
        $buses = $query->latest()->paginate(12);
        
        // Preserve filters when paginating
        $buses->appends($request->query());
        
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('admin.buses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|unique:buses',
            'name'         => 'nullable|string',  // Make name optional
            'capacity'     => 'required|integer|min:1|max:100',
            'type'         => 'required|in:city,intercity,luxury,minibus',
            'status'       => 'required|in:active,maintenance,retired',
        ]);

        Bus::create($request->all());
        return redirect()->route('admin.buses.index')
                         ->with('success', 'Bus added successfully!');
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'plate_number' => 'required|unique:buses,plate_number,' . $bus->id,
            'name'         => 'nullable|string',
            'capacity'     => 'required|integer|min:1|max:100',
            'type'         => 'required|in:city,intercity,luxury,minibus',
            'status'       => 'required|in:active,maintenance,retired',
        ]);

        $bus->update($request->all());
        return redirect()->route('admin.buses.index')
                         ->with('success', 'Bus updated successfully!');
    }

    public function destroy(Bus $bus)
    {
        // Prevent deleting if bus has bookings
        if ($bus->schedules()->count() > 0) {
            return redirect()->route('admin.buses.index')
                             ->with('error', 'Cannot delete bus with existing schedules!');
        }
        
        $bus->delete();
        return redirect()->route('admin.buses.index')
                         ->with('success', 'Bus deleted successfully!');
    }
}