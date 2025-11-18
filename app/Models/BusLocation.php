<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'bus_id',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'recorded_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'speed' => 'decimal:2',
        'heading' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    /**
     * Get the trip for the location.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the bus for the location.
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Scope a query to only include locations from a specific time range.
     */
    public function scopeRecent($query, $minutes = 60)
    {
        return $query->where('recorded_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Scope a query to order by latest recorded time.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('recorded_at', 'desc');
    }
}
