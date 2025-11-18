<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'driver_id',
        'conductor_id',
        'assignment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'assignment_date' => 'date',
    ];

    /**
     * Get the bus for the assignment.
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get the driver for the assignment.
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the conductor for the assignment.
     */
    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }

    /**
     * Get the trips for the assignment.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Scope a query to only include active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include assignments for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('assignment_date', $date);
    }

    /**
     * Check if assignment is active for today.
     */
    public function isActiveToday(): bool
    {
        return $this->status === 'active' && $this->assignment_date->isToday();
    }
}
