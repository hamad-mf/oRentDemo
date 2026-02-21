@extends('layouts.app')
@section('header', 'Edit Vehicle')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center gap-3 text-sm text-mb-subtle">
            <a href="{{ route('vehicles.index') }}" class="hover:text-white transition-colors">Fleet</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('vehicles.show', $vehicle->id) }}"
                class="hover:text-white transition-colors">{{ $vehicle->brand }} {{ $vehicle->model }}</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-white">Edit</span>
        </div>

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 text-sm text-red-400 space-y-1">
                @foreach($errors->all() as $error)<p>&bull; {{ $error }}</p>@endforeach
            </div>
        @endif

        <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Section: Identity --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-5">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Vehicle Identity</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Brand <span class="text-red-400">*</span></label>
                        <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Model <span class="text-red-400">*</span></label>
                        <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Year <span class="text-red-400">*</span></label>
                        <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" required min="1990"
                            max="{{ date('Y') + 1 }}"
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">License Plate <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}"
                            required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm uppercase">
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Color</label>
                        <input type="text" name="color" value="{{ old('color', $vehicle->color) }}"
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm"
                            placeholder="e.g. Obsidian Black">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-mb-silver mb-2">VIN (Chassis Number)</label>
                    <input type="text" name="vin" value="{{ old('vin', $vehicle->vin) }}"
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm"
                        placeholder="17-character Vehicle Identification Number">
                </div>
            </div>

            {{-- Section: Pricing & Status --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-5">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Pricing &amp; Status</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Daily Rate ($) <span
                                class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-mb-subtle text-sm">$</span>
                            <input type="number" name="daily_rate" value="{{ old('daily_rate', $vehicle->daily_rate) }}"
                                required step="0.01" min="0"
                                class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg pl-7 pr-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Monthly Rate ($)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-mb-subtle text-sm">$</span>
                            <input type="number" name="monthly_rate"
                                value="{{ old('monthly_rate', $vehicle->monthly_rate) }}" step="0.01" min="0"
                                class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg pl-7 pr-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm"
                                placeholder="3500.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Status <span class="text-red-400">*</span></label>
                        <select name="status" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                            <option value="available" {{ old('status', $vehicle->status) === 'available' ? 'selected' : '' }}>
                                Available</option>
                            <option value="rented" {{ old('status', $vehicle->status) === 'rented' ? 'selected' : '' }}>Rented
                            </option>
                            <option value="maintenance" {{ old('status', $vehicle->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section: Image --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-4">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Vehicle Image</h3>
                <div>
                    <label class="block text-sm text-mb-silver mb-2">Image URL</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $vehicle->image_url) }}"
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm"
                        oninput="previewImage(this.value)">
                </div>
                <div id="image-preview-wrap" class="{{ $vehicle->image_url ? '' : 'hidden' }}">
                    <div class="h-40 rounded-lg overflow-hidden border border-mb-subtle/20">
                        <img id="image-preview" src="{{ $vehicle->image_url }}" alt="Preview"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            {{-- Section: Additional Documents --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-4">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Add More Documents</h3>
                <div
                    class="bg-mb-black/50 border-2 border-dashed border-mb-subtle/30 rounded-lg p-6 text-center hover:border-mb-accent/50 transition-colors">
                    <label for="documents" class="cursor-pointer">
                        <span class="text-mb-accent hover:text-mb-accent/80 text-sm font-medium">Click to upload</span>
                        <span class="text-mb-subtle text-sm"> additional documents</span>
                        <input id="documents" name="documents[]" type="file" class="sr-only" multiple
                            accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                    <p class="text-mb-subtle/60 text-xs mt-1">PDF, JPG, PNG up to 4MB each</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('vehicles.show', $vehicle->id) }}"
                    class="text-mb-silver hover:text-white transition-colors text-sm">Cancel</a>
                <button type="submit"
                    class="bg-mb-accent text-white px-8 py-3 rounded-full hover:bg-mb-accent/80 transition-colors font-medium shadow-lg shadow-mb-accent/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(url) {
            const wrap = document.getElementById('image-preview-wrap');
            const img = document.getElementById('image-preview');
            if (url && url.startsWith('http')) {
                img.src = url;
                wrap.classList.remove('hidden');
            } else {
                wrap.classList.add('hidden');
            }
        }
    </script>
@endsection