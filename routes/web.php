<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;

Route::get('/', function () {
    return view('landing');
})->name('home');

// Public login page (UI only)
Route::view('/login', 'auth.login')->name('login');

// Admin UI routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::view('/profile', 'admin.profile')->name('profile');

    // Buses
    Route::get('/buses', [BusController::class, 'index'])->name('buses.index');
    Route::get('/buses/create', [BusController::class, 'create'])->name('buses.create');
    Route::post('/buses', [BusController::class, 'store'])->name('buses.store');
    Route::get('/buses/{bus}/edit', [BusController::class, 'edit'])->name('buses.edit');
    Route::put('/buses/{bus}', [BusController::class, 'update'])->name('buses.update');
    Route::delete('/buses/{bus}', [BusController::class, 'destroy'])->name('buses.destroy');
    Route::patch('/buses/{bus}/toggle-status', [BusController::class, 'toggleStatus'])->name('buses.toggle-status');

    // Staff - Updated with controller
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::patch('/staff/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status');

    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::patch('/assignments/{assignment}/toggle-status', [AssignmentController::class, 'toggleStatus'])->name('assignments.toggle-status');

    Route::get('/routes', [RouteController::class, 'index'])->name('routes.index');
    Route::get('/routes/create', [RouteController::class, 'create'])->name('routes.create');
    Route::post('/routes', [RouteController::class, 'store'])->name('routes.store');
    Route::get('/routes/{route}/edit', [RouteController::class, 'edit'])->name('routes.edit');
    Route::put('/routes/{route}', [RouteController::class, 'update'])->name('routes.update');
    Route::delete('/routes/{route}', [RouteController::class, 'destroy'])->name('routes.destroy');
    Route::patch('/routes/{route}/toggle-status', [RouteController::class, 'toggleStatus'])->name('routes.toggle-status');
    Route::get('/routes/{route}', [RouteController::class, 'show'])->name('routes.show');

    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::patch('/schedules/{schedule}/toggle-status', [ScheduleController::class, 'toggleStatus'])->name('schedules.toggle-status');

    // Logs
    Route::view('/logs', 'admin.logs.index')->name('logs.index');

    // Tracking
    Route::view('/tracking', 'admin.tracking.index')->name('tracking.index');
});
