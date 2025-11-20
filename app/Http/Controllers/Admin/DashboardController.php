<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\User;
use App\Models\Trip;
use App\Models\DriverIssue;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI Stats
        $activeBuses = Bus::where('status', 'active')->count();
        $totalBuses = Bus::count();
        $activeBusesPercentage = $totalBuses > 0 ? round(($activeBuses / $totalBuses) * 100) : 0;

        $registeredStaff = User::where('role', '!=', 'admin')->count(); // Assuming 'admin' is the role for admins
        // For active staff today, we might need a login log or schedule check. 
        // For now, let's assume active staff are those with a schedule today.
        // This is a placeholder logic for active staff.
        $activeStaffToday = 0; 

        $tripsToday = Trip::whereDate('created_at', Carbon::today())->count();
        $completedTripsToday = Trip::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->count();
        $tripsPercentage = 100; // Placeholder as we don't have a "target" trips count
        
        $openIssues = DriverIssue::where('status', 'open')->count();
        $resolvedIssuesToday = DriverIssue::where('status', 'resolved')
            ->whereDate('resolved_at', Carbon::today())
            ->count();

        // Recent Trips
        $recentTrips = Trip::with(['bus', 'driver', 'route'])
            ->latest()
            ->take(5)
            ->get();

        // Open Alerts (Issues)
        $alerts = DriverIssue::where('status', 'open')
            ->latest()
            ->take(5)
            ->get();

        // Activity Feed (Placeholder logic - could be from logs)
        // For now, we'll pass empty or mock if needed, but let's try to get some real data if possible.
        // We can use the latest created trips and issues as activity.
        $activities = collect();
        
        // Add recent trips to activity
        foreach($recentTrips as $trip) {
            $activities->push([
                'type' => 'trip',
                'icon' => 'bi-play-circle',
                'color' => 'success',
                'title' => 'Bus ' . ($trip->bus->bus_number ?? 'Unknown'),
                'description' => 'Started route ' . ($trip->route->name ?? 'Unknown'),
                'time' => $trip->created_at->diffForHumans(),
                'timestamp' => $trip->created_at,
            ]);
        }

        // Add recent issues to activity
        foreach($alerts as $issue) {
            $activities->push([
                'type' => 'issue',
                'icon' => 'bi-exclamation-triangle',
                'color' => 'danger',
                'title' => 'Driver ' . ($issue->driver->name ?? 'Unknown'),
                'description' => 'Reported issue: ' . $issue->type,
                'time' => $issue->created_at->diffForHumans(),
                'timestamp' => $issue->created_at,
            ]);
        }

        // Chart Data
        $hours = ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00'];
        $chartLabels = $hours;
        $chartCompletedData = [];
        $chartInProgressData = [];

        foreach ($hours as $hour) {
            $startTime = Carbon::createFromFormat('H:i', $hour);
            $endTime = $startTime->copy()->addHours(2);

            $completedCount = Trip::whereDate('created_at', Carbon::today())
                ->where('status', 'completed')
                ->whereTime('actual_departure_time', '>=', $startTime)
                ->whereTime('actual_departure_time', '<', $endTime)
                ->count();

            $inProgressCount = Trip::whereDate('created_at', Carbon::today())
                ->where('status', 'in_progress')
                ->whereTime('actual_departure_time', '>=', $startTime)
                ->whereTime('actual_departure_time', '<', $endTime)
                ->count();

            $chartCompletedData[] = $completedCount;
            $chartInProgressData[] = $inProgressCount;
        }

        return view('admin.dashboard', compact(
            'activeBuses',
            'totalBuses',
            'activeBusesPercentage',
            'registeredStaff',
            'activeStaffToday',
            'tripsToday',
            'completedTripsToday',
            'openIssues',
            'resolvedIssuesToday',
            'recentTrips',
            'alerts',
            'activities',
            'chartLabels',
            'chartCompletedData',
            'chartInProgressData'
        ));
    }
}
