<x-module-layout title="GPS Tracking">
    <div
        class="h-[600px] w-full bg-mb-black relative rounded-xl overflow-hidden flex items-center justify-center group">
        <!-- Placeholder Map Background (Static Image or Pattern) -->
        <div
            class="absolute inset-0 bg-[url('https://api.mapbox.com/styles/v1/mapbox/dark-v10/static/0,0,2,0,0/1200x800?access_token=YOUR_TOKEN')] bg-cover bg-center opacity-50 grayscale transition-all group-hover:grayscale-0">
        </div>

        <!-- Overlay Content -->
        <div
            class="relative z-10 text-center p-8 bg-mb-surface/80 backdrop-blur-sm rounded-xl border border-mb-subtle/20 max-w-md mx-4">
            <div
                class="w-16 h-16 bg-mb-accent/10 rounded-full flex items-center justify-center mx-auto mb-4 text-mb-accent">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-light text-white mb-2">Live Tracking Unavailable</h3>
            <p class="text-mb-silver text-sm mb-6">GPS integration is not yet connected. Connect your fleet telematics
                provider to see real-time vehicle locations.</p>
            <button class="bg-mb-accent text-white px-6 py-2 rounded-full hover:bg-mb-accent/80 transition-colors">
                Connect Provider
            </button>
        </div>
    </div>
</x-module-layout>