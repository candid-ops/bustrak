<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class MapController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['bus', 'route'])
                        ->where('status', 'active')
                        ->get();

        // Nairobi to Mombasa route coordinates
        $nairobiMombasa = [
            [-1.2921, 36.8219], [-1.5000, 37.1000],
            [-2.0000, 37.5000], [-2.5000, 38.0000],
            [-3.0000, 38.5000], [-3.5000, 39.0000],
            [-4.0167, 39.6667],
        ];

        // Nairobi CBD to Westlands
        $cbdWestlands = [
            [-1.2833, 36.8167], [-1.2750, 36.8100],
            [-1.2680, 36.8050], [-1.2614, 36.8030],
        ];

        $busRoutes = [];

        foreach ($schedules as $schedule) {
            $coords = str_contains(strtolower($schedule->route->destination), 'mombasa')
                ? $nairobiMombasa
                : $cbdWestlands;

            $busRoutes[] = [
                'id'     => $schedule->bus->id,
                'plate'  => $schedule->bus->plate_number,
                'route'  => $schedule->route->origin . ' → ' . $schedule->route->destination,
                'coords' => $coords,
            ];
        }

        return view('map', compact('busRoutes'));
    }
}