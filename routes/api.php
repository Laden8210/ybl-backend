<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\ConductorController;
use App\Http\Controllers\Api\PassengerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Common routes
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Supervisor specific routes
    Route::prefix('supervisor')->group(function () {
        Route::get('/dashboard', [SupervisorController::class, 'dashboard']);
        Route::get('/buses', [SupervisorController::class, 'getBuses']);
        Route::get('/schedules', [SupervisorController::class, 'getSchedules']);
        Route::get('/bus-locations', [SupervisorController::class, 'getBusLocations']);
        Route::get('/transportation-details/{trip}', [SupervisorController::class, 'getTransportationDetails']);
        Route::post('/confirm-transportation/{trip}', [SupervisorController::class, 'confirmTransportation']);
        Route::get('/trips', [SupervisorController::class, 'getTrips']);
    });

    // Driver specific routes
    Route::prefix('driver')->group(function () {
        Route::get('/dashboard', [DriverController::class, 'dashboard']);
        Route::get('/current-trip', [DriverController::class, 'getCurrentTrip']);

        Route::post('/start-trip', [DriverController::class, 'startTrip']);
        Route::post('/update-trip-location', [DriverController::class, 'updateTripLocation']);
        Route::get('/today-schedule', [DriverController::class, 'getTodaySchedule']);
        Route::post('/complete-trip', [DriverController::class, 'completeTrip']);
        Route::get('/drop-points', [DriverController::class, 'getDropPoints']);
        Route::post('/confirm-drop-point/{dropPoint}', [DriverController::class, 'confirmDropPoint']);
    });

    // Conductor specific routes
    Route::prefix('conductor')->group(function () {
        Route::get('/dashboard', [ConductorController::class, 'dashboard']);
        Route::get('/current-trip', [ConductorController::class, 'getCurrentTrip']);
        Route::post('/update-passenger-count', [ConductorController::class, 'updatePassengerCount']);
        Route::get('/drop-points', [ConductorController::class, 'getDropPoints']);
        Route::post('/add-drop-point', [ConductorController::class, 'addDropPoint']);
        Route::post('/forward-drop-point/{dropPoint}', [ConductorController::class, 'forwardDropPoint']);
    });

    // Passenger specific routes
    Route::prefix('passenger')->group(function () {
        Route::get('/routes', [PassengerController::class, 'getRoutes']);
        Route::get('/schedules', [PassengerController::class, 'getSchedules']);
        Route::get('/bus-locations', [PassengerController::class, 'getBusLocations']);
        Route::post('/request-drop-point', [PassengerController::class, 'requestDropPoint']);
        Route::get('/my-requests', [PassengerController::class, 'getMyRequests']);
    });
});
