<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fleet Status
        $totalCars = Vehicle::count();
        $availableCars = Vehicle::where('status', 'available')->count();
        $rentedCars = Vehicle::where('status', 'rented')->count();
        $maintenanceCars = Vehicle::where('status', 'maintenance')->count();

        // Daily Operations
        $todayReturns = \App\Models\Reservation::whereDate('end_date', today())
            ->where('status', 'active')
            ->count();

        $notifications = \App\Models\Reservation::where('status', 'pending')->count(); // Pending bookings

        // Business Performance
        $dailyTarget = 5000; // Static target for now
        $todayRevenue = \App\Models\Reservation::whereDate('created_at', today())->sum('total_price');
        $enquiries = \App\Models\Client::whereDate('created_at', today())->count() * 3; // Mock approx
        $closedDeals = \App\Models\Reservation::whereDate('created_at', today())->count();
        $newClients = \App\Models\Client::whereDate('created_at', today())->count();

        // Accounts (Simple aggregation)
        $totalRevenue = \App\Models\Reservation::sum('total_price');
        $accounts = [
            'total' => $totalRevenue,
            'cash' => $totalRevenue * 0.4, // Mock split
            'ac' => $totalRevenue * 0.5,
            'credit' => $totalRevenue * 0.1,
        ];

        return view('dashboard', compact(
            'totalCars',
            'availableCars',
            'rentedCars',
            'maintenanceCars',
            'todayReturns',
            'notifications',
            'dailyTarget',
            'todayRevenue',
            'enquiries',
            'closedDeals',
            'newClients',
            'accounts'
        ));
    }
}
