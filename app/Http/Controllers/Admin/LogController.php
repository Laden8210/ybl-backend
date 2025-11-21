<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\User;
use App\Models\Trip;
use App\Models\DriverIssue;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index()
    {
        $activities = collect();
        
        // Get recent trips
        $recentTrips = Trip::with(['bus', 'driver', 'route'])
            ->latest()
            ->take(20)
            ->get();
        
        // Add trips to activity collection
        foreach($recentTrips as $trip) {
            $activities->push([
                'type' => 'trip',
                'icon' => 'bi-play-circle',
                'color' => 'success',
                'title' => 'Bus ' . ($trip->bus->bus_number ?? 'Unknown'),
                'description' => 'Started route ' . ($trip->route->name ?? 'Unknown') . ' - Driver: ' . ($trip->driver->name ?? 'Unknown'),
                'time' => $trip->created_at->diffForHumans(),
                'timestamp' => $trip->created_at,
            ]);
        }

        // Get recent issues
        $recentIssues = DriverIssue::with(['driver', 'bus'])
            ->latest()
            ->take(20)
            ->get();
        
        // Add issues to activity collection
        foreach($recentIssues as $issue) {
            $activities->push([
                'type' => 'issue',
                'icon' => 'bi-exclamation-triangle',
                'color' => 'danger',
                'title' => 'Driver ' . ($issue->driver->name ?? 'Unknown'),
                'description' => 'Reported issue: ' . $issue->type . ($issue->bus ? ' (Bus: ' . $issue->bus->bus_number . ')' : ''),
                'time' => $issue->created_at->diffForHumans(),
                'timestamp' => $issue->created_at,
            ]);
        }

        // Sort activities by timestamp (newest first)
        $activities = $activities->sortByDesc('timestamp')->values();

        // Paginate the collection manually
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $activities = new \Illuminate\Pagination\LengthAwarePaginator(
            $activities->forPage($currentPage, $perPage),
            $activities->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.logs.index', compact('activities'));
    }
}