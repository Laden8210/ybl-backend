<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverIssue extends Model
{
    protected $fillable = [
        'driver_id',
        'bus_id',
        'type',
        'description',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
