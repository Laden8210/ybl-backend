<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     */
    public function index()
    {
        $schedules = Schedule::with(['bus', 'route'])
            ->orderBy('day_of_week')
            ->orderBy('departure_time')
            ->get();

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $buses = Bus::where('status', 'active')->get();
        $routes = Route::where('is_active', true)->get();

        return view('admin.schedules.create', compact('buses', 'routes'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'is_recurring' => 'boolean',
            'effective_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:effective_date',
        ]);

        // Set default values
        $validated['is_recurring'] = $request->has('is_recurring');
        $validated['status'] = 'scheduled';

        if ($validated['is_recurring']) {
            // Create schedules for all days of the week
            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($daysOfWeek as $day) {
                // Check for conflicting schedule for this specific day
                $conflictingSchedule = Schedule::where('bus_id', $validated['bus_id'])
                    ->where('day_of_week', $day)
                    ->where(function ($query) use ($validated) {
                        $query->whereBetween('departure_time', [
                            $validated['departure_time'],
                            $validated['arrival_time']
                        ])->orWhereBetween('arrival_time', [
                            $validated['departure_time'],
                            $validated['arrival_time']
                        ]);
                    })
                    ->where('status', 'scheduled')
                    ->first();

                if ($conflictingSchedule) {
                    return redirect()->back()
                        ->with('error', "This bus already has a scheduled trip on {$day} during the selected time!")
                        ->withInput();
                }

                // Create schedule for this day
                Schedule::create([
                    'bus_id' => $validated['bus_id'],
                    'route_id' => $validated['route_id'],
                    'departure_time' => $validated['departure_time'],
                    'arrival_time' => $validated['arrival_time'],
                    'day_of_week' => $day,
                    'is_recurring' => true,
                    'effective_date' => $validated['effective_date'],
                    'end_date' => $validated['end_date'],
                    'status' => 'scheduled'
                ]);
            }

            return redirect()->route('admin.schedules.index')
                ->with('success', 'Recurring schedule created for all days of the week!');
        } else {
            // Original logic for single day schedule
            $conflictingSchedule = Schedule::where('bus_id', $validated['bus_id'])
                ->where('day_of_week', $validated['day_of_week'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('departure_time', [
                        $validated['departure_time'],
                        $validated['arrival_time']
                    ])->orWhereBetween('arrival_time', [
                        $validated['departure_time'],
                        $validated['arrival_time']
                    ]);
                })
                ->where('status', 'scheduled')
                ->first();

            if ($conflictingSchedule) {
                return redirect()->back()
                    ->with('error', 'This bus already has a scheduled trip during the selected time!')
                    ->withInput();
            }

            Schedule::create($validated);

            return redirect()->route('admin.schedules.index')
                ->with('success', 'Schedule created successfully!');
        }
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule)
    {
        $buses = Bus::where('status', 'active')->get();
        $routes = Route::where('is_active', true)->get();

        return view('admin.schedules.edit', compact('schedule', 'buses', 'routes'));
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'is_recurring' => 'boolean',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after:effective_date',
        ]);

        // Check for schedule conflicts (excluding current schedule)
        $conflictingSchedule = Schedule::where('bus_id', $validated['bus_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('departure_time', [
                    $validated['departure_time'],
                    $validated['arrival_time']
                ])->orWhereBetween('arrival_time', [
                    $validated['departure_time'],
                    $validated['arrival_time']
                ]);
            })
            ->where('status', 'scheduled')
            ->first();

        if ($conflictingSchedule) {
            return redirect()->back()
                ->with('error', 'This bus already has a scheduled trip during the selected time!')
                ->withInput();
        }

        // Set default values
        $validated['is_recurring'] = $request->has('is_recurring');

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(Schedule $schedule)
    {
        // Check if schedule has active trips
        if ($schedule->trips()->whereIn('status', ['scheduled', 'in_progress'])->exists()) {
            return redirect()->route('admin.schedules.index')
                ->with('error', 'Cannot delete schedule with active trips!');
        }

        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }

    /**
     * Toggle schedule status
     */
    public function toggleStatus(Schedule $schedule, Request $request)
    {
        $request->validate([
            'status' => 'required|in:scheduled,departed,in_progress,completed,cancelled'
        ]);

        $schedule->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', "Schedule status updated to {$request->status}!");
    }
}
