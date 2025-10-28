<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

// Public login page (UI only)
Route::view('/login', 'auth.login')->name('login');

// Admin UI routes (UI only for now)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::view('/profile', 'admin.profile')->name('profile');

    // Buses
    Route::view('/buses', 'admin.buses.index')->name('buses.index');
    Route::view('/buses/create', 'admin.buses.create')->name('buses.create');

    // Staff
    Route::view('/staff', 'admin.staff.index')->name('staff.index');
    Route::view('/staff/create', 'admin.staff.create')->name('staff.create');

    // Assignments
    Route::view('/assignments', 'admin.assignments.index')->name('assignments.index');

    // Logs
    Route::view('/logs', 'admin.logs.index')->name('logs.index');

    // Tracking
    Route::view('/tracking', 'admin.tracking.index')->name('tracking.index');
});
