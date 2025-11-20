<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Starting Registration and Profile Update Verification...\n";

// Helper to make API requests
function apiRequest($method, $uri, $token = null, $data = []) {
    $headers = [
        'Accept' => 'application/json',
    ];
    
    if ($token) {
        $headers['Authorization'] = 'Bearer ' . $token;
    }
    
    $request = Illuminate\Http\Request::create('/api' . $uri, $method, $data);
    $request->headers->add($headers);
    
    global $kernel;
    $response = $kernel->handle($request);
    
    return $response;
}

// 1. Test Registration
echo "Testing Registration...\n";
$email = 'newpassenger_' . time() . '@example.com';
$response = apiRequest('POST', '/register', null, [
    'name' => 'New Passenger',
    'email' => $email,
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'phone' => '09123456789',
    'address' => '123 Test Street'
]);

echo "Registration Status: " . $response->status() . "\n";
if ($response->status() !== 201) {
    echo "Error: " . substr($response->getContent(), 0, 200) . "\n";
    exit(1);
}

$registrationData = json_decode($response->getContent());
$token = $registrationData->data->token;
$userId = $registrationData->data->user->id;
echo "User registered with ID: $userId\n";

// 2. Test Profile Update (without password)
echo "Testing Profile Update (basic info)...\n";
$response = apiRequest('PUT', '/profile', $token, [
    'name' => 'Updated Name',
    'phone' => '09987654321',
    'address' => '456 New Address'
]);

echo "Profile Update Status: " . $response->status() . "\n";
if ($response->status() !== 200) {
    echo "Error: " . substr($response->getContent(), 0, 200) . "\n";
    exit(1);
}

// 3. Test Profile Update (with password)
echo "Testing Profile Update (with password)...\n";
$response = apiRequest('PUT', '/profile', $token, [
    'current_password' => 'password123',
    'new_password' => 'newpassword123',
    'new_password_confirmation' => 'newpassword123'
]);

echo "Password Update Status: " . $response->status() . "\n";
if ($response->status() !== 200) {
    echo "Error: " . substr($response->getContent(), 0, 200) . "\n";
    exit(1);
}

// 4. Test Profile Update (wrong current password)
echo "Testing Profile Update (wrong password - should fail)...\n";
$response = apiRequest('PUT', '/profile', $token, [
    'current_password' => 'wrongpassword',
    'new_password' => 'anotherpassword',
    'new_password_confirmation' => 'anotherpassword'
]);

echo "Wrong Password Status: " . $response->status() . "\n";
if ($response->status() !== 422) {
    echo "Error: Expected 422, got " . $response->status() . "\n";
    exit(1);
}

// 5. Test Registration with duplicate email
echo "Testing Registration (duplicate email - should fail)...\n";
$response = apiRequest('POST', '/register', null, [
    'name' => 'Another User',
    'email' => $email,
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'phone' => '09111111111'
]);

echo "Duplicate Email Status: " . $response->status() . "\n";
if ($response->status() !== 422) {
    echo "Error: Expected 422, got " . $response->status() . "\n";
    exit(1);
}

echo "Verification Completed Successfully.\n";
