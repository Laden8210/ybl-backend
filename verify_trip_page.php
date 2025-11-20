<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

echo "Starting Trip Management Page Verification...\n";

// 1. Login as Admin
$admin = User::where('role', 'admin')->first();
if (!$admin) {
    $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin_test@example.com']);
}
Auth::login($admin);
echo "Logged in as Admin: " . $admin->email . "\n";

// 2. Verify Trip List Page
echo "Verifying Trip List Page...\n";
$response = $kernel->handle(
    Illuminate\Http\Request::create('/admin/trips', 'GET')
);

if ($response->status() === 200) {
    echo "Trip List Page Status: " . $response->status() . "\n";
    if (strpos($response->getContent(), 'All Trips') !== false) {
        echo "Trip List Page Content Verified.\n";
    } else {
        echo "Trip List Page Content Verification Failed.\n";
        exit(1);
    }
} else {
    echo "Trip List Page Failed with Status: " . $response->status() . "\n";
    echo "Content: " . substr($response->getContent(), 0, 500) . "\n";
    exit(1);
}

// 3. Verify Trip Detail Page
echo "Verifying Trip Detail Page...\n";
$trip = Trip::first();
if ($trip) {
    $response = $kernel->handle(
        Illuminate\Http\Request::create('/admin/trips/' . $trip->id, 'GET')
    );

    if ($response->status() === 200) {
        echo "Trip Detail Page Status: " . $response->status() . "\n";
        if (strpos($response->getContent(), 'Trip Details #' . $trip->id) !== false) {
            echo "Trip Detail Page Content Verified.\n";
        } else {
            echo "Trip Detail Page Content Verification Failed.\n";
            exit(1);
        }
    } else {
        echo "Trip Detail Page Failed with Status: " . $response->status() . "\n";
        exit(1);
    }
} else {
    echo "No trips found to verify detail page.\n";
}

echo "Verification Completed Successfully.\n";
