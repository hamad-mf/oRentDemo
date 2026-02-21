@extends('layouts.app')
@section('header', 'Add New Vehicle')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center gap-3 text-sm text-mb-subtle">
            <a href="{{ route('vehicles.index') }}" class="hover:text-white transition-colors">Fleet</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-white">Add New Vehicle</span>
        </div>

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 text-sm text-red-400 space-y-1">
                @foreach($errors->all() as $error)
                    <p>&bull; {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Section: Identity --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-5">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Vehicle Identity</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Brand <span class="text-red-400">*</span></label>
                        <input type="text" name="brand" value="{{ old('brand') }}" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors placeholder-mb-subtle/50 text-sm"
                            placeholder="e.g. Mercedes-Benz">
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Model <span class="text-red-400">*</span></label>
                        <input type="text" name="model" value="{{ old('model') }}" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors placeholder-mb-subtle/50 text-sm"
                            placeholder="e.g. S-Class 500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Year <span class="text-red-400">*</span></label>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}" required min="1990"
                            max="{{ date('Y') + 1 }}"
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">License Plate <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="license_plate" value="{{ old('license_plate') }}" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors placeholder-mb-subtle/50 text-sm uppercase"
                            placeholder="MB-2024">
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Color</label>
                        <input type="text" name="color" value="{{ old('color') }}"
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors placeholder-mb-subtle/50 text-sm"
                            placeholder="e.g. Obsidian Black">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-mb-silver mb-2">VIN (Chassis Number)</label>
                    <input type="text" name="vin" value="{{ old('vin') }}"
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors placeholder-mb-subtle/50 text-sm"
                        placeholder="Optional — 17-character Vehicle Identification Number">
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
                            <input type="number" name="daily_rate" value="{{ old('daily_rate') }}" required step="0.01"
                                min="0"
                                class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg pl-7 pr-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm"
                                placeholder="250.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Monthly Rate ($)
                            <span class="text-mb-subtle text-xs ml-1">optional</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-mb-subtle text-sm">$</span>
                            <input type="number" name="monthly_rate" value="{{ old('monthly_rate') }}" step="0.01" min="0"
                                class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg pl-7 pr-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm"
                                placeholder="3500.00">
                        </div>
                        <p class="text-mb-subtle/60 text-xs mt-1">Used for long-term rentals (30+ days)</p>
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Status <span class="text-red-400">*</span></label>
                        <select name="status" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                            <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="rented" {{ old('status') === 'rented' ? 'selected' : '' }}>Rented</option>
                            <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section: Image --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-4">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Vehicle Image</h3>

                <div>
                    <label class="block text-sm text-mb-silver mb-2">Image URL</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}"
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors placeholder-mb-subtle/50 text-sm"
                        placeholder="https://example.com/car-photo.jpg" oninput="previewImage(this.value)">
                    <p class="text-mb-subtle/60 text-xs mt-1">Paste a direct link to the car image.</p>
                </div>

                <div id="image-preview-wrap" class="hidden">
                    <p class="text-mb-subtle text-xs mb-2 uppercase tracking-wide">Preview</p>
                    <div class="h-48 rounded-lg overflow-hidden border border-mb-subtle/20">
                        <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            {{-- Section: Documents --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-4">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Vehicle Documents</h3>
                <p class="text-mb-subtle text-sm">Upload registration, insurance, or any other official vehicle documents.
                </p>

                <div
                    class="bg-mb-black/50 border-2 border-dashed border-mb-subtle/30 rounded-lg p-8 text-center hover:border-mb-accent/50 transition-colors">
                    <svg class="mx-auto h-10 w-10 text-mb-subtle/40 mb-3" stroke="currentColor" fill="none"
                        viewBox="0 0 48 48">
                        <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <label for="documents" class="cursor-pointer">
                        <span class="text-mb-accent hover:text-mb-accent/80 text-sm font-medium transition-colors">Click to
                            upload files</span>
                        <span class="text-mb-subtle text-sm"> or drag and drop</span>
                        <input id="documents" name="documents[]" type="file" class="sr-only" multiple
                            accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                    <p class="text-mb-subtle/60 text-xs mt-2">Registration, Insurance, Pollution Cert — PDF, JPG, PNG up to
                        4MB each</p>
                    <div id="file-list" class="mt-3 space-y-1 text-xs text-mb-silver text-left hidden"></div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('vehicles.index') }}"
                    class="text-mb-silver hover:text-white transition-colors text-sm">Cancel</a>
                <button type="submit"
                    class="bg-mb-accent text-white px-8 py-3 rounded-full hover:bg-mb-accent/80 transition-colors font-medium shadow-lg shadow-mb-accent/20">
                    Save Vehicle
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
        document.getElementById('documents').addEventListener('change', function () {
            const list = document.getElementById('file-list');
            list.innerHTML = '';
            if (this.files.length > 0) {
                list.classList.remove('hidden');
                Array.from(this.files).forEach(f => {
                    list.innerHTML += `<p class="flex items-center gap-2"><svg class="w-3 h-3 text-mb-accent flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/></svg>${f.name}</p>`;
                });
            } else {
                list.classList.add('hidden');
            }
        });
    </script>
@endsection