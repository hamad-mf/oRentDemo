<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_vehicles_page_can_be_rendered(): void
    {
        $response = $this->get('/vehicles');

        $response->assertStatus(200);
        $response->assertSee('Fleet Management');
    }
}
