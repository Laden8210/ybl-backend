<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    /**
     * Display a listing of the buses.
     */
    public function index()
    {
        $buses = Bus::orderBy('created_at', 'desc')->get();
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new bus.
     */
    public function create()
    {
        return view('admin.buses.create');
    }

    /**
     * Store a newly created bus in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_number' => 'required|string|max:50|unique:buses',
            'license_plate' => 'required|string|max:20|unique:buses',
            'capacity' => 'required|integer|min:1|max:100',
            'model' => 'required|string|max:100',
            'color' => 'nullable|string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
        ]);

        // Convert features array to JSON
        if (isset($validated['features'])) {
            $validated['features'] = json_encode($validated['features']);
        }

        Bus::create($validated);

        return redirect()->route('admin.buses.index')
                        ->with('success', 'Bus added to fleet successfully!');
    }

    /**
     * Show the form for editing the specified bus.
     */
    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Update the specified bus in storage.
     */
    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'bus_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('buses')->ignore($bus->id)
            ],
            'license_plate' => [
                'required',
                'string',
                'max:20',
                Rule::unique('buses')->ignore($bus->id)
            ],
            'capacity' => 'required|integer|min:1|max:100',
            'model' => 'required|string|max:100',
            'color' => 'nullable|string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
        ]);

        // Convert features array to JSON
        if (isset($validated['features'])) {
            $validated['features'] = json_encode($validated['features']);
        } else {
            $validated['features'] = null;
        }

        $bus->update($validated);

        return redirect()->route('admin.buses.index')
                        ->with('success', 'Bus updated successfully!');
    }

    /**
     * Remove the specified bus from storage.
     */
    public function destroy(Bus $bus)
    {
        // Check if bus has active assignments
        if ($bus->assignments()->where('status', 'active')->exists()) {
            return redirect()->route('admin.buses.index')
                            ->with('error', 'Cannot delete bus with active assignments!');
        }

        // Check if bus has active trips
        if ($bus->trips()->whereIn('status', ['scheduled', 'in_progress'])->exists()) {
            return redirect()->route('admin.buses.index')
                            ->with('error', 'Cannot delete bus with active trips!');
        }

        $bus->delete();

        return redirect()->route('admin.buses.index')
                        ->with('success', 'Bus deleted successfully!');
    }

    /**
     * Toggle bus status (active/maintenance/inactive)
     */
    public function toggleStatus(Bus $bus, Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,maintenance,inactive'
        ]);

        $bus->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.buses.index')
                        ->with('success', "Bus status updated to {$request->status}!");
    }
}
