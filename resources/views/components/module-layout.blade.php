@props(['title', 'createRoute' => null])

@extends('layouts.app')

@section('header', $title)

@section('content')
    <div class="space-y-6">
        <!-- Module Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative">
                <input type="text" placeholder="Search {{ $title }}..."
                    class="bg-mb-surface border border-mb-subtle/20 rounded-full py-2 pl-10 pr-4 text-white placeholder-mb-subtle focus:outline-none focus:border-mb-accent w-full md:w-64 transition-colors">
                <svg class="w-4 h-4 text-mb-subtle absolute left-4 top-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            @if($createRoute)
                <a href="{{ $createRoute }}"
                    class="bg-mb-accent text-white px-6 py-2 rounded-full hover:bg-mb-accent/80 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New
                </a>
            @endif
        </div>

        <!-- Content / Table -->
        <div class="bg-mb-surface border border-mb-subtle/20 rounded-xl overflow-hidden">
            {{ $slot }}
        </div>
    </div>
@endsection