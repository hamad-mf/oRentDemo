<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\VehicleInspection;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * List all reservations with optional search and status filter.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['client', 'vehicle']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('client', fn($q) => $q->where('name', 'like', "%{$s}%"))
                ->orWhereHas('vehicle', fn($q) => $q->where('license_plate', 'like', "%{$s}%")
                    ->orWhere('brand', 'like', "%{$s}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->get();

        $counts = [
            'all' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'active' => Reservation::where('status', 'active')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
        ];

        return view('reservations.index', compact('reservations', 'counts'));
    }

    /**
     * Show the create reservation form.
     * Blacklisted clients EXCLUDED.
     */
    public function create()
    {
        // Only non-blacklisted clients
        $clients = Client::where('is_blacklisted', false)->orderBy('name')->get();
        $vehicles = Vehicle::where('status', 'available')->orderBy('brand')->get();

        return view('reservations.create', compact('clients', 'vehicles'));
    }

    /**
     * Store a new reservation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'rental_type' => 'required|in:daily,monthly',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Guard: reject blacklisted clients
        $client = Client::findOrFail($validated['client_id']);
        if ($client->is_blacklisted) {
            return back()->withErrors(['client_id' => 'This client is blacklisted and cannot make reservations.']);
        }

        $validated['status'] = 'confirmed';

        $reservation = Reservation::create($validated);

        // Keep vehicle status as available until actual delivery
        // Vehicle is set to 'rented' when delivered (storeDelivery)

        return redirect()->route('reservations.show', $reservation->id)
            ->with('success', 'Reservation confirmed for ' . $client->name . '.');
    }

    /**
     * Show a single reservation's full detail.
     */
    public function show(string $id)
    {
        $reservation = Reservation::with([
            'client',
            'vehicle',
            'inspections'
        ])->findOrFail($id);

        $deliveryInspection = $reservation->inspections->where('type', 'delivery')->first();
        $returnInspection = $reservation->inspections->where('type', 'return')->first();

        return view('reservations.show', compact('reservation', 'deliveryInspection', 'returnInspection'));
    }

    /**
     * Edit form — only for pending/confirmed reservations.
     */
    public function edit(string $id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return redirect()->route('reservations.show', $id)
                ->with('error', 'Only pending or confirmed reservations can be edited.');
        }

        $clients = Client::where('is_blacklisted', false)->orderBy('name')->get();
        $vehicles = Vehicle::whereIn('status', ['available', 'rented'])
            ->orWhere('id', $reservation->vehicle_id)
            ->orderBy('brand')
            ->get();

        return view('reservations.edit', compact('reservation', 'clients', 'vehicles'));
    }

    /**
     * Update reservation details.
     */
    public function update(Request $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return redirect()->route('reservations.show', $id)
                ->with('error', 'Only pending or confirmed reservations can be edited.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'rental_type' => 'required|in:daily,monthly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Guard: reject blacklisted clients
        $client = Client::findOrFail($validated['client_id']);
        if ($client->is_blacklisted) {
            return back()->withErrors(['client_id' => 'This client is blacklisted and cannot make reservations.']);
        }

        $reservation->update($validated);

        return redirect()->route('reservations.show', $reservation->id)
            ->with('success', 'Reservation updated.');
    }

    /**
     * Cancel / delete a reservation (only if not active/completed).
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::with('vehicle')->findOrFail($id);

        if (in_array($reservation->status, ['active', 'completed'])) {
            return redirect()->route('reservations.index')
                ->with('error', 'Cannot delete an active or completed reservation.');
        }

        // Free vehicle if it was set to rented
        if ($reservation->vehicle && $reservation->vehicle->status === 'rented') {
            $reservation->vehicle->update(['status' => 'available']);
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation cancelled and removed.');
    }

    // ─── Delivery Flow ────────────────────────────────────────────────────────

    public function deliver($id)
    {
        $reservation = Reservation::with(['client', 'vehicle'])->findOrFail($id);

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return redirect()->route('reservations.index')
                ->with('error', 'This reservation cannot be delivered in its current state.');
        }

        return view('reservations.deliver', compact('reservation'));
    }

    public function storeDelivery(Request $request, $id)
    {
        $reservation = Reservation::with('vehicle')->findOrFail($id);

        $request->validate([
            'fuel_level' => 'required|integer|min:0|max:100',
            'mileage' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:1000',
            'photos' => 'array',
            'photos.*' => 'image|max:5120',
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $area => $photo) {
                $photos[$area] = $photo->store('inspections', 'public');
            }
        }

        VehicleInspection::create([
            'reservation_id' => $reservation->id,
            'type' => 'delivery',
            'fuel_level' => $request->fuel_level,
            'mileage' => $request->mileage,
            'notes' => $request->notes,
            'photos' => $photos,
        ]);

        $reservation->update(['status' => 'active']);
        $reservation->vehicle->update(['status' => 'rented']);

        return redirect()->route('reservations.show', $reservation->id)
            ->with('success', 'Vehicle delivered. Reservation is now active.');
    }

    // ─── Return Flow ──────────────────────────────────────────────────────────

    public function returnVehicle($id)
    {
        $reservation = Reservation::with(['client', 'vehicle', 'inspections'])->findOrFail($id);

        if ($reservation->status !== 'active') {
            return redirect()->route('reservations.index')
                ->with('error', 'Only active reservations can be returned.');
        }

        $deliveryInspection = $reservation->inspections->where('type', 'delivery')->first();
        $overdueInfo = $this->calcOverdue($reservation);

        return view('reservations.return', compact('reservation', 'deliveryInspection', 'overdueInfo'));
    }

    public function storeReturn(Request $request, $id)
    {
        $reservation = Reservation::with('vehicle')->findOrFail($id);

        $request->validate([
            'fuel_level' => 'required|integer|min:0|max:100',
            'mileage' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:1000',
            'photos' => 'array',
            'photos.*' => 'image|max:5120',
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $area => $photo) {
                $photos[$area] = $photo->store('inspections', 'public');
            }
        }

        VehicleInspection::create([
            'reservation_id' => $reservation->id,
            'type' => 'return',
            'fuel_level' => $request->fuel_level,
            'mileage' => $request->mileage,
            'notes' => $request->notes,
            'photos' => $photos,
        ]);

        $overdueInfo = $this->calcOverdue($reservation);

        $reservation->update([
            'status' => 'completed',
            'actual_end_date' => now(),
            'overdue_amount' => $overdueInfo['amount'],
        ]);

        $reservation->vehicle->update(['status' => 'available']);

        $msg = 'Vehicle returned successfully. Reservation closed.';
        if ($overdueInfo['amount'] > 0) {
            $msg .= ' ⚠️ Overdue charge: $' . number_format($overdueInfo['amount'], 2) . ' (' . $overdueInfo['days'] . ' extra days)';
        }

        return redirect()->route('reservations.show', $reservation->id)->with('success', $msg);
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    private function calcOverdue(Reservation $reservation): array
    {
        $today = now()->startOfDay();
        $endDate = $reservation->end_date->startOfDay();
        $days = 0;
        $amount = 0;

        if ($today->gt($endDate)) {
            $days = (int) $today->diffInDays($endDate);
            $amount = $days * (float) $reservation->vehicle->daily_rate;
        }

        return ['days' => $days, 'amount' => $amount];
    }
}
