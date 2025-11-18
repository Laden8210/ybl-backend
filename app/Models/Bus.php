<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_number',
        'license_plate',
        'capacity',
        'model',
        'color',
        'status',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
        'capacity' => 'integer',
    ];

    /**
     * Scope a query to only include active buses.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the bus assignments for the bus.
     */
    public function assignments()
    {
        return $this->hasMany(BusAssignment::class);
    }

    /**
     * Get the current assignment for the bus.
     */
    public function currentAssignment()
    {
        return $this->assignments()
            ->where('status', 'active')
            ->whereDate('assignment_date', today())
            ->first();
    }

    /**
     * Get the trips for the bus.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get the current trip for the bus.
     */
    public function currentTrip()
    {
        return $this->trips()
            ->whereDate('trip_date', today())
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->first();
    }

    /**
     * Get the bus locations for the bus.
     */
    public function locations()
    {
        return $this->hasMany(BusLocation::class);
    }

    /**
     * Get the latest location of the bus.
     */
    public function latestLocation()
    {
        return $this->hasOne(BusLocation::class)->latestOfMany();
    }

    /**
     * Get the bus logs for the bus.
     */
    public function logs()
    {
        return $this->hasMany(BusLog::class);
    }

    /**
     * Get the schedules for the bus.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Check if bus is available for assignment.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active' && !$this->currentAssignment();
    }

    /**
     * Get the current driver assigned to the bus.
     */
    public function getCurrentDriverAttribute()
    {
        return $this->currentAssignment()?->driver;
    }

    /**
     * Get the current conductor assigned to the bus.
     */
    public function getCurrentConductorAttribute()
    {
        return $this->currentAssignment()?->conductor;
    }
}
