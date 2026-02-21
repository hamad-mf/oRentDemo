<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInspection extends Model
{
    protected $fillable = [
        'reservation_id',
        'type',
        'photos',
        'notes',
        'fuel_level',
        'mileage',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
