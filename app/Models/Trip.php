<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'bus_assignment_id',
        'trip_date',
        'actual_departure_time',
        'actual_arrival_time',
        'passenger_count',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'status',
        'notes',
    ];

    protected $casts = [
        'trip_date' => 'date',
        'actual_departure_time' => 'datetime:H:i',
        'actual_arrival_time' => 'datetime:H:i',
        'passenger_count' => 'integer',
        'start_latitude' => 'decimal:8',
        'start_longitude' => 'decimal:8',
        'end_latitude' => 'decimal:8',
        'end_longitude' => 'decimal:8',
    ];

    /**
     * Get the schedule for the trip.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the bus assignment for the trip.
     */
    public function busAssignment()
    {
        return $this->belongsTo(BusAssignment::class);
    }

    /**
     * Get the bus through assignment.
     */
    public function bus()
    {
        return $this->hasOneThrough(
            Bus::class,           // Final model
            BusAssignment::class, // Intermediate model
            'id',                 // Foreign key on BusAssignment table
            'id',                 // Foreign key on Bus table
            'bus_assignment_id',  // Local key on Trip table
            'bus_id'              // Local key on BusAssignment table
        );
    }

    public function driver()
    {
        return $this->hasOneThrough(
            User::class,          // Final model
            BusAssignment::class, // Intermediate model
            'id',                 // Foreign key on BusAssignment table
            'id',                 // Foreign key on User table
            'bus_assignment_id',  // Local key on Trip table
            'driver_id'           // Local key on BusAssignment table
        );
    }

    public function route()
    {
        return $this->hasOneThrough(Route::class, Schedule::class, 'id', 'id', 'schedule_id', 'route_id');
    }

    /**
     * Get the drop points for the trip.
     */
    public function dropPoints()
    {
        return $this->hasMany(DropPoint::class);
    }

    /**
     * Get the bus locations for the trip.
     */
    public function locations()
    {
        return $this->hasMany(BusLocation::class);
    }

    /**
     * Get the latest location of the trip.
     */
    public function latestLocation()
    {
        return $this->hasOne(BusLocation::class)->latestOfMany();
    }

    /**
     * Get pending drop points for the trip.
     */
    public function pendingDropPoints()
    {
        return $this->dropPoints()->whereIn('status', ['requested', 'forwarded', 'confirmed']);
    }

    /**
     * Get completed drop points for the trip.
     */
    public function completedDropPoints()
    {
        return $this->dropPoints()->where('status', 'completed');
    }

    /**
     * Scope a query to only include trips for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('trip_date', today());
    }

    /**
     * Scope a query to only include active trips.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['scheduled', 'in_progress']);
    }

    /**
     * Start the trip.
     */
    public function startTrip($latitude = null, $longitude = null)
    {
        $this->update([
            'status' => 'in_progress',
            'actual_departure_time' => now(),
            'start_latitude' => $latitude,
            'start_longitude' => $longitude,
        ]);
    }

    /**
     * Complete the trip.
     */
    public function completeTrip($latitude = null, $longitude = null)
    {
        $this->update([
            'status' => 'completed',
            'actual_arrival_time' => now(),
            'end_latitude' => $latitude,
            'end_longitude' => $longitude,
        ]);
    }

    /**
     * Check if trip is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if trip is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
