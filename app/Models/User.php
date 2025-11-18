<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'license_number',
        'employee_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include users of a given role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has supervisor role
     */
    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    /**
     * Check if user has driver role
     */
    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    /**
     * Check if user has conductor role
     */
    public function isConductor(): bool
    {
        return $this->role === 'conductor';
    }

    /**
     * Check if user has passenger role
     */
    public function isPassenger(): bool
    {
        return $this->role === 'passenger';
    }

    /**
     * Check if user is staff (supervisor, driver, or conductor)
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['supervisor', 'driver', 'conductor']);
    }

    // Relationships

    /**
     * Get the bus assignments where this user is the driver
     */
    public function driverAssignments()
    {
        return $this->hasMany(BusAssignment::class, 'driver_id');
    }

    /**
     * Get the bus assignments where this user is the conductor
     */
    public function conductorAssignments()
    {
        return $this->hasMany(BusAssignment::class, 'conductor_id');
    }

    /**
     * Get the current active bus assignment for driver
     */
    public function currentDriverAssignment()
    {
        return $this->driverAssignments()
            ->where('status', 'active')
            ->whereDate('assignment_date', today())
            ->first();
    }

    /**
     * Get the current active bus assignment for conductor
     */
    public function currentConductorAssignment()
    {
        return $this->conductorAssignments()
            ->where('status', 'active')
            ->whereDate('assignment_date', today())
            ->first();
    }

    /**
     * Get the drop points requested by this passenger
     */
    public function dropPoints()
    {
        return $this->hasMany(DropPoint::class, 'passenger_id');
    }

    /**
     * Get the bus logs created by this user
     */
    public function busLogs()
    {
        return $this->hasMany(BusLog::class);
    }

    /**
     * Get the trips where this driver was assigned
     */
    public function drivenTrips()
    {
        return $this->hasManyThrough(Trip::class, BusAssignment::class, 'driver_id', 'bus_assignment_id');
    }

    /**
     * Get the trips where this conductor was assigned
     */
    public function conductedTrips()
    {
        return $this->hasManyThrough(Trip::class, BusAssignment::class, 'conductor_id', 'bus_assignment_id');
    }

    /**
     * Get the notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the unread notifications for this user
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    // Helper Methods

    /**
     * Get the user's full role name
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'supervisor' => 'Supervisor',
            'driver' => 'Driver',
            'conductor' => 'Conductor',
            'passenger' => 'Passenger',
            default => 'Unknown',
        };
    }

    /**
     * Check if user can be assigned to a bus (driver or conductor)
     */
    public function canBeAssignedToBus(): bool
    {
        return in_array($this->role, ['driver', 'conductor']) && $this->is_active;
    }

    /**
     * Get the current assigned bus for staff members
     */
    public function getCurrentBusAttribute()
    {
        if ($this->isDriver()) {
            $assignment = $this->currentDriverAssignment();
            return $assignment?->bus;
        }

        if ($this->isConductor()) {
            $assignment = $this->currentConductorAssignment();
            return $assignment?->bus;
        }

        return null;
    }

    public function getCurrentTripAttribute()
    {
        $bus = $this->current_bus;
        if ($bus) {
            return Trip::where('bus_assignment_id', $bus->id)
                ->whereDate('trip_date', today())
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->first();
        }

        return null;
    }
}
