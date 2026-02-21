@extends('layouts.app')
@section('header', 'Reservation #' . $reservation->id)

@section('content')
    <div class="space-y-6">

        @if(session('success'))
            <div
                class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-5 py-3 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-3 text-sm text-mb-subtle">
            <a href="{{ route('reservations.index') }}" class="hover:text-white transition-colors">Reservations</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-white">#{{ $reservation->id }}</span>
        </div>

        {{-- Hero Summary --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-5">
                <div class="flex items-start gap-5">
                    {{-- Status Indicator --}}
                    <div class="flex-shrink-0">
                        @php
                            $statusStyle = [
                                'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
                                'confirmed' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
                                'active' => 'bg-green-500/10 text-green-400 border-green-500/30',
                                'completed' => 'bg-mb-subtle/10 text-mb-subtle border-mb-subtle/30',
                            ];
                        @endphp
                        <span
                            class="px-4 py-2 rounded-full text-sm font-medium border {{ $statusStyle[$reservation->status] ?? 'bg-mb-subtle/10 text-mb-subtle' }}">
                            {{ ucfirst($reservation->status) }}
                        </span>
                        @if($reservation->isOverdue())
                            <p class="text-red-400 text-xs text-center mt-2 animate-pulse font-medium">⚠ Overdue</p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-mb-subtle text-xs uppercase">Client</p>
                                <a href="{{ route('clients.show', $reservation->client_id) }}"
                                    class="text-white hover:text-mb-accent transition-colors font-medium">
                                    {{ $reservation->client->name ?? '—' }}
                                </a>
                                @if($reservation->client->rating)
                                    <p class="text-yellow-400 text-xs">{{ $reservation->client->starDisplay() }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-mb-subtle text-xs uppercase">Vehicle</p>
                                <a href="{{ route('vehicles.show', $reservation->vehicle_id) }}"
                                    class="text-white hover:text-mb-accent transition-colors font-medium">
                                    {{ $reservation->vehicle->brand ?? '—' }} {{ $reservation->vehicle->model ?? '' }}
                                </a>
                                <p class="text-mb-subtle text-xs">{{ $reservation->vehicle->license_plate ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-mb-subtle text-xs uppercase">Rental Type</p>
                                <p class="text-white capitalize">{{ $reservation->rental_type }}</p>
                            </div>
                            <div>
                                <p class="text-mb-subtle text-xs uppercase">Start Date</p>
                                <p class="text-white">{{ $reservation->start_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-mb-subtle text-xs uppercase">End Date</p>
                                <p class="text-white">{{ $reservation->end_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-mb-subtle text-xs uppercase">Duration</p>
                                <p class="text-white">{{ $reservation->durationDays() }} day(s)</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pricing Box --}}
                <div class="bg-mb-black/50 border border-mb-subtle/10 rounded-xl p-5 text-right flex-shrink-0 min-w-44">
                    <p class="text-mb-subtle text-xs uppercase mb-1">Total</p>
                    <p class="text-mb-accent text-3xl font-light">${{ number_format($reservation->total_price, 0) }}</p>
                    @if($reservation->overdue_amount > 0)
                        <p class="text-red-400 text-sm mt-1">+${{ number_format($reservation->overdue_amount, 0) }} overdue</p>
                        <p class="text-red-400/70 text-xs">Grand:
                            ${{ number_format($reservation->total_price + $reservation->overdue_amount, 0) }}</p>
                    @endif
                    @if($reservation->actual_end_date)
                        <p class="text-mb-subtle text-xs mt-2">Returned: {{ $reservation->actual_end_date->format('M d, Y') }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-3 pt-5 mt-5 border-t border-mb-subtle/10">
                @if(in_array($reservation->status, ['pending', 'confirmed']))
                    <a href="{{ route('reservations.deliver', $reservation->id) }}"
                        class="bg-green-500/10 text-green-400 border border-green-500/30 px-5 py-2 rounded-full hover:bg-green-500/20 transition-colors text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Deliver Vehicle
                    </a>
                    <a href="{{ route('reservations.edit', $reservation->id) }}"
                        class="bg-mb-accent/10 text-mb-accent border border-mb-accent/30 px-5 py-2 rounded-full hover:bg-mb-accent/20 transition-colors text-sm">
                        Edit Reservation
                    </a>
                    <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                        onsubmit="return confirm('Cancel and delete reservation #{{ $reservation->id }}?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="border border-red-500/30 text-red-400 px-5 py-2 rounded-full hover:bg-red-500/10 transition-colors text-sm">
                            Cancel Reservation
                        </button>
                    </form>
                @elseif($reservation->status === 'active')
                    <a href="{{ route('reservations.return', $reservation->id) }}"
                        class="bg-mb-accent text-white px-5 py-2 rounded-full hover:bg-mb-accent/80 transition-colors text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Process Return
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Delivery Inspection --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-mb-subtle/10 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-400"></div>
                    <h3 class="text-white font-light">Delivery Inspection</h3>
                </div>
                @if($deliveryInspection)
                    <div class="p-6 space-y-3 text-sm">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-mb-black/40 rounded-lg p-3">
                                <p class="text-mb-subtle text-xs uppercase">Mileage</p>
                                <p class="text-white text-lg">{{ number_format($deliveryInspection->mileage) }} km</p>
                            </div>
                            <div class="bg-mb-black/40 rounded-lg p-3">
                                <p class="text-mb-subtle text-xs uppercase">Fuel</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex-1 bg-mb-subtle/20 rounded-full h-2">
                                        <div class="bg-green-400 h-2 rounded-full"
                                            style="width:{{ $deliveryInspection->fuel_level }}%"></div>
                                    </div>
                                    <span
                                        class="text-green-400 text-sm font-medium">{{ $deliveryInspection->fuel_level }}%</span>
                                </div>
                            </div>
                        </div>
                        @if($deliveryInspection->notes)
                            <p class="text-mb-subtle text-xs uppercase mt-2">Notes</p>
                            <p class="text-mb-silver">{{ $deliveryInspection->notes }}</p>
                        @endif
                    </div>
                @else
                    <div class="py-10 text-center text-mb-subtle text-sm italic">Not yet delivered.</div>
                @endif
            </div>

            {{-- Return Inspection --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-mb-subtle/10 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full {{ $returnInspection ? 'bg-mb-accent' : 'bg-mb-subtle/40' }}"></div>
                    <h3 class="text-white font-light">Return Inspection</h3>
                </div>
                @if($returnInspection)
                    <div class="p-6 space-y-3 text-sm">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-mb-black/40 rounded-lg p-3">
                                <p class="text-mb-subtle text-xs uppercase">Mileage</p>
                                <p class="text-white text-lg">{{ number_format($returnInspection->mileage) }} km</p>
                                @if($deliveryInspection)
                                    <p class="text-mb-subtle text-xs">
                                        +{{ number_format($returnInspection->mileage - $deliveryInspection->mileage) }} km driven
                                    </p>
                                @endif
                            </div>
                            <div class="bg-mb-black/40 rounded-lg p-3">
                                <p class="text-mb-subtle text-xs uppercase">Fuel</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex-1 bg-mb-subtle/20 rounded-full h-2">
                                        <div class="bg-mb-accent h-2 rounded-full"
                                            style="width:{{ $returnInspection->fuel_level }}%"></div>
                                    </div>
                                    <span class="text-mb-accent text-sm font-medium">{{ $returnInspection->fuel_level }}%</span>
                                </div>
                            </div>
                        </div>
                        @if($returnInspection->notes)
                            <p class="text-mb-subtle text-xs uppercase mt-2">Notes</p>
                            <p class="text-mb-silver">{{ $returnInspection->notes }}</p>
                        @endif
                    </div>
                @else
                    <div class="py-10 text-center text-mb-subtle text-sm italic">
                        @if($reservation->status === 'active') Awaiting return. @else Not yet returned. @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection