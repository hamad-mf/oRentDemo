<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Reservation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RentalLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_monthly_reservation()
    {
        $client = Client::create(['name' => 'John', 'email' => 'j@e.com', 'phone' => '1234567890', 'address' => 'X']);
        $vehicle = Vehicle::create([
            'brand' => 'MB',
            'model' => 'S',
            'year' => 2024,
            'license_plate' => 'MB-001',
            'daily_rate' => 100,
            'monthly_rate' => 2000,
            'status' => 'available'
        ]);

        $response = $this->post(route('reservations.store'), [
            'client_id' => $client->id,
            'vehicle_id' => $vehicle->id,
            'rental_type' => 'monthly',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'total_price' => 2000,
        ]);

        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservations', [
            'client_id' => $client->id,
            'rental_type' => 'monthly',
            'total_price' => 2000,
        ]);
    }

    public function test_overdue_calculation_on_return()
    {
        Storage::fake('public');

        $client = Client::create(['name' => 'John', 'email' => 'j@e.com', 'phone' => '1234567890', 'address' => 'X']);
        $vehicle = Vehicle::create([
            'brand' => 'MB',
            'model' => 'S',
            'year' => 2024,
            'license_plate' => 'MB-002',
            'daily_rate' => 100,
            'status' => 'rented'
        ]);

        // Reservation ended 2 days ago
        $reservation = Reservation::create([
            'client_id' => $client->id,
            'vehicle_id' => $vehicle->id,
            'rental_type' => 'daily',
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(2), // Due 2 days ago
            'total_price' => 300,
            'status' => 'active', // Already delivered
        ]);

        // Return today
        $response = $this->post(route('reservations.return.store', $reservation->id), [
            'fuel_level' => 100,
            'mileage' => 1000,
            'notes' => 'Late return',
            'photos' => ['front' => UploadedFile::fake()->image('f.jpg')],
        ]);

        $response->assertRedirect(route('reservations.index'));

        $reservation->refresh();
        $this->assertEquals('completed', $reservation->status);
        $this->assertTrue($reservation->overdue_amount > 0);
        // 2 days overdue * 100 = 200
        $this->assertEquals(200, $reservation->overdue_amount);
    }
}
