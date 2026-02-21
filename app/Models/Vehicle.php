<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'year',
        'license_plate',
        'status',
        'daily_rate',
        'monthly_rate',
        'image_url',
        'color',
        'vin',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function statusColor(): string
    {
        return match ($this->status) {
            'available' => 'green',
            'rented' => 'blue',
            'maintenance' => 'red',
            default => 'gray',
        };
    }

    public function statusLabel(): string
    {
        return ucfirst($this->status);
    }
}
