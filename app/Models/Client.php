<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'rating',
        'is_blacklisted',
        'blacklist_reason',
        'notes',
    ];

    protected $casts = [
        'is_blacklisted' => 'boolean',
        'rating' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Returns star display string, e.g. "★★★★☆"
     */
    public function starDisplay(): string
    {
        $r = $this->rating ?? 0;
        return str_repeat('★', $r) . str_repeat('☆', 5 - $r);
    }

    /**
     * Rating label (e.g. "Excellent")
     */
    public function ratingLabel(): string
    {
        return match ($this->rating) {
            5 => 'Excellent',
            4 => 'Good',
            3 => 'Average',
            2 => 'Below Average',
            1 => 'Poor',
            default => 'Not Rated',
        };
    }

    /**
     * Rating CSS colour class
     */
    public function ratingColor(): string
    {
        return match ($this->rating) {
            5, 4 => 'text-yellow-400',
            3 => 'text-mb-silver',
            2, 1 => 'text-red-400',
            default => 'text-mb-subtle',
        };
    }

    /**
     * Total revenue from all reservations.
     */
    public function totalRevenue(): float
    {
        return (float) $this->reservations->sum('total_price');
    }
}
