<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RouteController extends Controller
{
    /**
     * Display a listing of the routes.
     */
    public function index()
    {
        $routes = Route::orderBy('created_at', 'desc')->get();
        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new route.
     */
    public function create()
    {
        return view('admin.routes.create');
    }

    /**
     * Store a newly created route in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255|unique:routes',
            'description' => 'nullable|string|max:500',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'start_latitude' => 'required|numeric|between:-90,90',
            'start_longitude' => 'required|numeric|between:-180,180',
            'end_latitude' => 'required|numeric|between:-90,90',
            'end_longitude' => 'required|numeric|between:-180,180',
            'distance' => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:1',
            'waypoints' => 'nullable|array',
            'waypoints.*.name' => 'required|string|max:255',
            'waypoints.*.latitude' => 'required|numeric|between:-90,90',
            'waypoints.*.longitude' => 'required|numeric|between:-180,180',
            'waypoints.*.sequence' => 'required|integer|min:1',
        ]);

        // Convert waypoints to JSON
        if (isset($validated['waypoints'])) {
            // Sort waypoints by sequence
            usort($validated['waypoints'], function ($a, $b) {
                return $a['sequence'] - $b['sequence'];
            });
            $validated['waypoints'] = json_encode($validated['waypoints']);
        }

        Route::create($validated);

        return redirect()->route('admin.routes.index')
            ->with('success', 'Route created successfully!');
    }

    /**
     * Show the form for editing the specified route.
     */
    public function edit(Route $route)
    {

        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Update the specified route in storage.
     */
    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255|unique:routes,route_name,' . $route->id,
            'description' => 'nullable|string|max:500',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'start_latitude' => 'required|numeric|between:-90,90',
            'start_longitude' => 'required|numeric|between:-180,180',
            'end_latitude' => 'required|numeric|between:-90,90',
            'end_longitude' => 'required|numeric|between:-180,180',
            'distance' => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:1',
            'waypoints' => 'nullable|array',
            'waypoints.*.name' => 'required|string|max:255',
            'waypoints.*.latitude' => 'required|numeric|between:-90,90',
            'waypoints.*.longitude' => 'required|numeric|between:-180,180',
            'waypoints.*.sequence' => 'required|integer|min:1',
        ]);

        // Convert waypoints to JSON
        if (isset($validated['waypoints'])) {
            // Sort waypoints by sequence
            usort($validated['waypoints'], function ($a, $b) {
                return $a['sequence'] - $b['sequence'];
            });
            $validated['waypoints'] = json_encode($validated['waypoints']);
        } else {
            $validated['waypoints'] = null;
        }

        $route->update($validated);

        return redirect()->route('admin.routes.index')
            ->with('success', 'Route updated successfully!');
    }

    /**
     * Remove the specified route from storage.
     */
    public function destroy(Route $route)
    {
        // Check if route has active schedules
        if ($route->schedules()->exists()) {
            return redirect()->route('admin.routes.index')
                ->with('error', 'Cannot delete route with active schedules!');
        }

        $route->delete();

        return redirect()->route('admin.routes.index')
            ->with('success', 'Route deleted successfully!');
    }

    /**
     * Toggle route status
     */
    public function toggleStatus(Route $route)
    {
        $route->update([
            'is_active' => !$route->is_active
        ]);

        $status = $route->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.routes.index')
            ->with('success', "Route {$status} successfully!");
    }

    /**
     * Get route details for API
     */
    public function getRouteDetails(Route $route)
    {
        return response()->json([
            'route' => $route,
            'waypoints' => $route->waypoints ? json_decode($route->waypoints, true) : []
        ]);
    }

    /**
     * Show the specified route.
     */
    public function show(Route $route)
    {
        $route->load('schedules.bus');

        return view('admin.routes.show', compact('route'));
    }
}
