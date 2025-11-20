<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\Trip;
use App\Models\BusAssignment;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\Route;
use Illuminate\Support\Facades\Hash;

echo "Starting Mobile API Verification...\n";

// Helper to make API requests
function apiRequest($method, $uri, $token, $data = []) {
    $headers = [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
    
    // Create a new request instance for each call to avoid state pollution
    $request = Illuminate\Http\Request::create('/api' . $uri, $method, $data);
    $request->headers->add($headers);
    
    // Resolve the kernel from the container again or use the existing one
    global $kernel;
    $response = $kernel->handle($request);
    
    return $response;
}

// 1. Setup Test Data
echo "Setting up test data...\n";
$conductor = User::firstOrCreate(
    ['email' => 'conductor_api_test@example.com'],
    ['name' => 'Conductor Test', 'password' => Hash::make('password'), 'role' => 'conductor', 'phone' => '09123456789']
);
$passenger = User::firstOrCreate(
    ['email' => 'passenger_api_test@example.com'],
    ['name' => 'Passenger Test', 'password' => Hash::make('password'), 'role' => 'passenger', 'phone' => '09987654321']
);

// Create active trip for conductor
$bus = Bus::first();
$route = Route::first();
$schedule = Schedule::first(); // Assuming exists from seeding
if (!$schedule) {
    // Create dummy schedule if none
    $schedule = Schedule::create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'departure_time' => '08:00:00',
        'arrival_time' => '10:00:00',
        'day_of_week' => strtolower(now()->englishDayOfWeek),
        'status' => 'scheduled',
        'effective_date' => now(),
        'is_recurring' => true
    ]);
}

$assignment = BusAssignment::firstOrCreate(
    [
        'bus_id' => $bus->id,
        'assignment_date' => now()->toDateString(),
    ],
    [
        'driver_id' => User::where('role', 'driver')->first()->id, // Assuming driver exists
        'conductor_id' => $conductor->id,
        'status' => 'active'
    ]
);

// Ensure the assignment has the correct conductor (in case it existed with a different one)
$assignment->update(['conductor_id' => $conductor->id]);

$trip = Trip::create([
    'schedule_id' => $schedule->id,
    'bus_assignment_id' => $assignment->id,
    'trip_date' => now(),
    'status' => 'in_progress',
    'passenger_count' => 10
]);

// Get Tokens
$conductorToken = $conductor->createToken('test')->plainTextToken;
$passengerToken = $passenger->createToken('test')->plainTextToken;

// 2. Verify Conductor APIs
echo "Verifying Conductor APIs...\n";

// Dashboard
$response = apiRequest('GET', '/conductor/dashboard', $conductorToken);
echo "Conductor Dashboard: " . $response->status() . "\n";
if ($response->status() !== 200) {
    echo "Error: " . $response->getContent() . "\n";
    exit(1);
}

// Update Passenger Count
$response = apiRequest('POST', '/conductor/update-passenger-count', $conductorToken, ['passenger_count' => 15]);
echo "Update Passenger Count: " . $response->status() . "\n";
if ($response->status() !== 200) exit(1);

// Add Drop Point
$response = apiRequest('POST', '/conductor/add-drop-point', $conductorToken, [
    'address' => 'Test Location',
    'latitude' => 6.11640000,
    'longitude' => 25.17160000
]);
echo "Add Drop Point: " . $response->status() . "\n";
if ($response->status() !== 200) {
    echo "Error: " . substr($response->getContent(), 0, 200) . "\n";
    exit(1);
}
$dropPointId = json_decode($response->getContent())->data->id;

// Forward Drop Point
$response = apiRequest('POST', "/conductor/forward-drop-point/{$dropPointId}", $conductorToken);
echo "Forward Drop Point: " . $response->status() . "\n";
if ($response->status() !== 200) exit(1);


// 3. Verify Passenger APIs
echo "Verifying Passenger APIs...\n";

// Get Routes
$response = apiRequest('GET', '/passenger/routes', $passengerToken);
echo "Get Routes: " . $response->status() . "\n";
if ($response->status() !== 200) {
    echo "Error: " . substr($response->getContent(), 0, 200) . "\n";
    exit(1);
}

// Request Drop Point
$response = apiRequest('POST', '/passenger/request-drop-point', $passengerToken, [
    'trip_id' => $trip->id,
    'address' => 'Passenger Request',
    'latitude' => 6.11650000,
    'longitude' => 25.17170000
]);
echo "Request Drop Point: " . $response->status() . "\n";
if ($response->status() !== 200) {
    echo "Error: " . $response->getContent() . "\n";
    exit(1);
}

echo "Verification Completed Successfully.\n";
