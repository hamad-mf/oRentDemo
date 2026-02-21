@extends('layouts.app')
@section('header', 'New Reservation')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-3 text-sm text-mb-subtle">
        <a href="{{ route('reservations.index') }}" class="hover:text-white transition-colors">Reservations</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white">New Reservation</span>
    </div>

    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 text-sm text-red-400 space-y-1">
            @foreach($errors->all() as $error)<p>&bull; {{ $error }}</p>@endforeach
        </div>
    @endif

    @if($clients->isEmpty())
        <div class="bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 rounded-lg p-4 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>No eligible clients available. All existing clients may be blacklisted. <a href="{{ route('clients.create') }}" class="underline hover:text-yellow-300">Add a new client first.</a></span>
        </div>
    @endif

    <form action="{{ route('reservations.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Client & Type --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-5">
            <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Client &amp; Rental Type</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm text-mb-silver mb-2">Client <span class="text-red-400">*</span>
                        <span class="text-mb-subtle text-xs ml-1">(blacklisted clients hidden)</span>
                    </label>
                    <select name="client_id" id="client_id" required
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                                @if($client->rating) ({{ $client->starDisplay() }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-mb-silver mb-2">Rental Type <span class="text-red-400">*</span></label>
                    <select name="rental_type" id="rental_type" required
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                        <option value="daily"   {{ old('rental_type') === 'daily'   ? 'selected' : '' }}>Daily</option>
                        <option value="monthly" {{ old('rental_type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Vehicle & Dates --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-5">
            <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Vehicle &amp; Dates</h3>

            <div>
                <label class="block text-sm text-mb-silver mb-2">Vehicle <span class="text-red-400">*</span>
                    <span class="text-mb-subtle text-xs ml-1">(available only)</span>
                </label>
                @if($vehicles->isEmpty())
                    <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg px-4 py-3 text-sm">
                        No available vehicles right now. <a href="{{ route('vehicles.index') }}" class="underline">Check fleet status.</a>
                    </div>
                @else
                    <select name="vehicle_id" id="vehicle_id" required
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                        <option value="">Select Vehicle</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}"
                                data-daily="{{ $vehicle->daily_rate }}"
                                data-monthly="{{ $vehicle->monthly_rate ?? 0 }}"
                                {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->brand }} {{ $vehicle->model }} — {{ $vehicle->license_plate }}
                                (${{ number_format($vehicle->daily_rate, 0) }}/day
                                @if($vehicle->monthly_rate), ${{ number_format($vehicle->monthly_rate, 0) }}/mo @endif)
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                @endif
            </div>

            {{-- Vehicle Quick Info --}}
            <div id="vehicle-info" class="hidden bg-mb-black/40 border border-mb-subtle/10 rounded-lg p-4 grid grid-cols-2 gap-3">
                <div>
                    <p class="text-mb-subtle text-xs uppercase">Daily Rate</p>
                    <p class="text-mb-accent text-lg font-light" id="vi-daily">—</p>
                </div>
                <div>
                    <p class="text-mb-subtle text-xs uppercase">Monthly Rate</p>
                    <p class="text-white text-lg font-light" id="vi-monthly">—</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm text-mb-silver mb-2">Start Date <span class="text-red-400">*</span></label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ old('start_date', date('Y-m-d')) }}" required
                           min="{{ date('Y-m-d') }}"
                           class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    @error('start_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-mb-silver mb-2">End Date <span class="text-red-400">*</span></label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ old('end_date') }}" required
                           class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    @error('end_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Price Summary --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6">
            <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3 mb-5">Price Summary</h3>
            <div class="bg-mb-black/50 rounded-xl p-5 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-mb-subtle" id="breakdown-label">Select vehicle and dates to see breakdown</span>
                    <span class="text-mb-silver" id="breakdown-detail"></span>
                </div>
                <div class="border-t border-mb-subtle/10 pt-3 flex items-center justify-between">
                    <span class="text-white font-medium">Total Amount</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-mb-accent text-3xl font-light" id="total-display">—</span>
                        <span class="text-mb-subtle text-sm">USD</span>
                    </div>
                </div>
                <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price') }}">
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('reservations.index') }}" class="text-mb-silver hover:text-white transition-colors text-sm">Cancel</a>
            <button type="submit" {{ $clients->isEmpty() || $vehicles->isEmpty() ? 'disabled' : '' }}
                class="bg-mb-accent text-white px-8 py-3 rounded-full hover:bg-mb-accent/80 transition-colors font-medium shadow-lg shadow-mb-accent/20 disabled:opacity-40 disabled:cursor-not-allowed">
                Confirm Reservation
            </button>
        </div>
    </form>
</div>

<script>
const labels = ['', 'Poor', 'Below Average', 'Average', 'Good', 'Excellent'];

function calcPrice() {
    const vehicleOpt  = document.getElementById('vehicle_id').options[document.getElementById('vehicle_id').selectedIndex];
    const rentalType  = document.getElementById('rental_type').value;
    const startVal    = document.getElementById('start_date').value;
    const endVal      = document.getElementById('end_date').value;
    const totalInput  = document.getElementById('total_price');
    const totalDisp   = document.getElementById('total-display');
    const breakdown   = document.getElementById('breakdown-detail');
    const breakLabel  = document.getElementById('breakdown-label');
    const vehicleInfo = document.getElementById('vehicle-info');
    const viDaily     = document.getElementById('vi-daily');
    const viMonthly   = document.getElementById('vi-monthly');

    if (!vehicleOpt || !vehicleOpt.value) {
        vehicleInfo.classList.add('hidden');
        totalDisp.textContent = '—';
        totalInput.value = '';
        return;
    }

    const daily   = parseFloat(vehicleOpt.dataset.daily) || 0;
    const monthly = parseFloat(vehicleOpt.dataset.monthly) || 0;

    vehicleInfo.classList.remove('hidden');
    viDaily.textContent   = '$' + daily.toLocaleString();
    viMonthly.textContent = monthly > 0 ? '$' + monthly.toLocaleString() : 'Not set';

    if (!startVal || !endVal) return;

    const start = new Date(startVal);
    const end   = new Date(endVal);
    if (end <= start) return;

    const days = Math.ceil(Math.abs(end - start) / 86400000);
    let total = 0, detail = '';

    if (rentalType === 'monthly' && monthly > 0) {
        const months    = Math.floor(days / 30);
        const extraDays = days % 30;
        total  = (months * monthly) + (extraDays * daily);
        detail = `${months} month(s) × $${monthly} + ${extraDays} day(s) × $${daily}`;
        breakLabel.textContent = `Pricing breakdown:`;
    } else {
        total  = days * daily;
        detail = `${days} day(s) × $${daily}`;
        breakLabel.textContent = `Pricing breakdown:`;
    }

    totalInput.value      = total.toFixed(2);
    totalDisp.textContent = '$' + total.toLocaleString(undefined, {minimumFractionDigits: 0});
    breakdown.textContent = detail;
}

['vehicle_id','rental_type','start_date','end_date'].forEach(id =>
    document.getElementById(id)?.addEventListener('change', calcPrice)
);
document.addEventListener('DOMContentLoaded', calcPrice);
</script>
@endsection