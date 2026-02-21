@extends('layouts.app')
@section('header', 'Clients')

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

        {{-- Summary Bar --}}
        <div class="grid grid-cols-3 gap-4">
            <a href="{{ route('clients.index') }}"
                class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-4 text-center hover:border-white/20 transition-all {{ !request('filter') ? 'border-white/30' : '' }}">
                <p class="text-3xl font-light text-white">{{ $totalCount }}</p>
                <p class="text-mb-silver text-xs uppercase mt-1">Total Clients</p>
            </a>
            <a href="{{ route('clients.index', ['filter' => 'rated']) }}"
                class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-4 text-center hover:border-yellow-500/40 transition-all {{ request('filter') === 'rated' ? 'border-yellow-500/50' : '' }}">
                <p class="text-3xl font-light text-yellow-400">{{ $topRated }}</p>
                <p class="text-mb-silver text-xs uppercase mt-1">Top Rated (4–5★)</p>
            </a>
            <a href="{{ route('clients.index', ['filter' => 'blacklisted']) }}"
                class="bg-mb-surface border border-mb-subtle/20 rounded-lg p-4 text-center hover:border-red-500/40 transition-all {{ request('filter') === 'blacklisted' ? 'border-red-500/50' : '' }}">
                <p class="text-3xl font-light text-red-400">{{ $blacklisted }}</p>
                <p class="text-mb-silver text-xs uppercase mt-1">Blacklisted</p>
            </a>
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('clients.index') }}" class="flex items-center gap-3 flex-1">
                @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                <div class="relative flex-1 max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name, email or phone..."
                        class="w-full bg-mb-surface border border-mb-subtle/20 rounded-full py-2 pl-10 pr-4 text-white placeholder-mb-subtle focus:outline-none focus:border-mb-accent text-sm transition-colors">
                    <svg class="w-4 h-4 text-mb-subtle absolute left-4 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="text-mb-silver hover:text-white text-sm transition-colors">Search</button>
                @if(request('search') || request('filter'))
                    <a href="{{ route('clients.index') }}"
                        class="text-mb-subtle hover:text-white text-sm transition-colors">Clear</a>
                @endif
            </form>
            <a href="{{ route('clients.create') }}"
                class="bg-mb-accent text-white px-6 py-2 rounded-full hover:bg-mb-accent/80 transition-colors flex items-center gap-2 text-sm font-medium flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                Add Client
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-mb-black/60 text-mb-silver uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Client</th>
                        <th class="px-6 py-4 font-medium">Contact</th>
                        <th class="px-6 py-4 font-medium text-center">Rating</th>
                        <th class="px-6 py-4 font-medium text-center">Reservations</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-mb-subtle/10">
                    @forelse($clients as $client)
                        <tr
                            class="hover:bg-mb-black/20 transition-colors group {{ $client->is_blacklisted ? 'border-l-2 border-red-500/50' : '' }}">
                            {{-- Name --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar --}}
                                    <div
                                        class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 font-medium text-sm
                                        {{ $client->is_blacklisted ? 'bg-red-500/20 text-red-400' : 'bg-mb-accent/10 text-mb-accent' }}">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('clients.show', $client->id) }}"
                                            class="text-white hover:text-mb-accent transition-colors font-medium text-sm">
                                            {{ $client->name }}
                                        </a>
                                        @if($client->notes)
                                            <p class="text-mb-subtle text-xs truncate max-w-xs">{{ Str::limit($client->notes, 40) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Contact --}}
                            <td class="px-6 py-4">
                                <p class="text-mb-silver text-sm">{{ $client->phone }}</p>
                                <p class="text-mb-subtle text-xs">{{ $client->email }}</p>
                            </td>

                            {{-- Rating --}}
                            <td class="px-6 py-4 text-center">
                                @if($client->rating)
                                    <div class="inline-flex flex-col items-center">
                                        <span
                                            class="{{ $client->ratingColor() }} text-base tracking-tight leading-none">{{ $client->starDisplay() }}</span>
                                        <span class="text-mb-subtle text-xs mt-0.5">{{ $client->ratingLabel() }}</span>
                                    </div>
                                @else
                                    <span class="text-mb-subtle/50 text-xs italic">Not rated</span>
                                @endif
                            </td>

                            {{-- Count --}}
                            <td class="px-6 py-4 text-center">
                                <span class="text-white text-sm">{{ $client->reservations_count }}</span>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if($client->is_blacklisted)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/15 text-red-400 border border-red-500/25">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Blacklisted
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                        Active
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('clients.show', $client->id) }}"
                                        class="text-mb-subtle hover:text-white transition-colors p-1.5 rounded hover:bg-white/5"
                                        title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('clients.edit', $client->id) }}"
                                        class="text-mb-subtle hover:text-white transition-colors p-1.5 rounded hover:bg-white/5"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST"
                                        onsubmit="return confirm('Remove {{ addslashes($client->name) }} from the system?')">
                                        @csrf @method('DELETE')
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <svg class="w-14 h-14 text-mb-subtle/20 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-mb-subtle">No clients found.</p>
                                @if(request('search') || request('filter'))
                                    <a href="{{ route('clients.index') }}"
                                        class="text-mb-accent text-sm hover:underline mt-1 inline-block">Clear filters</a>
                                @else
                                    <a href="{{ route('clients.create') }}"
                                        class="text-mb-accent text-sm hover:underline mt-1 inline-block">Add your first client</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection