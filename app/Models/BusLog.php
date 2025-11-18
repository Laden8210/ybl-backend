<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'trip_id',
        'user_id',
        'action',
        'description',
        'metadata',
        'log_time',
    ];

    protected $casts = [
        'metadata' => 'array',
        'log_time' => 'datetime',
    ];

    /**
     * Get the bus for the log.
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get the trip for the log.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the user who created the log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include logs for a specific bus.
     */
    public function scopeForBus($query, $busId)
    {
        return $query->where('bus_id', $busId);
    }

    /**
     * Scope a query to only include logs from a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('log_time', [$startDate, $endDate]);
    }
}
