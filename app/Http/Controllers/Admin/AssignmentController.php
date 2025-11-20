<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the assignments.
     */
    public function index()
    {
        $assignments = BusAssignment::with(['bus', 'driver', 'conductor'])
            ->orderBy('assignment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $buses = Bus::where('status', 'active')->get();
        $drivers = User::where('role', 'driver')->where('is_active', true)->get();
        $conductors = User::where('role', 'conductor')->where('is_active', true)->get();
        return view('admin.assignments.index', compact('assignments', 'buses', 'drivers', 'conductors'));
    }
    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'driver_id' => 'required|exists:users,id',
            'conductor_id' => 'required|exists:users,id',
            'assignment_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        $existingAssignment = BusAssignment::where('bus_id', $validated['bus_id'])
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'This bus is already assigned to another driver or conductor!')
                ->withInput();
        }


        $driverAssignment = BusAssignment::where('driver_id', $validated['driver_id'])

            ->where('status', 'active')
            ->first();

        if ($driverAssignment) {
            return redirect()->back()
                ->with('error', 'This driver is already assigned to another bus or conductor!')
                ->withInput();
        }

        $conductorAssignment = BusAssignment::where('conductor_id', $validated['conductor_id'])
            ->where('status', 'active')
            ->first();

        if ($conductorAssignment) {
            return redirect()->back()
                ->with('error', 'This conductor is already assigned to another bus or driver!')
                ->withInput();
        }

        // Create the assignment
        BusAssignment::create($validated);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Staff assigned to bus successfully!');
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(BusAssignment $assignment)
    {
        $buses = Bus::where('status', 'active')->get();
        $drivers = User::where('role', 'driver')->where('is_active', true)->get();
        $conductors = User::where('role', 'conductor')->where('is_active', true)->get();

        return view('admin.assignments.edit', compact('assignment', 'buses', 'drivers', 'conductors'));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, BusAssignment $assignment)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'driver_id' => 'required|exists:users,id',
            'conductor_id' => 'required|exists:users,id',
            'assignment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for conflicts (excluding current assignment)
        $existingAssignment = BusAssignment::where('bus_id', $validated['bus_id'])
            ->where('assignment_date', $validated['assignment_date'])
            ->where('status', 'active')
            ->where('id', '!=', $assignment->id)
            ->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'This bus is already assigned for the selected date!')
                ->withInput();
        }

        $driverAssignment = BusAssignment::where('driver_id', $validated['driver_id'])
            ->where('assignment_date', $validated['assignment_date'])
            ->where('status', 'active')
            ->where('id', '!=', $assignment->id)
            ->first();

        if ($driverAssignment) {
            return redirect()->back()
                ->with('error', 'This driver is already assigned to another bus for the selected date!')
                ->withInput();
        }

        $conductorAssignment = BusAssignment::where('conductor_id', $validated['conductor_id'])
            ->where('assignment_date', $validated['assignment_date'])
            ->where('status', 'active')
            ->where('id', '!=', $assignment->id)
            ->first();

        if ($conductorAssignment) {
            return redirect()->back()
                ->with('error', 'This conductor is already assigned to another bus for the selected date!')
                ->withInput();
        }

        $assignment->update($validated);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment updated successfully!');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(BusAssignment $assignment)
    {
        // Check if assignment has active trips
        if ($assignment->trips()->whereIn('status', ['scheduled', 'in_progress'])->exists()) {
            return redirect()->route('admin.assignments.index')
                ->with('error', 'Cannot delete assignment with active trips!');
        }

        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment removed successfully!');
    }

    /**
     * Toggle assignment status
     */
    public function toggleStatus(BusAssignment $assignment, Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,completed,cancelled'
        ]);

        $assignment->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.assignments.index')
            ->with('success', "Assignment status updated to {$request->status}!");
    }

    /**
     * Get available drivers and conductors for a specific date
     */
    public function getAvailableStaff(Request $request)
    {
        $date = $request->validate(['date' => 'required|date'])['date'];

        $assignedDriverIds = BusAssignment::where('assignment_date', $date)
            ->where('status', 'active')
            ->pluck('driver_id');

        $assignedConductorIds = BusAssignment::where('assignment_date', $date)
            ->where('status', 'active')
            ->pluck('conductor_id');

        $availableDrivers = User::where('role', 'driver')
            ->where('is_active', true)
            ->whereNotIn('id', $assignedDriverIds)
            ->get();

        $availableConductors = User::where('role', 'conductor')
            ->where('is_active', true)
            ->whereNotIn('id', $assignedConductorIds)
            ->get();

        return response()->json([
            'drivers' => $availableDrivers,
            'conductors' => $availableConductors
        ]);
    }
}
