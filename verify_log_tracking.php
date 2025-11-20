<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "Starting Log and Tracking Page Verification...\n";

// 1. Login as Admin
$admin = User::where('role', 'admin')->first();
if (!$admin) {
    $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin_test@example.com']);
}
Auth::login($admin);
echo "Logged in as Admin: " . $admin->email . "\n";

// 2. Verify Logs Page
echo "Verifying Logs Page...\n";
$response = $kernel->handle(
    Illuminate\Http\Request::create('/admin/logs', 'GET')
);

if ($response->status() === 200) {
    echo "Logs Page Status: " . $response->status() . "\n";
    if (strpos($response->getContent(), 'Activity Logs') !== false) {
        echo "Logs Page Content Verified.\n";
    } else {
        echo "Logs Page Content Verification Failed.\n";
        exit(1);
    }
} else {
    echo "Logs Page Failed with Status: " . $response->status() . "\n";
    exit(1);
}

// 3. Verify Tracking Page
echo "Verifying Tracking Page...\n";
$response = $kernel->handle(
    Illuminate\Http\Request::create('/admin/tracking', 'GET')
);

if ($response->status() === 200) {
    echo "Tracking Page Status: " . $response->status() . "\n";
    if (strpos($response->getContent(), 'Live Tracking') !== false) {
        echo "Tracking Page Content Verified.\n";
    } else {
        echo "Tracking Page Content Verification Failed.\n";
        exit(1);
    }
} else {
    echo "Tracking Page Failed with Status: " . $response->status() . "\n";
    exit(1);
}

echo "Verification Completed Successfully.\n";
