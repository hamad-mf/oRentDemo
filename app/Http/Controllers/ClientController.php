<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display clients with search and filters.
     */
    public function index(Request $request)
    {
        $query = Client::withCount('reservations');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('filter')) {
            match ($request->filter) {
                'blacklisted' => $query->where('is_blacklisted', true),
                'rated' => $query->whereNotNull('rating'),
                'unrated' => $query->whereNull('rating'),
                default => null,
            };
        }

        $clients = $query->latest()->get();
        $totalCount = Client::count();
        $blacklisted = Client::where('is_blacklisted', true)->count();
        $topRated = Client::where('rating', '>=', 4)->count();

        return view('clients.index', compact('clients', 'totalCount', 'blacklisted', 'topRated'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $client = Client::create($validated);

        return redirect()->route('clients.show', $client->id)
            ->with('success', "Client {$client->name} added successfully.");
    }

    /**
     * Display a single client profile.
     */
    public function show(string $id)
    {
        $client = Client::with(['reservations.vehicle'])->findOrFail($id);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the edit form.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update client details.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Delete a client (guard: cannot delete if active reservations exist).
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);

        $activeCount = $client->reservations()->whereIn('status', ['pending', 'confirmed', 'active'])->count();
        if ($activeCount > 0) {
            return redirect()->route('clients.index')
                ->with('error', "Cannot delete {$client->name} — they have {$activeCount} active reservation(s).");
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', "{$client->name} has been removed.");
    }

    // ─── Extra Actions ─────────────────────────────────────────────────────────

    /**
     * Set / update a client's star rating (1–5).
     */
    public function rate(Request $request, string $id)
    {
        $request->validate(['rating' => 'required|integer|min:1|max:5']);
        $client = Client::findOrFail($id);
        $client->update(['rating' => $request->rating]);

        return back()->with('success', "Rating updated to {$request->rating} stars for {$client->name}.");
    }

    /**
     * Toggle blacklist status for a client.
     */
    public function blacklist(Request $request, string $id)
    {
        $request->validate(['blacklist_reason' => 'nullable|string|max:500']);
        $client = Client::findOrFail($id);

        if ($client->is_blacklisted) {
            // Un-blacklist
            $client->update(['is_blacklisted' => false, 'blacklist_reason' => null]);
            return back()->with('success', "{$client->name} has been removed from the blacklist.");
        } else {
            // Blacklist
            $client->update([
                'is_blacklisted' => true,
                'blacklist_reason' => $request->blacklist_reason,
            ]);
            return back()->with('success', "{$client->name} has been added to the blacklist.");
        }
    }
}
