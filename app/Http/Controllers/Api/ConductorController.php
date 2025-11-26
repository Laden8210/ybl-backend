<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\DropPoint;
use Illuminate\Support\Facades\DB;
use App\Models\Route;

class ConductorController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if (!$user->isConductor()) {
            return response()->json(['message' => 'Access denied. Conductor role required.'], 403);
        }

        $currentTrip = Trip::whereHas('busAssignment', function($q) use ($user) {
                $q->where('conductor_id', $user->id);
            })
            ->with(['bus', 'route'])
            ->whereIn('status', ['loading', 'in_progress'])
            ->latest()
            ->first();

        return response()->json([
            'message' => 'Conductor dashboard retrieved successfully',
            'data' => [
                'current_trip' => $currentTrip ? $this->formatTripData($currentTrip) : null,
                'today_stats' => [
                    'trips_count' => Trip::whereHas('busAssignment', function($q) use ($user) {
                            $q->where('conductor_id', $user->id);
                        })
                        ->whereDate('trip_date', today())
                        ->count(),
                ]
            ]
        ]);
    }

    public function getCurrentTrip(Request $request)
    {
        $user = $request->user();

        if (!$user->isConductor()) {
            return response()->json(['message' => 'Access denied. Conductor role required.'], 403);
        }

        $currentTrip = Trip::whereHas('busAssignment', function($q) use ($user) {
                $q->where('conductor_id', $user->id);
            })
            ->with(['bus', 'route'])
            ->whereIn('status', ['loading', 'in_progress'])
            ->latest()
            ->first();

        if (!$currentTrip) {
            return response()->json(['message' => 'No active trip found'], 404);
        }

        return response()->json([
            'message' => 'Current trip retrieved successfully',
            'data' => $this->formatTripData($currentTrip)
        ]);
    }

    public function updatePassengerCount(Request $request)
    {
        $user = $request->user();

        if (!$user->isConductor()) {
            return response()->json(['message' => 'Access denied. Conductor role required.'], 403);
        }

        $request->validate([
            'passenger_count' => 'required|integer|min:0',
        ]);

        $currentTrip = Trip::whereHas('busAssignment', function($q) use ($user) {
                $q->where('conductor_id', $user->id);
            })
            ->with(['bus', 'route'])
            ->whereIn('status', ['loading', 'in_progress'])
            ->latest()
            ->first();

        if (!$currentTrip) {
            return response()->json(['message' => 'No active trip found'], 404);
        }

        $currentTrip->update(['passenger_count' => $request->passenger_count]);

        return response()->json([
            'message' => 'Passenger count updated successfully',
            'data' => ['passenger_count' => $currentTrip->passenger_count]
        ]);
    }

    public function getDropPoints(Request $request)
    {
        $user = $request->user();

        if (!$user->isConductor()) {
            return response()->json(['message' => 'Access denied. Conductor role required.'], 403);
        }

        $currentTrip = Trip::whereHas('busAssignment', function($q) use ($user) {
                $q->where('conductor_id', $user->id);
            })
            ->with(['bus', 'route'])
            ->whereIn('status', ['loading', 'in_progress'])
            ->latest()
            ->first();

        if (!$currentTrip) {
            return response()->json(['message' => 'No active trip found'], 404);
        }

        $dropPoints = $currentTrip->dropPoints()
            ->with('passenger')
            ->orderBy('sequence_order')
            ->get();

        return response()->json([
            'message' => 'Drop points retrieved successfully',
            'data' => $dropPoints
        ]);
    }

    public function addDropPoint(Request $request)
    {
        $user = $request->user();

        if (!$user->isConductor()) {
            return response()->json(['message' => 'Access denied. Conductor role required.'], 403);
        }

        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $route = Route::find($request->route_id);
        
        // Decode the waypoints JSON string to array
        $waypoints = json_decode($route->waypoints, true) ?? [];
        
        // Determine next sequence number
        $nextSequence = count($waypoints) + 1;
        
        // Add new waypoint
        $waypoints[] = [
            'name' => $request->address,
            'latitude' => (string)$request->latitude,
            'longitude' => (string)$request->longitude,
            'sequence' => (string)$nextSequence,
        ];
        
        // Update route with new waypoints (encode back to JSON string)
        $route->update(['waypoints' => json_encode($waypoints)]);

        return response()->json([
            'message' => 'Drop point added successfully',
            'data' => end($waypoints) // Return the newly added waypoint
        ]);
    }

    public function forwardDropPoint(Request $request, DropPoint $dropPoint)
    {
        $user = $request->user();

        if (!$user->isConductor()) {
            return response()->json(['message' => 'Access denied. Conductor role required.'], 403);
        }

        // Verify trip ownership
        $currentTrip = Trip::whereHas('busAssignment', function($q) use ($user) {
                $q->where('conductor_id', $user->id);
            })
            ->with(['bus', 'route'])
            ->whereIn('status', ['loading', 'in_progress'])
            ->latest()
            ->first();

        if (!$currentTrip || $dropPoint->trip_id !== $currentTrip->id) {
            return response()->json(['message' => 'Drop point not found in current trip'], 404);
        }

        $dropPoint->update(['status' => 'forwarded']);

        return response()->json([
            'message' => 'Drop point forwarded to driver',
            'data' => $dropPoint
        ]);
    }

    private function formatTripData(Trip $trip)
    {
        return [
            'id' => $trip->id,
            'trip_date' => $trip->trip_date->toDateString(),
            'status' => $trip->status,
            'passenger_count' => $trip->passenger_count,
            'bus' => [
                'number' => $trip->bus?->bus_number,
                'plate' => $trip->bus?->license_plate,
            ],
            'route' => $trip->route,
        ];
    }
}
