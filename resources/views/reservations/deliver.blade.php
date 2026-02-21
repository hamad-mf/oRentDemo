<x-module-layout title="Deliver Vehicle">
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('reservations.deliver.store', $reservation->id) }}" method="POST"
            enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Header Info -->
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <span class="block text-mb-subtle text-xs uppercase mb-1">Client</span>
                    <p class="text-white text-lg font-light">{{ $reservation->client->name }}</p>
                </div>
                <div>
                    <span class="block text-mb-subtle text-xs uppercase mb-1">Vehicle</span>
                    <p class="text-white text-lg font-light">{{ $reservation->vehicle->brand }}
                        {{ $reservation->vehicle->model }}
                    </p>
                    <p class="text-mb-silver text-sm">{{ $reservation->vehicle->license_plate }}</p>
                </div>
                <div>
                    <span class="block text-mb-subtle text-xs uppercase mb-1">Dates</span>
                    <p class="text-white text-lg font-light">{{ $reservation->start_date->format('M d') }} -
                        {{ $reservation->end_date->format('M d') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Readings -->
                <div class="space-y-6">
                    <h3 class="text-white text-lg font-light border-l-2 border-mb-accent pl-3">Odometer & Fuel</h3>

                    <div>
                        <label for="mileage" class="block text-sm font-medium text-mb-silver mb-2">Current Mileage
                            (km)</label>
                        <input type="number" name="mileage" id="mileage" required
                            class="w-full bg-mb-surface border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors"
                            placeholder="e.g. 15000">
                    </div>

                    <div>
                        <label for="fuel_level" class="block text-sm font-medium text-mb-silver mb-2">Fuel Level
                            (%)</label>
                        <div class="relative pt-1">
                            <input type="range" name="fuel_level" id="fuel_level" min="0" max="100" value="100"
                                class="w-full h-2 bg-mb-subtle/50 rounded-lg appearance-none cursor-pointer accent-mb-accent"
                                oninput="document.getElementById('fuel-val').innerText = this.value + '%'">
                            <span id="fuel-val"
                                class="absolute right-0 top-0 text-mb-accent text-sm font-bold">100%</span>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-mb-silver mb-2">Inspection
                            Notes</label>
                        <textarea name="notes" id="notes" rows="4"
                            class="w-full bg-mb-surface border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors"
                            placeholder="Note any existing scratches, dents, or issues..."></textarea>
                    </div>
                </div>

                <!-- Photos -->
                <div class="space-y-6">
                    <h3 class="text-white text-lg font-light border-l-2 border-mb-accent pl-3">Vehicle Condition Photos
                    </h3>
                    <p class="text-xs text-mb-subtle">Upload clear photos for each area.</p>

                    @foreach(['Front', 'Back', 'Left', 'Right', 'Interior'] as $area)
                        <div
                            class="bg-mb-black/30 p-4 rounded-lg border border-mb-subtle/10 hover:border-mb-accent/30 transition-colors">
                            <label class="block text-sm font-medium text-mb-silver mb-2">{{ $area }} View</label>
                            <input type="file" name="photos[{{ strtolower($area) }}]" accept="image/*" class="block w-full text-sm text-mb-silver
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-xs file:font-semibold
                                        file:bg-mb-surface file:text-mb-accent
                                        hover:file:bg-mb-surface/80
                                        cursor-pointer">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-8 border-t border-mb-subtle/10">
                <a href="{{ route('reservations.index') }}"
                    class="text-mb-silver hover:text-white transition-colors">Cancel</a>
                <button type="submit"
                    class="bg-mb-accent text-white px-8 py-3 rounded-full hover:bg-mb-accent/80 transition-colors font-medium shadow-lg shadow-mb-accent/20">
                    Confirm Delivery
                </button>
            </div>
        </form>
    </div>
</x-module-layout>