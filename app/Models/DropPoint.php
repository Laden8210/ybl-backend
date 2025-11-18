<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'passenger_id',
        'address',
        'latitude',
        'longitude',
        'sequence_order',
        'status',
        'requested_at',
        'forwarded_at',
        'confirmed_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'sequence_order' => 'integer',
        'requested_at' => 'datetime',
        'forwarded_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the trip for the drop point.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the passenger who requested the drop point.
     */
    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    /**
     * Scope a query to only include pending drop points.
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['requested', 'forwarded', 'confirmed']);
    }

    /**
     * Scope a query to only include completed drop points.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Mark drop point as forwarded.
     */
    public function markAsForwarded()
    {
        $this->update([
            'status' => 'forwarded',
            'forwarded_at' => now(),
        ]);
    }

    /**
     * Mark drop point as confirmed by driver.
     */
    public function markAsConfirmed()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Mark drop point as completed.
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Check if drop point is pending action.
     */
    public function isPending(): bool
    {
        return in_array($this->status, ['requested', 'forwarded']);
    }

    /**
     * Check if drop point is ready for confirmation.
     */
    public function isReadyForConfirmation(): bool
    {
        return $this->status === 'forwarded';
    }
}
