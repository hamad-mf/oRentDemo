@extends('layouts.app')
@section('header', $client->name)

@section('content')
    <div class="space-y-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div
                class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-5 py-3 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-3 text-sm text-mb-subtle">
            <a href="{{ route('clients.index') }}" class="hover:text-white transition-colors">Clients</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-white">{{ $client->name }}</span>
        </div>

        {{-- Profile Header --}}
        <div
            class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 {{ $client->is_blacklisted ? 'border-red-500/30' : '' }}">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="flex items-center gap-5">
                    {{-- Avatar --}}
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-light
                        {{ $client->is_blacklisted ? 'bg-red-500/20 text-red-400' : 'bg-mb-accent/10 text-mb-accent' }}">
                        {{ strtoupper(substr($client->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-light text-white">{{ $client->name }}</h2>
                            @if($client->is_blacklisted)
                                <span
                                    class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/15 text-red-400 border border-red-500/25 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    BLACKLISTED
                                </span>
                            @endif
                        </div>
                        <p class="text-mb-silver text-sm mt-1">{{ $client->email }}</p>
                        <p class="text-mb-subtle text-sm">{{ $client->phone }}</p>
                        @if($client->address)
                        <p class="text-mb-subtle text-xs mt-1">{{ $client->address }}</p> @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('clients.edit', $client->id) }}"
                        class="bg-mb-accent/10 text-mb-accent border border-mb-accent/30 px-5 py-2 rounded-full hover:bg-mb-accent/20 transition-colors text-sm font-medium">
                        Edit Client
                    </a>
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST"
                        onsubmit="return confirm('Permanently remove this client?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="border border-red-500/30 text-red-400 px-5 py-2 rounded-full hover:bg-red-500/10 transition-colors text-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            @if($client->is_blacklisted && $client->blacklist_reason)
                <div class="mt-4 bg-red-500/10 border border-red-500/20 rounded-lg px-4 py-3">
                    <p class="text-red-400 text-xs uppercase font-medium mb-1">Blacklist Reason</p>
                    <p class="text-red-300 text-sm">{{ $client->blacklist_reason }}</p>
                </div>
            @endif

            @if($client->notes)
                <div class="mt-4 bg-mb-black/30 border border-mb-subtle/10 rounded-lg px-4 py-3">
                    <p class="text-mb-subtle text-xs uppercase font-medium mb-1">Internal Notes</p>
                    <p class="text-mb-silver text-sm">{{ $client->notes }}</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left column: Stats + Rating + Blacklist --}}
            <div class="space-y-6">

                {{-- Stats --}}
                <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-5 space-y-4">
                    <h3 class="text-white font-light border-l-2 border-mb-accent pl-3">Overview</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-mb-black/50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-light text-white">{{ $client->reservations->count() }}</p>
                            <p class="text-mb-subtle text-xs">Total Rentals</p>
                        </div>
                        <div class="bg-mb-black/50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-light text-green-400">${{ number_format($client->totalRevenue(), 0) }}
                            </p>
                            <p class="text-mb-subtle text-xs">Total Spent</p>
                        </div>
                        <div class="bg-mb-black/50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-light text-mb-accent">
                                {{ $client->reservations->where('status', 'active')->count() }}</p>
                            <p class="text-mb-subtle text-xs">Active Now</p>
                        </div>
                        <div class="bg-mb-black/50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-light text-mb-silver">
                                {{ $client->reservations->where('status', 'completed')->count() }}</p>
                            <p class="text-mb-subtle text-xs">Completed</p>
                        </div>
                    </div>
                </div>

                {{-- ★ Star Rating Widget --}}
                <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-5">
                    <h3 class="text-white font-light border-l-2 border-mb-accent pl-3 mb-4">Client Rating</h3>

                    {{-- Display current rating --}}
                    <div class="text-center mb-4">
                        @if($client->rating)
                            <div class="text-3xl tracking-wider {{ $client->ratingColor() }}">{{ $client->starDisplay() }}</div>
                            <p class="text-mb-silver text-sm mt-1">{{ $client->ratingLabel() }}</p>
                        @else
                            <div class="text-3xl tracking-wider text-mb-subtle/30">☆☆☆☆☆</div>
                            <p class="text-mb-subtle text-sm mt-1 italic">Not yet rated</p>
                        @endif
                    </div>

                    {{-- Interactive star picker --}}
                    <form action="{{ route('clients.rate', $client->id) }}" method="POST" id="rateForm">
                        @csrf
                        <input type="hidden" name="rating" id="ratingInput" value="{{ $client->rating ?? 0 }}">
                        <div class="flex justify-center gap-2 mb-4" id="starPicker">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})"
                                    class="star-btn text-3xl transition-all duration-150 hover:scale-125" data-star="{{ $i }}">
                                    {{ $i <= ($client->rating ?? 0) ? '★' : '☆' }}
                                </button>
                            @endfor
                        </div>
                        <div class="flex items-center gap-2 text-center justify-center">
                            <span id="ratingLabel" class="text-mb-subtle text-sm">
                                {{ $client->ratingLabel() }}
                            </span>
                        </div>
                        <button type="submit"
                            class="mt-4 w-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 py-2 rounded-lg hover:bg-yellow-500/20 transition-colors text-sm font-medium">
                            Save Rating
                        </button>
                    </form>
                </div>

                {{-- 🚫 Blacklist Controls --}}
                <div
                    class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-5 {{ $client->is_blacklisted ? 'border-red-500/30' : '' }}">
                    <h3
                        class="text-white font-light border-l-2 border-{{ $client->is_blacklisted ? 'red' : 'mb' }}-{{ $client->is_blacklisted ? '500' : 'accent' }} pl-3 mb-4">
                        Blacklist
                    </h3>

                    @if($client->is_blacklisted)
                        <p class="text-red-400/80 text-sm mb-4">This client is currently blacklisted and cannot make new
                            reservations.</p>
                        <form action="{{ route('clients.blacklist', $client->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-green-500/10 border border-green-500/30 text-green-400 py-2.5 rounded-lg hover:bg-green-500/20 transition-colors text-sm font-medium">
                                ✓ Remove from Blacklist
                            </button>
                        </form>
                    @else
                        <p class="text-mb-subtle text-sm mb-4">Blacklisting will flag this client across all operations.</p>
                        <form action="{{ route('clients.blacklist', $client->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <textarea name="blacklist_reason" rows="2" placeholder="Reason for blacklisting (optional)..."
                                class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-red-500/50 transition-colors placeholder-mb-subtle/50 resize-none"></textarea>
                            <button type="submit"
                                onclick="return confirm('Blacklist {{ addslashes($client->name) }}? They will be flagged across all operations.')"
                                class="w-full bg-red-500/10 border border-red-500/30 text-red-400 py-2.5 rounded-lg hover:bg-red-500/20 transition-colors text-sm font-medium">
                                🚫 Add to Blacklist
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Right column: Reservation History --}}
            <div class="lg:col-span-2 bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-mb-subtle/10">
                    <h3 class="text-white font-light text-lg">Rental History</h3>
                </div>
                @if($client->reservations->isEmpty())
                    <div class="py-16 text-center text-mb-subtle text-sm italic">No rental history yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-mb-black/40 text-mb-subtle uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-5 py-3">Vehicle</th>
                                    <th class="px-5 py-3">Dates</th>
                                    <th class="px-5 py-3">Type</th>
                                    <th class="px-5 py-3 text-right">Total</th>
                                    <th class="px-5 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-mb-subtle/10">
                                @foreach($client->reservations->sortByDesc('id') as $res)
                                    <tr class="hover:bg-mb-black/10 transition-colors">
                                        <td class="px-5 py-3">
                                            @if($res->vehicle)
                                                <p class="text-white">{{ $res->vehicle->brand }} {{ $res->vehicle->model }}</p>
                                                <p class="text-mb-subtle text-xs">{{ $res->vehicle->license_plate }}</p>
                                            @else
                                                <span class="text-mb-subtle italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-mb-silver">
                                            {{ $res->start_date->format('M d') }} – {{ $res->end_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-5 py-3 text-mb-subtle capitalize">{{ $res->rental_type }}</td>
                                        <td class="px-5 py-3 text-right text-mb-accent">${{ number_format($res->total_price, 0) }}
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-xs
                                                        @if($res->status === 'active') bg-mb-accent/10 text-mb-accent
                                                        @elseif($res->status === 'completed') bg-green-500/10 text-green-400
                                                        @else bg-mb-subtle/10 text-mb-subtle
                                                        @endif">
                                                {{ ucfirst($res->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .star-btn {
            color: #4B5563;
        }

        .star-btn.active {
            color: #EAB308;
        }
    </style>

    <script>
        const labels = ['', 'Poor', 'Below Average', 'Average', 'Good', 'Excellent'];
        let currentRating = {{ $client->rating ?? 0 }};

        function setRating(val) {
            currentRating = val;
            document.getElementById('ratingInput').value = val;
            document.getElementById('ratingLabel').textContent = labels[val];
            document.querySelectorAll('#starPicker .star-btn').forEach((btn, i) => {
                btn.textContent = (i + 1) <= val ? '★' : '☆';
                btn.classList.toggle('active', (i + 1) <= val);
                // color
                btn.style.color = (i + 1) <= val ? '#EAB308' : '#4B5563';
            });
        }

        // Hover effects
        document.querySelectorAll('#starPicker .star-btn').forEach((btn, i) => {
            if ((i + 1) <= currentRating) {
                btn.style.color = '#EAB308';
            }
            btn.addEventListener('mouseenter', () => {
                document.querySelectorAll('#starPicker .star-btn').forEach((b, j) => {
                    b.textContent = (j + 1) <= (i + 1) ? '★' : '☆';
                    b.style.color = (j + 1) <= (i + 1) ? '#EAB308' : '#6B7280';
                });
            });
            btn.addEventListener('mouseleave', () => {
                document.querySelectorAll('#starPicker .star-btn').forEach((b, j) => {
                    b.textContent = (j + 1) <= currentRating ? '★' : '☆';
                    b.style.color = (j + 1) <= currentRating ? '#EAB308' : '#4B5563';
                });
                document.getElementById('ratingLabel').textContent = labels[currentRating] || 'Not Rated';
            });
        });
    </script>
@endsection