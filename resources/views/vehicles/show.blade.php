@extends('layouts.app')
@section('header', $vehicle->brand . ' ' . $vehicle->model)

@section('content')
<div class="space-y-6">

    {{-- Success Banner --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-5 py-3 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 text-sm text-mb-subtle">
        <a href="{{ route('vehicles.index') }}" class="hover:text-white transition-colors">Fleet</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white">{{ $vehicle->brand }} {{ $vehicle->model }}</span>
    </div>

    {{-- Hero Card --}}
    <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            {{-- Image --}}
            <div class="h-72 lg:h-auto bg-mb-black relative">
                @if($vehicle->image_url)
                    <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full min-h-72 flex items-center justify-center bg-gradient-to-br from-mb-black to-mb-surface">
                        <svg class="w-20 h-20 text-mb-subtle/10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                @endif

                @php
                    $badgeColors = [
                        'available'   => 'bg-green-500/20 text-green-400 border-green-500/30',
                        'rented'      => 'bg-mb-accent/20 text-mb-accent border-mb-accent/30',
                        'maintenance' => 'bg-red-500/20 text-red-400 border-red-500/30',
                    ];
                @endphp
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1.5 rounded-full text-sm font-medium border backdrop-blur-sm {{ $badgeColors[$vehicle->status] ?? '' }}">
                        {{ $vehicle->statusLabel() }}
                    </span>
                </div>
            </div>

            {{-- Details --}}
            <div class="p-8 flex flex-col justify-between">
                <div class="space-y-4">
                    <div>
                        <h2 class="text-3xl font-light text-white">{{ $vehicle->brand }} {{ $vehicle->model }}</h2>
                        <p class="text-mb-silver mt-1">{{ $vehicle->year }} &bull; {{ $vehicle->license_plate }}</p>
                        @if($vehicle->color) <p class="text-mb-subtle text-sm">{{ $vehicle->color }}</p> @endif
                        @if($vehicle->vin)   <p class="text-mb-subtle text-xs mt-1">VIN: {{ $vehicle->vin }}</p> @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div class="bg-mb-black/50 rounded-lg p-4">
                            <p class="text-mb-subtle text-xs uppercase mb-1">Daily Rate</p>
                            <p class="text-mb-accent text-2xl font-light">${{ number_format($vehicle->daily_rate, 0) }}</p>
                            <p class="text-mb-subtle text-xs">per day</p>
                        </div>
                        <div class="bg-mb-black/50 rounded-lg p-4">
                            <p class="text-mb-subtle text-xs uppercase mb-1">Monthly Rate</p>
                            @if($vehicle->monthly_rate)
                                <p class="text-white text-2xl font-light">${{ number_format($vehicle->monthly_rate, 0) }}</p>
                                <p class="text-mb-subtle text-xs">per month</p>
                            @else
                                <p class="text-mb-subtle/50 text-sm italic">Not set</p>
                            @endif
                        </div>
                        <div class="bg-mb-black/50 rounded-lg p-4">
                            <p class="text-mb-subtle text-xs uppercase mb-1">Total Rentals</p>
                            <p class="text-white text-2xl font-light">{{ $vehicle->reservations->count() }}</p>
                        </div>
                        <div class="bg-mb-black/50 rounded-lg p-4">
                            <p class="text-mb-subtle text-xs uppercase mb-1">Total Revenue</p>
                            <p class="text-green-400 text-2xl font-light">${{ number_format($totalRevenue, 0) }}</p>
                        </div>
                    </div>

                    @if($activeReservation)
                        <div class="bg-mb-accent/10 border border-mb-accent/30 rounded-lg p-4">
                            <p class="text-mb-accent text-xs uppercase mb-1">Currently Rented By</p>
                            <p class="text-white font-medium">{{ $activeReservation->client->name }}</p>
                            <p class="text-mb-silver text-sm">Returns: {{ $activeReservation->end_date->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3 pt-6 border-t border-mb-subtle/10">
                    <a href="{{ route('vehicles.edit', $vehicle->id) }}"
                       class="bg-mb-accent text-white px-6 py-2.5 rounded-full hover:bg-mb-accent/80 transition-colors text-sm font-medium">
                        Edit Vehicle
                    </a>
                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST"
                          onsubmit="return confirm('Permanently remove this vehicle from the fleet?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="border border-red-500/30 text-red-400 px-6 py-2.5 rounded-full hover:bg-red-500/10 transition-colors text-sm">
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Rental History --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-mb-subtle/10">
                <h3 class="text-white font-light text-lg">Rental History</h3>
            </div>
            @if($vehicle->reservations->isEmpty())
                <div class="py-10 text-center text-mb-subtle text-sm italic">No rentals yet.</div>
            @else
                <div class="divide-y divide-mb-subtle/10">
                    @foreach($vehicle->reservations->sortByDesc('id')->take(10) as $res)
                        <div class="px-6 py-4 hover:bg-mb-black/20 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-white text-sm font-medium">{{ $res->client->name }}</p>
                                    <p class="text-mb-subtle text-xs mt-0.5">
                                        {{ $res->start_date->format('M d') }} – {{ $res->end_date->format('M d, Y') }}
                                        &bull; {{ ucfirst($res->rental_type) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-mb-accent text-sm">${{ number_format($res->total_price, 0) }}</p>
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                        {{ $res->status === 'active' ? 'bg-mb-accent/10 text-mb-accent' : 'bg-mb-subtle/10 text-mb-subtle' }}">
                                        {{ ucfirst($res->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Documents --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-mb-subtle/10 flex items-center justify-between">
                <h3 class="text-white font-light text-lg">Documents</h3>
                <span class="text-mb-subtle text-sm">{{ $vehicle->documents->count() }} file(s)</span>
            </div>
            @if($vehicle->documents->isEmpty())
                <div class="py-10 text-center text-mb-subtle text-sm italic">No documents uploaded.</div>
            @else
                <div class="divide-y divide-mb-subtle/10">
                    @foreach($vehicle->documents as $doc)
                        <div class="px-6 py-3 flex items-center gap-3 hover:bg-mb-black/20 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-mb-accent/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-mb-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-sm truncate">{{ $doc->title }}</p>
                                <p class="text-mb-subtle text-xs uppercase">{{ $doc->type }}</p>
                            </div>
                            @if($doc->file_path)
                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                                   class="text-mb-subtle hover:text-mb-accent transition-colors flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
