@extends('layouts.app')
@section('header', 'Edit Reservation #' . $reservation->id)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-3 text-sm text-mb-subtle">
        <a href="{{ route('reservations.index') }}" class="hover:text-white transition-colors">Reservations</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('reservations.show', $reservation->id) }}" class="hover:text-white transition-colors">#{{ $reservation->id }}</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white">Edit</span>
    </div>

    <div class="bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 rounded-lg px-4 py-3 text-sm">
        ⚠ Only <strong>pending</strong> and <strong>confirmed</strong> reservations can be edited.
    </div>

    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 text-sm text-red-400 space-y-1">
            @foreach($errors->all() as $error)<p>&bull; {{ $error }}</p>@endforeach
        </div>
    @endif

    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        {{-- Client & Type --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-5">
            <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Client &amp; Rental Type</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm text-mb-silver mb-2">Client <span class="text-red-400">*</span>
                        <span class="text-mb-subtle text-xs ml-1">(blacklisted hidden)</span>
                    </label>
                    <select name="client_id" required class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $reservation->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                                @if($client->rating) ({{ $client->starDisplay() }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-mb-silver mb-2">Rental Type <span class="text-red-400">*</span></label>
                    <select name="rental_type" id="rental_type" required class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                        <option value="daily"   {{ old('rental_type', $reservation->rental_type) === 'daily'   ? 'selected' : '' }}>Daily</option>
                        <option value="monthly" {{ old('rental_type', $reservation->rental_type) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Vehicle & Dates --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-5">
            <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Vehicle &amp; Dates</h3>
            <div>
                <label class="block text-sm text-mb-silver mb-2">Vehicle <span class="text-red-400">*</span></label>
                <select name="vehicle_id" id="vehicle_id" required class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    <option value="">Select Vehicle</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}"
                            data-daily="{{ $vehicle->daily_rate }}"
                            data-monthly="{{ $vehicle->monthly_rate ?? 0 }}"
                            {{ old('vehicle_id', $reservation->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->brand }} {{ $vehicle->model }} — {{ $vehicle->license_plate }}
                            (${{ number_format($vehicle->daily_rate, 0) }}/day)
                            @if($vehicle->id === $reservation->vehicle_id) [Current] @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm text-mb-silver mb-2">Start Date <span class="text-red-400">*</span></label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ old('start_date', $reservation->start_date->format('Y-m-d')) }}" required
                           class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                </div>
                <div>
                    <label class="block text-sm text-mb-silver mb-2">End Date <span class="text-red-400">*</span></label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ old('end_date', $reservation->end_date->format('Y-m-d')) }}" required
                           class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                </div>
            </div>
        </div>

        {{-- Price Summary --}}
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6">
            <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3 mb-5">Price Summary</h3>
            <div class="bg-mb-black/50 rounded-xl p-5 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-mb-subtle" id="breakdown-label">—</span>
                    <span class="text-mb-silver" id="breakdown-detail"></span>
                </div>
                <div class="border-t border-mb-subtle/10 pt-3 flex items-center justify-between">
                    <span class="text-white font-medium">Total Amount</span>
                    <span class="text-mb-accent text-3xl font-light" id="total-display">
                        ${{ number_format(old('total_price', $reservation->total_price), 0) }}
                    </span>
                </div>
                <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price', $reservation->total_price) }}">
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('reservations.show', $reservation->id) }}" class="text-mb-silver hover:text-white transition-colors text-sm">Cancel</a>
            <button type="submit" class="bg-mb-accent text-white px-8 py-3 rounded-full hover:bg-mb-accent/80 transition-colors font-medium shadow-lg shadow-mb-accent/20">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
function calcPrice() {
    const vehicleOpt = document.getElementById('vehicle_id').options[document.getElementById('vehicle_id').selectedIndex];
    const rentalType = document.getElementById('rental_type').value;
    const startVal   = document.getElementById('start_date').value;
    const endVal     = document.getElementById('end_date').value;
    const totalInput = document.getElementById('total_price');
    const totalDisp  = document.getElementById('total-display');
    const breakdown  = document.getElementById('breakdown-detail');
    const bLabel     = document.getElementById('breakdown-label');

    if (!vehicleOpt || !vehicleOpt.value || !startVal || !endVal) return;

    const daily   = parseFloat(vehicleOpt.dataset.daily) || 0;
    const monthly = parseFloat(vehicleOpt.dataset.monthly) || 0;
    const start   = new Date(startVal), end = new Date(endVal);
    if (end <= start) return;

    const days = Math.ceil(Math.abs(end - start) / 86400000);
    let total = 0, detail = '';

    if (rentalType === 'monthly' && monthly > 0) {
        const months = Math.floor(days / 30), extra = days % 30;
        total  = (months * monthly) + (extra * daily);
        detail = `${months}mo × $${monthly} + ${extra}d × $${daily}`;
    } else {
        total  = days * daily;
        detail = `${days} day(s) × $${daily}`;
    }

    bLabel.textContent    = 'Breakdown:';
    breakdown.textContent = detail;
    totalInput.value      = total.toFixed(2);
    totalDisp.textContent = '$' + total.toLocaleString(undefined, {minimumFractionDigits: 0});
}

['vehicle_id','rental_type','start_date','end_date'].forEach(id =>
    document.getElementById(id)?.addEventListener('change', calcPrice)
);
document.addEventListener('DOMContentLoaded', calcPrice);
</script>
@endsection
