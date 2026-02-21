<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_user_can_create_reservation()
    {
        // Create dependencies
        $client = \App\Models\Client::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
        ]);

        $vehicle = \App\Models\Vehicle::create([
            'brand' => 'Mercedes',
            'model' => 'C-Class',
            'year' => 2024,
            'license_plate' => 'MB-TEST-001',
            'status' => 'available',
            'daily_rate' => 100,
            'image_url' => 'http://example.com/image.jpg',
        ]);

        // Visit Create Page
        $response = $this->get(route('reservations.create'));
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('MB-TEST-001');

        // Submit Reservation
        $response = $this->post(route('reservations.store'), [
            'client_id' => $client->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDays(3)->format('Y-m-d'), // 2 days
            'total_price' => 200,
        ]);

        $response->assertRedirect(route('reservations.index'));

        // precise verification
        $this->assertDatabaseHas('reservations', [
            'client_id' => $client->id,
            'vehicle_id' => $vehicle->id,
            'total_price' => 200,
            'status' => 'active',
        ]);

        // Verify vehicle status updated
        $this->assertEquals('rented', $vehicle->fresh()->status);
    }
}
