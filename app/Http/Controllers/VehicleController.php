<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Document;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles with optional search and status filter.
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vehicles = $query->latest()->get();
        $totalCount = Vehicle::count();
        $available = Vehicle::where('status', 'available')->count();
        $rented = Vehicle::where('status', 'rented')->count();
        $maintenance = Vehicle::where('status', 'maintenance')->count();

        return view('vehicles.index', compact(
            'vehicles',
            'totalCount',
            'available',
            'rented',
            'maintenance'
        ));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|unique:vehicles,license_plate',
            'daily_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance',
            'image_url' => 'nullable|url',
            'color' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:50',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $vehicle = Vehicle::create($validated);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents', 'public');
                Document::create([
                    'title' => $file->getClientOriginalName(),
                    'type' => $file->getClientOriginalExtension(),
                    'vehicle_id' => $vehicle->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('vehicles.show', $vehicle->id)
            ->with('success', "Vehicle {$vehicle->brand} {$vehicle->model} added successfully.");
    }

    /**
     * Display a single vehicle with all its documents and reservation history.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::with(['documents', 'reservations.client'])->findOrFail($id);
        $totalRevenue = $vehicle->reservations->sum('total_price');
        $activeReservation = $vehicle->reservations->where('status', 'active')->first();

        return view('vehicles.show', compact('vehicle', 'totalRevenue', 'activeReservation'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|unique:vehicles,license_plate,' . $vehicle->id,
            'daily_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance',
            'image_url' => 'nullable|url',
            'color' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:50',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $vehicle->update($validated);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents', 'public');
                Document::create([
                    'title' => $file->getClientOriginalName(),
                    'type' => $file->getClientOriginalExtension(),
                    'vehicle_id' => $vehicle->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('vehicles.show', $vehicle->id)
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        // Prevent deletion of vehicles that are currently rented
        if ($vehicle->status === 'rented') {
            return redirect()->route('vehicles.index')
                ->with('error', "Cannot delete a vehicle that is currently rented ({$vehicle->license_plate}).");
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', "Vehicle {$vehicle->brand} {$vehicle->model} has been removed from the fleet.");
    }
}
