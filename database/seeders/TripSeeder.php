<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\User;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\BusAssignment;
use Carbon\Carbon;

class TripSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have some base data
        $buses = Bus::all();
        if ($buses->isEmpty()) {
            $buses = Bus::factory(5)->create();
        }

        $drivers = User::where('role', 'driver')->get();
        if ($drivers->isEmpty()) {
            // Create some dummy drivers if none exist
            for ($i = 0; $i < 5; $i++) {
                User::create([
                    'name' => 'Driver ' . ($i + 1),
                    'email' => 'driver' . ($i + 1) . '@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'driver',
                ]);
            }
            $drivers = User::where('role', 'driver')->get();
        }

        $routes = Route::all();
        if ($routes->isEmpty()) {
            // Create a dummy route
            $route = Route::create([
                'name' => 'Main Route',
                'origin' => 'City Center',
                'destination' => 'Suburb',
                'distance' => 15.5,
                'estimated_duration' => 45,
            ]);
            $routes = collect([$route]);
        }

        // Create schedules and assignments if needed (simplified for this seeder)
        // We will just create trips directly and assume relationships exist or are nullable/mocked for now
        // But Trip model requires schedule_id and bus_assignment_id.
        // Let's create a generic schedule and assignment.
        
        $route = $routes->first();
        $bus = $buses->first();
        $schedule = Schedule::firstOrCreate([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_time' => '08:00:00',
            'arrival_time' => '09:00:00',
            'day_of_week' => strtolower(now()->englishDayOfWeek),
            'effective_date' => today(),
            'end_date' => today()->addYear(),
            'is_recurring' => true,
            'status' => 'scheduled',
        ]);
        $driver = $drivers->first();
        
        $conductors = User::where('role', 'conductor')->get();
        if ($conductors->isEmpty()) {
            for ($i = 0; $i < 5; $i++) {
                User::create([
                    'name' => 'Conductor ' . ($i + 1),
                    'email' => 'conductor' . ($i + 1) . '@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'conductor',
                ]);
            }
            $conductors = User::where('role', 'conductor')->get();
        }
        $conductor = $conductors->first();

        $assignment = BusAssignment::firstOrCreate([
            'bus_id' => $bus->id,
            'driver_id' => $driver->id,
            'conductor_id' => $conductor->id,
            'assignment_date' => today(),
            'status' => 'active',
        ]);

        // Generate trips for today across different hours
        $hours = [6, 8, 10, 12, 14, 16, 18];
        
        foreach ($hours as $hour) {
            // Random number of completed trips
            $completedCount = rand(5, 20);
            for ($i = 0; $i < $completedCount; $i++) {
                Trip::create([
                    'schedule_id' => $schedule->id,
                    'bus_assignment_id' => $assignment->id,
                    'trip_date' => today(),
                    'actual_departure_time' => Carbon::today()->setHour($hour)->setMinute(rand(0, 59)),
                    'actual_arrival_time' => Carbon::today()->setHour($hour + 1)->setMinute(rand(0, 59)),
                    'status' => 'completed',
                ]);
            }

            // Random number of in-progress trips (only for current/recent hours usually, but for chart demo we scatter them)
            $inProgressCount = rand(2, 8);
            for ($i = 0; $i < $inProgressCount; $i++) {
                Trip::create([
                    'schedule_id' => $schedule->id,
                    'bus_assignment_id' => $assignment->id,
                    'trip_date' => today(),
                    'actual_departure_time' => Carbon::today()->setHour($hour)->setMinute(rand(0, 59)),
                    'status' => 'in_progress',
                ]);
            }
        }
    }
}
