<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\BusLocation;
use App\Models\DropPoint;
use App\Models\Trip;

class PassengerController extends Controller
{
    public function getRoutes()
    {
        $routes = Route::where('is_active', true)->get();
        
        return response()->json([
            'message' => 'Routes retrieved successfully',
            'data' => $routes
        ]);
    }

    public function getSchedules(Request $request)
    {
        $query = Schedule::with(['route', 'bus'])
            ->where('status', 'scheduled');

        if ($request->has('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        // Filter by day of week
        $today = strtolower(now()->englishDayOfWeek);
        $query->where('day_of_week', $today);

        $schedules = $query->get();

        return response()->json([
            'message' => 'Schedules retrieved successfully',
            'data' => $schedules
        ]);
    }

    public function getBusLocations(Request $request)
    {
        // Get latest location for each active trip
        $activeTrips = Trip::with(['bus', 'route', 'latestLocation'])
            ->where('status', 'in_progress')
            ->get();

        $locations = $activeTrips->map(function ($trip) {
            return [
                'trip_id' => $trip->id,
                'bus_number' => $trip->bus->bus_number,
                'route_name' => $trip->route->route_name,
                'latitude' => $trip->latestLocation?->latitude,
                'longitude' => $trip->latestLocation?->longitude,
                'speed' => $trip->latestLocation?->speed,
                'heading' => $trip->latestLocation?->heading,
                'last_updated' => $trip->latestLocation?->recorded_at,
            ];
        })->filter(function ($item) {
            return $item['latitude'] !== null;
        })->values();

        return response()->json([
            'message' => 'Bus locations retrieved successfully',
            'data' => $locations
        ]);
    }

    public function requestDropPoint(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $trip = Trip::find($request->trip_id);

        if ($trip->status !== 'in_progress' && $trip->status !== 'loading') {
            return response()->json(['message' => 'Trip is not active'], 400);
        }

        // Determine sequence order
        $maxSequence = $trip->dropPoints()->max('sequence_order') ?? 0;

        $dropPoint = DropPoint::create([
            'trip_id' => $trip->id,
            'passenger_id' => $user->id,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'requested',
            'sequence_order' => $maxSequence + 1,
            'requested_at' => now(),
        ]);

        return response()->json([
            'message' => 'Drop point requested successfully',
            'data' => $dropPoint
        ]);
    }

    public function getMyRequests(Request $request)
    {
        $user = $request->user();

        $requests = DropPoint::where('passenger_id', $user->id)
            ->with(['trip.route', 'trip.bus'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'My requests retrieved successfully',
            'data' => $requests
        ]);
    }
}
