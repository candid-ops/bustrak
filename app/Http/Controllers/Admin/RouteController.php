<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(Request $request)  // Add Request parameter here
    {
        $query = Route::query();
        
        // Search by origin or destination
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('origin', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Order by latest and paginate
        $routes = $query->latest()->paginate(10);
        
        // Preserve filter parameters when paginating
        $routes->appends($request->query());
        
        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.routes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'origin'      => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'fare'        => 'required|numeric|min:1',
            'type'        => 'required|in:city,intercity',
            'distance_km' => 'nullable|numeric|min:1',
            'stops'       => 'nullable|string',
        ]);

        $stops = $request->stops
            ? array_filter(explode(',', $request->stops))
            : [];

        Route::create([
            'origin'      => $request->origin,
            'destination' => $request->destination,
            'stops'       => $stops,
            'distance_km' => $request->distance_km,
            'fare'        => $request->fare,
            'type'        => $request->type,
            'is_active'   => true,
        ]);

        return redirect()->route('admin.routes.index')
                         ->with('success', 'Route added successfully!');
    }

    public function edit(Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route)
    {
        $request->validate([
            'origin'      => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'fare'        => 'required|numeric|min:1',
            'type'        => 'required|in:city,intercity',
            'distance_km' => 'nullable|numeric|min:1',
            'stops'       => 'nullable|string',
        ]);

        $stops = $request->stops
            ? array_filter(explode(',', $request->stops))
            : [];

        $route->update([
            'origin'      => $request->origin,
            'destination' => $request->destination,
            'stops'       => $stops,
            'distance_km' => $request->distance_km,
            'fare'        => $request->fare,
            'type'        => $request->type,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.routes.index')
                         ->with('success', 'Route updated successfully!');
    }

    public function destroy(Route $route)
    {
        // Check if route has any schedules before deleting
        if ($route->schedules()->count() > 0) {
            return redirect()->route('admin.routes.index')
                             ->with('error', 'Cannot delete route with existing schedules!');
        }
        
        $route->delete();
        return redirect()->route('admin.routes.index')
                         ->with('success', 'Route deleted successfully!');
    }
}