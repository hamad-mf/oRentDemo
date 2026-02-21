<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'client_id',
        'vehicle_id',
        'rental_type',
        'start_date',
        'end_date',
        'actual_end_date',
        'total_price',
        'overdue_amount',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_end_date' => 'date',
        'total_price' => 'decimal:2',
        'overdue_amount' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function inspections()
    {
        return $this->hasMany(VehicleInspection::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function durationDays(): int
    {
        return (int) $this->start_date->diffInDays($this->end_date);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'active' => 'green',
            'completed' => 'gray',
            default => 'gray',
        };
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'active' && now()->startOfDay()->gt($this->end_date->startOfDay());
    }
}
