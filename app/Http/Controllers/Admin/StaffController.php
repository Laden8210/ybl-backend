<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index()
    {
        $staff = User::whereIn('role', ['supervisor', 'driver', 'conductor'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:supervisor,driver,conductor',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'license_number' => 'required_if:role,driver|nullable|string|max:50|unique:users',
            'employee_id' => 'required|string|max:50|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.staff.index')
                        ->with('success', 'Staff account created successfully!');
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        // Ensure we're only editing staff roles
        if (!in_array($staff->role, ['supervisor', 'driver', 'conductor'])) {
            abort(404);
        }

        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, User $staff)
    {
        // Ensure we're only updating staff roles
        if (!in_array($staff->role, ['supervisor', 'driver', 'conductor'])) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'role' => 'required|in:supervisor,driver,conductor',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'license_number' => 'required_if:role,driver|nullable|string|max:50|unique:users,license_number,' . $staff->id,
            'employee_id' => 'required|string|max:50|unique:users,employee_id,' . $staff->id,
            'password' => 'nullable|confirmed|min:8',
        ]);

        // Remove password from validation if empty
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.index')
                        ->with('success', 'Staff account updated successfully!');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(User $staff)
    {
        // Ensure we're only deleting staff roles
        if (!in_array($staff->role, ['supervisor', 'driver', 'conductor'])) {
            abort(404);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
                        ->with('success', 'Staff account deleted successfully!');
    }

    /**
     * Toggle staff status (active/inactive)
     */
    public function toggleStatus(User $staff)
    {
        // Ensure we're only toggling staff roles
        if (!in_array($staff->role, ['supervisor', 'driver', 'conductor'])) {
            abort(404);
        }

        $staff->update([
            'is_active' => !$staff->is_active
        ]);

        $status = $staff->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.staff.index')
                        ->with('success', "Staff account {$status} successfully!");
    }
}
