<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Reservation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DeliveryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_can_be_delivered_and_returned()
    {
        Storage::fake('public');

        // 1. Arrange: Create dependencies
        $client = Client::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
        ]);

        $vehicle = Vehicle::create([
            'brand' => 'Mercedes-Benz',
            'model' => 'C-Class',
            'year' => 2024,
            'license_plate' => 'MB-DEL-001',
            'daily_rate' => 100,
            'status' => 'available',
        ]);

        $reservation = Reservation::create([
            'client_id' => $client->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'total_price' => 300,
            'status' => 'confirmed',
        ]);

        // 2. Act: Deliver Vehicle
        $photos = [
            'front' => UploadedFile::fake()->image('front.jpg'),
            'back' => UploadedFile::fake()->image('back.jpg'),
        ];

        $response = $this->post(route('reservations.deliver.store', $reservation->id), [
            'fuel_level' => 100,
            'mileage' => 5000,
            'notes' => 'Clean condition',
            'photos' => $photos,
        ]);

        // 3. Assert: Delivery
        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('vehicle_inspections', [
            'reservation_id' => $reservation->id,
            'type' => 'delivery',
            'fuel_level' => 100,
            'mileage' => 5000,
        ]);

        // Verify JSON storage (partial)
        $inspection = \App\Models\VehicleInspection::where('reservation_id', $reservation->id)->where('type', 'delivery')->first();
        $this->assertArrayHasKey('front', $inspection->photos);

        $this->assertEquals('active', $reservation->fresh()->status);
        $this->assertEquals('rented', $vehicle->fresh()->status);


        // 4. Act: Return Vehicle
        $returnPhotos = [
            'front' => UploadedFile::fake()->image('return_front.jpg'),
        ];

        $responseReturn = $this->post(route('reservations.return.store', $reservation->id), [
            'fuel_level' => 90,
            'mileage' => 5200, // +200km
            'notes' => 'No new damage',
            'photos' => $returnPhotos,
        ]);

        // 5. Assert: Return
        $responseReturn->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('vehicle_inspections', [
            'reservation_id' => $reservation->id,
            'type' => 'return',
            'fuel_level' => 90,
            'mileage' => 5200,
        ]);

        $this->assertEquals('completed', $reservation->fresh()->status);
        $this->assertEquals('available', $vehicle->fresh()->status);
    }
}
