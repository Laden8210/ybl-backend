<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_time',
        'arrival_time',
        'day_of_week',
        'is_recurring',
        'effective_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'departure_time' => 'datetime:H:i',
        'arrival_time' => 'datetime:H:i',
        'effective_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    /**
     * Get the bus for the schedule.
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get the route for the schedule.
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the trips for the schedule.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Scope a query to only include schedules for a specific day.
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', strtolower($day));
    }

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Check if schedule is active for today.
     */
    public function isActiveToday(): bool
    {
        return $this->day_of_week === strtolower(now()->englishDayOfWeek) &&
               $this->status === 'scheduled';
    }
}
