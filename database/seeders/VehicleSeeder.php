<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'brand' => 'Mercedes-Benz',
            'model' => 'C-Class 300',
            'year' => 2024,
            'license_plate' => 'MB-2024-C300',
            'status' => 'available',
            'daily_rate' => 150.00,
            'image_url' => 'https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=2670&auto=format&fit=crop',
        ]);

        Vehicle::create([
            'brand' => 'Mercedes-AMG',
            'model' => 'GT 63 S',
            'year' => 2025,
            'license_plate' => 'AMG-GT-63S',
            'status' => 'rented',
            'daily_rate' => 550.00,
            'image_url' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=2670&auto=format&fit=crop',
        ]);

        Vehicle::create([
            'brand' => 'Mercedes-Benz',
            'model' => 'G-Class G63',
            'year' => 2023,
            'license_plate' => 'G-WAGON-01',
            'status' => 'available',
            'daily_rate' => 450.00,
            'image_url' => 'https://images.unsplash.com/photo-1520031441872-ddb150216665?q=80&w=2543&auto=format&fit=crop',
        ]);

        Vehicle::create([
            'brand' => 'Mercedes-EQ',
            'model' => 'EQS 580',
            'year' => 2024,
            'license_plate' => 'EQ-FUTURE-01',
            'status' => 'maintenance',
            'daily_rate' => 300.00,
            // 'image_url' => null, // Testing duplicate/missing image
        ]);
    }
}
