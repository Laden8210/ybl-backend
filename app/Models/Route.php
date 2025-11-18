<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'description',
        'start_point',
        'end_point',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'distance',
        'estimated_duration',
        'waypoints',
        'is_active',
    ];

    protected $casts = [
        'distance' => 'decimal:2',
        'estimated_duration' => 'integer',
        'waypoints' => 'array',
        'start_latitude' => 'decimal:8',
        'start_longitude' => 'decimal:8',
        'end_latitude' => 'decimal:8',
        'end_longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Get the schedules for the route.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Scope a query to only include active routes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get active schedules for the route.
     */
    public function activeSchedules()
    {
        return $this->schedules()->whereHas('bus', function ($query) {
            $query->active();
        });
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Get formatted distance
     */
    public function getFormattedDistanceAttribute()
    {
        return "{$this->distance} km";
    }
}
