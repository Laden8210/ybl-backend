<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\DropPoint;
use App\Models\BusLocation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\BusAssignment;

class DriverController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        $currentTrip = Trip::where('bus_assignment_id', $user->driverAssignments->first()->id)
            ->whereIn('status', ['in_progress', 'loading'])
            ->first();  

        return response()->json([
            'message' => 'Driver dashboard retrieved successfully',
            'data' => [
                'current_trip' => $currentTrip ? $this->formatTripData($currentTrip) : null,
                'today_trips' => $user->drivenTrips()
                    ->whereDate('trip_date', Carbon::today())
                    ->count(),
                'assigned_bus' => $user->current_bus ? [
                    'id' => $user->current_bus->id,
                    'bus_number' => $user->current_bus->bus_number,
                    'license_plate' => $user->current_bus->license_plate,
                ] : null,
            ]
        ]);
    }

    public function getCurrentTrip(Request $request)
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        $currentTrip = $user->currentTrip;

        if (!$currentTrip) {
            return response()->json([
                'message' => 'No active trip found'
            ], 404);
        }

        return response()->json([
            'message' => 'Current trip retrieved successfully',
            'data' => $this->formatTripData($currentTrip)
        ]);
    }

    public function startTrip(Request $request)
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $currentTrip = Trip::find($request->trip_id);


        if (!$currentTrip) {
            return response()->json([
                'message' => 'No scheduled trip found'
            ], 404);
        }

        $currentTrip->startTrip($request->latitude, $request->longitude);

        return response()->json([
            'message' => 'Trip started successfully',
            'data' => $this->formatTripData($currentTrip)
        ]);
    }

    public function updateTripLocation(Request $request)
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

        $currentTrip = Trip::find($request->trip_id);

        if (!$currentTrip) {
            return response()->json([
                'message' => 'No scheduled trip found'
            ], 404);
        }

        // Record bus location
        BusLocation::create([
            'trip_id' => $currentTrip->id,
            'bus_id' => $currentTrip->schedule->bus_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed,
            'heading' => $request->heading,
            'recorded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Location updated successfully'
        ]);
    }

    public function createTrip(Request $request)
    {

        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'bus_id' => 'required|exists:buses,id',
        ]);

        $busAssignment = BusAssignment::where('bus_id', $request->bus_id)->first();

        // check first if there is exist and not yet completed trip
        $existingTrip = Trip::where('bus_assignment_id', $busAssignment->id)
            ->where('status', '!=', 'completed')
            ->first();

        if ($existingTrip) {
            return response()->json([
                'message' => 'Bus is already assigned to another trip',
                'data' => $existingTrip
            ], 400);
        }

        $trip = Trip::create([
            'schedule_id' => $request->schedule_id,
            'bus_assignment_id' => $busAssignment->id,
            'trip_date' => now()->format('Y-m-d'),
            'actual_departure_time' => null,
            'actual_arrival_time' => null,
            'passenger_count' => 0,
            'start_latitude' => null,
            'start_longitude' => null,
            'end_latitude' => null,
            'end_longitude' => null,
            'status' => 'loading',
            'notes' => null,
        ]);

        $updateScheduleStatus = Schedule::find($request->schedule_id);
        $updateScheduleStatus->update([
            'status' => 'in_progress',
        ]);

        return response()->json([
            'message' => 'Trip created successfully',
            'data' => $trip
        ]);

    }

    public function completeTrip(Request $request)
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $currentTrip = Trip::find($request->trip_id);

        if (!$currentTrip || $currentTrip->status !== 'in_progress') {
            return response()->json([
                'message' => 'No active trip in progress'
            ], 404);
        }

        $currentTrip->completeTrip($request->latitude, $request->longitude);

        $updateScheduleStatus = Schedule::find($currentTrip->schedule_id);
        $updateScheduleStatus->update([
            'status' => 'completed',
        ]);

        return response()->json([
            'message' => 'Trip completed successfully',
            'data' => $this->formatTripData($currentTrip)
        ]);
    }

    public function getDropPoints(Request $request)
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        $currentTrip = $user->currentTrip;

        if (!$currentTrip) {
            return response()->json([
                'message' => 'No active trip found'
            ], 404);
        }

        $dropPoints = $currentTrip->dropPoints()
            ->with('passenger')
            ->orderBy('sequence_order')
            ->get();

        return response()->json([
            'message' => 'Drop points retrieved successfully',
            'data' => $dropPoints->map(function ($dropPoint) {
                return [
                    'id' => $dropPoint->id,
                    'passenger_name' => $dropPoint->passenger->name,
                    'address' => $dropPoint->address,
                    'latitude' => $dropPoint->latitude,
                    'longitude' => $dropPoint->longitude,
                    'sequence_order' => $dropPoint->sequence_order,
                    'status' => $dropPoint->status,
                    'requested_at' => $dropPoint->requested_at->toISOString(),
                ];
            })
        ]);
    }

    public function confirmDropPoint(Request $request, DropPoint $dropPoint)
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        // Verify that the drop point belongs to driver's current trip
        $currentTrip = $user->currentTrip;

        if (!$currentTrip || $dropPoint->trip_id !== $currentTrip->id) {
            return response()->json([
                'message' => 'Drop point not found in current trip'
            ], 404);
        }

        if ($dropPoint->status !== 'forwarded') {
            return response()->json([
                'message' => 'Drop point is not ready for confirmation'
            ], 400);
        }

        $dropPoint->markAsConfirmed();

        return response()->json([
            'message' => 'Drop point confirmed successfully',
            'data' => [
                'id' => $dropPoint->id,
                'status' => $dropPoint->status,
                'confirmed_at' => $dropPoint->confirmed_at->toISOString(),
            ]
        ]);
    }

    private function formatTripData(Trip $trip)
    {
        return [
            'id' => $trip->id,
            'trip_date' => $trip->trip_date->toDateString(),
            'status' => $trip->status,
            'passenger_count' => $trip->passenger_count,
            'actual_departure_time' => $trip->actual_departure_time?->format('H:i'),
            'actual_arrival_time' => $trip->actual_arrival_time?->format('H:i'),
            'route' => [
                'route_name' => $trip->schedule->route->route_name,
                'start_point' => $trip->schedule->route->start_point,
                'end_point' => $trip->schedule->route->end_point,
                'start_time' => $trip->schedule->start_time,
                'end_time' => $trip->schedule->end_time,
                'start_latitude' => $trip->schedule->route->start_latitude,
                'start_longitude' => $trip->schedule->route->start_longitude,
                'end_latitude' => $trip->schedule->route->end_latitude,
                'end_longitude' => $trip->schedule->route->end_longitude,
                'waypoints' => $trip->schedule->route->waypoints,
            ],
        ];
    }
    public function getTodaySchedule(Request $request)
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json([
                'message' => 'Access denied. Driver role required.'
            ], 403);
        }

        $assignment = BusAssignment::where('driver_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'No bus assignment found for today'
            ], 404);
        }


        $today = today();
        $dayOfWeek = strtolower($today->englishDayOfWeek);

        $schedule = Schedule::where('bus_id', $assignment->bus_id)
            ->where('day_of_week', $dayOfWeek)
            ->where(function ($query) use ($today) {
                $query->where('is_recurring', true)
                    ->orWhereDate('effective_date', '<=', $today)
                    ->where(function ($q) use ($today) {
                        $q->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', $today);
                    });
            })
            ->with(['bus', 'route'])
            ->get();

        if (!$schedule) {
            return response()->json([
                'message' => 'No scheduled trip found for today'
            ], 404);
        }

        return response()->json([
            'message' => 'Today schedule retrieved successfully',
            'data' => [
                'schedule' => $schedule,
            ]
        ]);
    }
}
