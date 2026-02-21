@extends('layouts.app')
@section('header', 'Reservations')

@section('content')
    <div class="space-y-6">

        {{-- Flash Messages --}}
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

        {{-- Status Filter Bar --}}
        <div class="grid grid-cols-5 gap-3">
            @php
                $filters = [
                    '' => ['label' => 'All', 'count' => $counts['all'], 'color' => 'white'],
                    'pending' => ['label' => 'Pending', 'count' => $counts['pending'], 'color' => 'yellow'],
                    'confirmed' => ['label' => 'Confirmed', 'count' => $counts['confirmed'], 'color' => 'blue'],
                    'active' => ['label' => 'Active', 'count' => $counts['active'], 'color' => 'green'],
                    'completed' => ['label' => 'Completed', 'count' => $counts['completed'], 'color' => 'gray'],
                ];
                $colorMap = [
                    'white' => 'text-white border-white/30',
                    'yellow' => 'text-yellow-400 border-yellow-500/50',
                    'blue' => 'text-blue-400 border-blue-500/50',
                    'green' => 'text-green-400 border-green-500/50',
                    'gray' => 'text-mb-silver border-mb-subtle/40',
                ];
            @endphp
            @foreach($filters as $val => $f)
                <a href="{{ route('reservations.index', array_filter(['status' => $val, 'search' => request('search')])) }}"
                    class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-3 text-center hover:border-{{ $f['color'] === 'white' ? 'white' : $f['color'] . '-500' }}/40 transition-all
                              {{ request('status', '') === $val ? 'border-' . ($f['color'] === 'white' ? 'white' : $f['color'] . '-500') . '/50' : '' }}">
                    <p class="text-2xl font-light {{ $colorMap[$f['color']] ?? 'text-white' }} border-0">{{ $f['count'] }}</p>
                    <p class="text-mb-subtle text-xs mt-0.5">{{ $f['label'] }}</p>
                </a>
            @endforeach
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('reservations.index') }}" class="flex items-center gap-3 flex-1">
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <div class="relative flex-1 max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client or plate..."
                        class="w-full bg-mb-surface border border-mb-subtle/20 rounded-full py-2 pl-10 pr-4 text-white placeholder-mb-subtle focus:outline-none focus:border-mb-accent text-sm transition-colors">
                    <svg class="w-4 h-4 text-mb-subtle absolute left-4 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                @if(request('search') || request('status'))
                    <a href="{{ route('reservations.index') }}" class="text-mb-subtle hover:text-white text-sm">Clear</a>
                @endif
            </form>
            <a href="{{ route('reservations.create') }}"
                class="bg-mb-accent text-white px-6 py-2 rounded-full hover:bg-mb-accent/80 transition-colors flex items-center gap-2 text-sm font-medium flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Reservation
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-mb-black/60 text-mb-subtle uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-4 font-medium">#</th>
                        <th class="px-5 py-4 font-medium">Client</th>
                        <th class="px-5 py-4 font-medium">Vehicle</th>
                        <th class="px-5 py-4 font-medium">Dates</th>
                        <th class="px-5 py-4 font-medium">Type</th>
                        <th class="px-5 py-4 font-medium text-right">Total</th>
                        <th class="px-5 py-4 font-medium text-center">Status</th>
                        <th class="px-5 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-mb-subtle/10">
                    @forelse($reservations as $res)
                        @php
                            $isOverdue = $res->isOverdue();
                        @endphp
                        <tr
                            class="hover:bg-mb-black/20 transition-colors {{ $isOverdue ? 'border-l-2 border-red-500/50' : '' }}">
                            <td class="px-5 py-4 text-mb-subtle text-sm">#{{ $res->id }}</td>

                            <td class="px-5 py-4">
                                <a href="{{ route('clients.show', $res->client_id) }}"
                                    class="text-white hover:text-mb-accent transition-colors text-sm font-medium">
                                    {{ $res->client->name ?? '—' }}
                                </a>
                            </td>

                            <td class="px-5 py-4">
                                @if($res->vehicle)
                                    <a href="{{ route('vehicles.show', $res->vehicle_id) }}"
                                        class="text-mb-silver hover:text-white transition-colors text-sm">
                                        {{ $res->vehicle->brand }} {{ $res->vehicle->model }}
                                    </a>
                                    <p class="text-mb-subtle text-xs">{{ $res->vehicle->license_plate }}</p>
                                @else
                                    <span class="text-mb-subtle text-sm">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <p class="text-mb-silver">{{ $res->start_date->format('M d, Y') }}</p>
                                <p class="text-mb-subtle text-xs">→ {{ $res->end_date->format('M d, Y') }}</p>
                                @if($isOverdue)
                                    <p class="text-red-400 text-xs font-medium animate-pulse">⚠ Overdue</p>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <span
                                    class="text-mb-subtle text-xs capitalize px-2 py-0.5 rounded bg-mb-black/40">{{ $res->rental_type }}</span>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <p class="text-mb-accent text-sm font-medium">${{ number_format($res->total_price, 0) }}</p>
                                @if($res->overdue_amount > 0)
                                    <p class="text-red-400 text-xs">+${{ number_format($res->overdue_amount, 0) }} overdue</p>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @php
                                    $badge = [
                                        'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                        'confirmed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                        'active' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                        'completed' => 'bg-mb-subtle/10 text-mb-subtle border-mb-subtle/20',
                                    ];
                                @endphp
                                <span
                                    class="px-2.5 py-1 rounded-full text-xs font-medium border {{ $badge[$res->status] ?? 'bg-mb-subtle/10 text-mb-subtle' }}">
                                    {{ ucfirst($res->status) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('reservations.show', $res->id) }}"
                                        class="text-mb-subtle hover:text-white transition-colors p-1.5 rounded hover:bg-white/5"
                                        title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    @if(in_array($res->status, ['pending', 'confirmed']))
                                        <a href="{{ route('reservations.deliver', $res->id) }}"
                                            class="text-mb-subtle hover:text-green-400 transition-colors p-1.5 rounded hover:bg-green-500/5"
                                            title="Deliver">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('reservations.edit', $res->id) }}"
                                            class="text-mb-subtle hover:text-white transition-colors p-1.5 rounded hover:bg-white/5"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('reservations.destroy', $res->id) }}" method="POST"
                                            onsubmit="return confirm('Cancel reservation #{{ $res->id }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-mb-subtle hover:text-red-400 transition-colors p-1.5 rounded hover:bg-red-500/5"
                                                title="Cancel">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    @elseif($res->status === 'active')
                                        <a href="{{ route('reservations.return', $res->id) }}"
                                            class="text-mb-subtle hover:text-mb-accent transition-colors p-1.5 rounded hover:bg-mb-accent/5"
                                            title="Return">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <svg class="w-14 h-14 text-mb-subtle/20 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-mb-subtle">No reservations found.</p>
                                @if(!request('status') && !request('search'))
                                    <a href="{{ route('reservations.create') }}"
                                        class="text-mb-accent text-sm hover:underline mt-1 inline-block">Create your first
                                        reservation</a>
                                @else
                                    <a href="{{ route('reservations.index') }}"
                                        class="text-mb-accent text-sm hover:underline mt-1 inline-block">Clear filters</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection