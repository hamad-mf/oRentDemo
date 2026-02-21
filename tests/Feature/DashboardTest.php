<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_dashboard_displays_new_sections(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Fleet Status');
        $response->assertSee('Daily Operations');
        $response->assertSee('Business Performance');
        $response->assertSee('Accounts');
        $response->assertSee('Total Cars');
        $response->assertSee('GPS Tracking');
    }
}
