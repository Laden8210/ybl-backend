<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        // Get active trips with their latest location
        $activeTrips = Trip::with(['bus', 'driver', 'route', 'latestLocation'])
            ->where('status', 'in_progress')
            ->get();

        return view('admin.tracking.index', compact('activeTrips'));
    }
}
