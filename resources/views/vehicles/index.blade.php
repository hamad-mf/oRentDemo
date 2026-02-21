@extends('layouts.app')
@section('header', 'Vehicles (Fleet)')

@section('content')
    <div class="space-y-6">

        {{-- Success / Error Banner --}}
        @if(session('success'))
            <div
                class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-5 py-3 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div
                class="flex items-center gap-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg px-5 py-3 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Fleet Status Bar --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('vehicles.index') }}"
                class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-4 text-center hover:border-white/20 transition-all {{ !request('status') ? 'border-white/30' : '' }}">
                <p class="text-3xl font-light text-white">{{ $totalCount }}</p>
                <p class="text-mb-silver text-xs uppercase mt-1">Total Fleet</p>
            </a>
            <a href="{{ route('vehicles.index', ['status' => 'available']) }}"
                class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-4 text-center hover:border-green-500/40 transition-all {{ request('status') === 'available' ? 'border-green-500/50' : '' }}">
                <p class="text-3xl font-light text-green-400">{{ $available }}</p>
                <p class="text-mb-silver text-xs uppercase mt-1">Available</p>
            </a>
            <a href="{{ route('vehicles.index', ['status' => 'rented']) }}"
                class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-4 text-center hover:border-mb-accent/40 transition-all {{ request('status') === 'rented' ? 'border-mb-accent/50' : '' }}">
                <p class="text-3xl font-light text-mb-accent">{{ $rented }}</p>
                <p class="text-mb-silver text-xs uppercase mt-1">Rented</p>
            </a>
            <a href="{{ route('vehicles.index', ['status' => 'maintenance']) }}"
                class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-4 text-center hover:border-red-500/40 transition-all {{ request('status') === 'maintenance' ? 'border-red-500/50' : '' }}">
                <p class="text-3xl font-light text-red-400">{{ $maintenance }}</p>
                <p class="text-mb-silver text-xs uppercase mt-1">Workshop</p>
            </a>
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('vehicles.index') }}" class="flex items-center gap-3 flex-1">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-1 max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search brand, model or plate..."
                        class="w-full bg-mb-surface border border-mb-subtle/20 rounded-full py-2 pl-10 pr-4 text-white placeholder-mb-subtle focus:outline-none focus:border-mb-accent text-sm transition-colors">
                    <svg class="w-4 h-4 text-mb-subtle absolute left-4 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="text-mb-silver hover:text-white text-sm transition-colors">Search</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('vehicles.index') }}"
                        class="text-mb-subtle hover:text-white text-sm transition-colors">Clear</a>
                @endif
            </form>
            <a href="{{ route('vehicles.create') }}"
                class="bg-mb-accent text-white px-6 py-2 rounded-full hover:bg-mb-accent/80 transition-colors flex items-center gap-2 text-sm font-medium flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                Add Vehicle
            </a>
        </div>

        {{-- Vehicle Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($vehicles as $vehicle)
                <div
                    class="bg-mb-surface rounded-xl border border-mb-subtle/20 overflow-hidden group hover:border-mb-accent/30 transition-all duration-300 flex flex-col">

                    {{-- Image --}}
                    <div class="h-44 bg-mb-black relative overflow-hidden">
                        @if($vehicle->image_url)
                            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-mb-black to-mb-surface">
                                <svg class="w-14 h-14 text-mb-subtle/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        @endif

                        {{-- Status Badge --}}
                        <div class="absolute top-3 right-3">
                            @php
                                $badgeColors = [
                                    'available' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                    'rented' => 'bg-mb-accent/20 text-mb-accent border-mb-accent/30',
                                    'maintenance' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                ];
                                $dotColors = [
                                    'available' => 'bg-green-500',
                                    'rented' => 'bg-mb-accent',
                                    'maintenance' => 'bg-red-500',
                                ];
                            @endphp
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium border backdrop-blur-sm flex items-center gap-1.5 {{ $badgeColors[$vehicle->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$vehicle->status] ?? 'bg-gray-500' }}
                                    {{ $vehicle->status === 'available' ? 'animate-pulse' : '' }}"></span>
                                {{ $vehicle->statusLabel() }}
                            </span>
                        </div>

                        {{-- Documents badge --}}
                        @if($vehicle->documents_count > 0 || $vehicle->documents->count() > 0)
                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs bg-black/50 text-mb-silver border border-white/10 backdrop-blur-sm flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $vehicle->documents->count() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-white font-light text-lg leading-tight">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                        <p class="text-mb-silver text-sm mt-0.5">{{ $vehicle->year }} &bull; {{ $vehicle->license_plate }}</p>
                        @if($vehicle->color)
                            <p class="text-mb-subtle text-xs mt-0.5">{{ $vehicle->color }}</p>
                        @endif

                        {{-- Pricing --}}
                        <div class="mt-3 flex items-end gap-3">
                            <div>
                                <span
                                    class="text-mb-accent text-xl font-medium">${{ number_format($vehicle->daily_rate, 0) }}</span>
                                <span class="text-mb-subtle text-xs">/day</span>
                            </div>
                            @if($vehicle->monthly_rate)
                                <div class="text-mb-subtle text-xs pb-0.5">
                                    ${{ number_format($vehicle->monthly_rate, 0) }}/mo
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 flex items-center justify-between border-t border-mb-subtle/10 pt-4">
                            <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                class="text-sm text-mb-silver hover:text-white transition-colors flex items-center gap-1">
                                View Details
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('vehicles.edit', $vehicle->id) }}"
                                    class="text-mb-subtle hover:text-white transition-colors p-1.5 rounded hover:bg-white/5"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST"
                                    onsubmit="return confirm('Remove {{ $vehicle->brand }} {{ $vehicle->model }} from the fleet?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-mb-subtle hover:text-red-400 transition-colors p-1.5 rounded hover:bg-red-500/5"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <svg class="w-16 h-16 text-mb-subtle/20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-mb-subtle text-lg">No vehicles found.</p>
                    <p class="text-mb-subtle/60 text-sm mt-1">
                        @if(request('search') || request('status'))
                            Try adjusting your search or filter. <a href="{{ route('vehicles.index') }}"
                                class="text-mb-accent hover:underline">Clear filters</a>
                        @else
                            <a href="{{ route('vehicles.create') }}" class="text-mb-accent hover:underline">Add your first
                                vehicle</a> to get started.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

    </div>
@endsection