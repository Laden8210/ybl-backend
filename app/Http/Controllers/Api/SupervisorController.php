<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\Trip;
use App\Models\BusLocation;
use App\Models\BusAssignment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SupervisorController extends Controller
{
    /**
     * Get supervisor dashboard data
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if (!$user->isSupervisor()) {
            return response()->json([
                'message' => 'Access denied. Supervisor role required.'
            ], 403);
        }

        $today = Carbon::today();

        // Get today's active trips
        $activeTrips = Trip::with(['bus', 'schedule.route', 'busAssignment.driver', 'busAssignment.conductor'])
            ->whereDate('trip_date', $today)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->get();

        // Get today's schedules
        $todaySchedules = Schedule::with(['bus', 'route'])
            ->where('day_of_week', strtolower($today->englishDayOfWeek))
            ->where('status', 'scheduled')
            ->get();

        // Get active buses with their latest locations
        $activeBuses = Bus::where('status', 'active')
            ->with(['latestLocation'])
            ->get();

        return response()->json([
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'active_trips_count' => $activeTrips->count(),
                'today_schedules_count' => $todaySchedules->count(),
                'active_buses_count' => $activeBuses->count(),
                'active_trips' => $activeTrips->map(function ($trip) {
                    return $this->formatTripData($trip);
                }),
                'today_schedules' => $todaySchedules->map(function ($schedule) {
                    return $this->formatScheduleData($schedule);
                }),
            ]
        ]);
    }

    /**
     * Get all buses with their status and assignments
     */
    public function getBuses(Request $request)
    {
        $user = $request->user();

        if (!$user->isSupervisor()) {
            return response()->json([
                'message' => 'Access denied. Supervisor role required.'
            ], 403);
        }

        $buses = Bus::with(['currentAssignment.driver', 'currentAssignment.conductor', 'latestLocation'])
            ->orderBy('bus_number')
            ->get();

        return response()->json([
            'message' => 'Buses retrieved successfully',
            'data' => $buses->map(function ($bus) {
                return [
                    'id' => $bus->id,
                    'bus_number' => $bus->bus_number,
                    'license_plate' => $bus->license_plate,
                    'model' => $bus->model,
                    'capacity' => $bus->capacity,
                    'status' => $bus->status,
                    'current_assignment' => $bus->currentAssignment ? [
                        'driver' => $bus->currentAssignment->driver ? [
                            'id' => $bus->currentAssignment->driver->id,
                            'name' => $bus->currentAssignment->driver->name,
                            'phone' => $bus->currentAssignment->driver->phone,
                        ] : null,
                        'conductor' => $bus->currentAssignment->conductor ? [
                            'id' => $bus->currentAssignment->conductor->id,
                            'name' => $bus->currentAssignment->conductor->name,
                            'phone' => $bus->currentAssignment->conductor->phone,
                        ] : null,
                    ] : null,
                    'latest_location' => $bus->latestLocation ? [
                        'latitude' => $bus->latestLocation->latitude,
                        'longitude' => $bus->latestLocation->longitude,
                        'recorded_at' => $bus->latestLocation->recorded_at->toISOString(),
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * Get bus schedules with departure times
     */
    public function getSchedules(Request $request)
    {
        $user = $request->user();

        if (!$user->isSupervisor()) {
            return response()->json([
                'message' => 'Access denied. Supervisor role required.'
            ], 403);
        }

        $schedules = Schedule::with(['bus', 'route'])
            ->where('status', 'scheduled')
            ->orderBy('day_of_week')
            ->orderBy('departure_time')
            ->get();

        return response()->json([
            'message' => 'Schedules retrieved successfully',
            'data' => $schedules->map(function ($schedule) {
                return $this->formatScheduleData($schedule);
            })
        ]);
    }

    /**
     * Get real-time bus locations
     */
    public function getBusLocations(Request $request)
    {
        $user = $request->user();

        if (!$user->isSupervisor()) {
            return response()->json([
                'message' => 'Access denied. Supervisor role required.'
            ], 403);
        }

        // Get bus locations from the last 5 minutes
        $recentLocations = BusLocation::with(['bus', 'trip.schedule.route'])
            ->where('recorded_at', '>=', Carbon::now()->subMinutes(5))
            ->orderBy('recorded_at', 'desc')
            ->get()
            ->groupBy('bus_id')
            ->map(function ($locations) {
                // Get the latest location for each bus
                return $locations->first();
            });

        return response()->json([
            'message' => 'Bus locations retrieved successfully',
            'data' => $recentLocations->map(function ($location) {
                return [
                    'bus' => [
                        'id' => $location->bus->id,
                        'bus_number' => $location->bus->bus_number,
                        'license_plate' => $location->bus->license_plate,
                    ],
                    'trip' => $location->trip ? [
                        'id' => $location->trip->id,
                        'status' => $location->trip->status,
                        'route_name' => $location->trip->schedule->route->route_name,
                    ] : null,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'speed' => $location->speed,
                    'heading' => $location->heading,
                    'recorded_at' => $location->recorded_at->toISOString(),
                ];
            })->values()
        ]);
    }

    /**
     * Get transportation details for a specific trip
     */
    public function getTransportationDetails(Request $request, Trip $trip)
    {
        $user = $request->user();

        if (!$user->isSupervisor()) {
            return response()->json([
                'message' => 'Access denied. Supervisor role required.'
            ], 403);
        }

        $trip->load([
            'bus',
            'schedule.route',
            'busAssignment.driver',
            'busAssignment.conductor',
            'dropPoints.passenger',
            'locations' => function ($query) {
                $query->orderBy('recorded_at', 'desc')->limit(10);
            }
        ]);

        return response()->json([
            'message' => 'Transportation details retrieved successfully',
            'data' => [
                'trip' => $this->formatTripData($trip),
                'bus_details' => [
                    'id' => $trip->bus->id,
                    'bus_number' => $trip->bus->bus_number,
                    'license_plate' => $trip->bus->license_plate,
                    'model' => $trip->bus->model,
                    'capacity' => $trip->bus->capacity,
                    'features' => $trip->bus->features ? json_decode($trip->bus->features, true) : [],
                ],
                'assigned_staff' => [
                    'driver' => $trip->busAssignment->driver ? [
                        'id' => $trip->busAssignment->driver->id,
                        'name' => $trip->busAssignment->driver->name,
                        'phone' => $trip->busAssignment->driver->phone,
                        'license_number' => $trip->busAssignment->driver->license_number,
                    ] : null,
                    'conductor' => $trip->busAssignment->conductor ? [
                        'id' => $trip->busAssignment->conductor->id,
                        'name' => $trip->busAssignment->conductor->name,
                        'phone' => $trip->busAssignment->conductor->phone,
                        'employee_id' => $trip->busAssignment->conductor->employee_id,
                    ] : null,
                ],
                'passenger_info' => [
                    'current_count' => $trip->passenger_count,
                    'capacity' => $trip->bus->capacity,
                    'drop_points' => $trip->dropPoints->map(function ($dropPoint) {
                        return [
                            'id' => $dropPoint->id,
                            'passenger_name' => $dropPoint->passenger->name,
                            'address' => $dropPoint->address,
                            'status' => $dropPoint->status,
                            'requested_at' => $dropPoint->requested_at->toISOString(),
                        ];
                    }),
                ],
                'route_details' => [
                    'route_name' => $trip->schedule->route->route_name,
                    'start_point' => $trip->schedule->route->start_point,
                    'end_point' => $trip->schedule->route->end_point,
                    'distance' => $trip->schedule->route->distance,
                    'estimated_duration' => $trip->schedule->route->estimated_duration,
                ],
                'recent_locations' => $trip->locations->map(function ($location) {
                    return [
                        'latitude' => $location->latitude,
                        'longitude' => $location->longitude,
                        'speed' => $location->speed,
                        'recorded_at' => $location->recorded_at->toISOString(),
                    ];
                }),
            ]
        ]);
    }

    /**
     * Confirm transportation information
     */
    public function confirmTransportation(Request $request, Trip $trip)
    {
        $user = $request->user();

        if (!$user->isSupervisor()) {
            return response()->json([
                'message' => 'Access denied. Supervisor role required.'
            ], 403);
        }

        $request->validate([
            'confirmed_details' => 'required|array',
            'notes' => 'nullable|string|max:500',
        ]);

        // Create a bus log for the confirmation
        $trip->bus->logs()->create([
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'action' => 'transportation_confirmed',
            'description' => 'Transportation details verified and confirmed by supervisor',
            'metadata' => json_encode([
                'confirmed_details' => $request->confirmed_details,
                'notes' => $request->notes,
                'confirmed_at' => now()->toISOString(),
            ]),
            'log_time' => now(),
        ]);

        return response()->json([
            'message' => 'Transportation information confirmed successfully',
            'data' => [
                'trip_id' => $trip->id,
                'confirmed_by' => $user->name,
                'confirmed_at' => now()->toISOString(),
            ]
        ]);
    }

    /**
     * Get all trips with filtering options
     */
    public function getTrips(Request $request)
    {
        $user = $request->user();

        if (!$user->isSupervisor()) {
            return response()->json([
                'message' => 'Access denied. Supervisor role required.'
            ], 403);
        }

        $query = Trip::with(['bus', 'schedule.route', 'busAssignment.driver', 'busAssignment.conductor']);

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('trip_date', $request->date);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by bus
        if ($request->has('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        $trips = $query->orderBy('trip_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Trips retrieved successfully',
            'data' => $trips->map(function ($trip) {
                return $this->formatTripData($trip);
            })
        ]);
    }

    /**
     * Format trip data for API response
     */
    private function formatTripData(Trip $trip)
    {
        return [
            'id' => $trip->id,
            'trip_date' => $trip->trip_date->toDateString(),
            'status' => $trip->status,
            'passenger_count' => $trip->passenger_count,
            'actual_departure_time' => $trip->actual_departure_time?->format('H:i'),
            'actual_arrival_time' => $trip->actual_arrival_time?->format('H:i'),
            'bus' => [
                'id' => $trip->bus->id,
                'bus_number' => $trip->bus->bus_number,
                'license_plate' => $trip->bus->license_plate,
            ],
            'route' => [
                'id' => $trip->schedule->route->id,
                'route_name' => $trip->schedule->route->route_name,
                'start_point' => $trip->schedule->route->start_point,
                'end_point' => $trip->schedule->route->end_point,
            ],
            'schedule' => [
                'departure_time' => $trip->schedule->departure_time->format('H:i'),
                'arrival_time' => $trip->schedule->arrival_time->format('H:i'),
                'day_of_week' => $trip->schedule->day_of_week,
            ],
            'staff' => [
                'driver' => $trip->busAssignment->driver ? [
                    'name' => $trip->busAssignment->driver->name,
                    'phone' => $trip->busAssignment->driver->phone,
                ] : null,
                'conductor' => $trip->busAssignment->conductor ? [
                    'name' => $trip->busAssignment->conductor->name,
                    'phone' => $trip->busAssignment->conductor->phone,
                ] : null,
            ],
        ];
    }

    /**
     * Format schedule data for API response
     */
    private function formatScheduleData(Schedule $schedule)
    {
        return [
            'id' => $schedule->id,
            'departure_time' => $schedule->departure_time->format('H:i'),
            'arrival_time' => $schedule->arrival_time->format('H:i'),
            'day_of_week' => $schedule->day_of_week,
            'is_recurring' => $schedule->is_recurring,
            'status' => $schedule->status,
            'bus' => [
                'id' => $schedule->bus->id,
                'bus_number' => $schedule->bus->bus_number,
                'license_plate' => $schedule->bus->license_plate,
                'capacity' => $schedule->bus->capacity,
            ],
            'route' => [
                'id' => $schedule->route->id,
                'route_name' => $schedule->route->route_name,
                'start_point' => $schedule->route->start_point,
                'end_point' => $schedule->route->end_point,
                'distance' => $schedule->route->distance,
                'estimated_duration' => $schedule->route->estimated_duration,
            ],
        ];
    }
}
