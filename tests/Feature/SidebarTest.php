<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SidebarTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_all_sidebar_routes_are_accessible(): void
    {
        $routes = [
            '/dashboard',
            '/vehicles',
            '/reservations',
            '/clients',
            '/investments',
            '/gps',
            '/documents',
            '/expenses',
            '/challans',
            '/staff',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }
}
