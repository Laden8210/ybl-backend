<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Bus;
use App\Models\DriverIssue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Create a test driver
$driver = User::where('email', 'testdriver@example.com')->first();
if (!$driver) {
    $driver = User::create([
        'name' => 'Test Driver',
        'email' => 'testdriver@example.com',
        'password' => Hash::make('password'),
        'role' => 'driver',
    ]);
}

// Create a test bus
$bus = Bus::first();
if (!$bus) {
    $bus = Bus::create([
        'bus_number' => 'TEST-001',
        'plate_number' => 'ABC-123',
        'capacity' => 50,
        'status' => 'active',
    ]);
}

// Login as driver
Auth::login($driver);

// Create a request to submit an issue
$request = Request::create('/api/driver/issues', 'POST', [
    'type' => 'mechanical',
    'description' => 'Engine overheating',
    'bus_id' => $bus->id,
]);

// Resolve the controller
$controller = new \App\Http\Controllers\Api\DriverIssueController();
$response = $controller->store($request);

echo "Issue Submission Response Status: " . $response->getStatusCode() . "\n";
echo "Issue Submission Response Content: " . $response->getContent() . "\n";

// Check if issue exists in DB
$issue = DriverIssue::where('description', 'Engine overheating')->first();
if ($issue) {
    echo "Issue found in database: ID " . $issue->id . "\n";
} else {
    echo "Issue NOT found in database\n";
}

// Test Dashboard Controller
// Create an admin user
$admin = User::where('email', 'admin@example.com')->first();
if (!$admin) {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
}
Auth::login($admin);

$dashboardController = new \App\Http\Controllers\Admin\DashboardController();
$view = $dashboardController->index();

echo "Dashboard View Name: " . $view->name() . "\n";
echo "Dashboard Data Keys: " . implode(', ', array_keys($view->getData())) . "\n";
