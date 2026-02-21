@extends('layouts.app')
@section('header', 'Edit Client')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <div class="flex items-center gap-3 text-sm text-mb-subtle">
            <a href="{{ route('clients.index') }}" class="hover:text-white transition-colors">Clients</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('clients.show', $client->id) }}"
                class="hover:text-white transition-colors">{{ $client->name }}</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-white">Edit</span>
        </div>

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 text-sm text-red-400 space-y-1">
                @foreach($errors->all() as $error)<p>&bull; {{ $error }}</p>@endforeach
            </div>
        @endif

        <form action="{{ route('clients.update', $client->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Personal Info --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-5">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Personal Information</h3>

                <div>
                    <label class="block text-sm text-mb-silver mb-2">Full Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $client->name) }}" required
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Email Address <span
                                class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $client->email) }}" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-mb-silver mb-2">Phone Number <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" required
                            class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-mb-silver mb-2">Address</label>
                    <textarea name="address" rows="2"
                        class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm resize-none">{{ old('address', $client->address) }}</textarea>
                </div>
            </div>

            {{-- Internal Notes --}}
            <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl p-6 space-y-4">
                <h3 class="text-white font-light text-lg border-l-2 border-mb-accent pl-3">Internal Notes
                    <span class="text-mb-subtle text-sm font-normal ml-2">— staff only</span>
                </h3>
                <textarea name="notes" rows="3"
                    class="w-full bg-mb-black border border-mb-subtle/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-mb-accent transition-colors text-sm resize-none"
                    placeholder="Any notes about this client...">{{ old('notes', $client->notes) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('clients.show', $client->id) }}"
                    class="text-mb-silver hover:text-white transition-colors text-sm">Cancel</a>
                <button type="submit"
                    class="bg-mb-accent text-white px-8 py-3 rounded-full hover:bg-mb-accent/80 transition-colors font-medium shadow-lg shadow-mb-accent/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection