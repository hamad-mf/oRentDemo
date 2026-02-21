@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
    <div class="space-y-8">

        <!-- Row 1: Fleet Status -->
        <section>
            <h3 class="text-white text-lg font-light mb-4 uppercase tracking-wider border-l-2 border-mb-accent pl-2">Fleet
                Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Cars -->
                <div
                    class="bg-mb-surface border border-mb-subtle/20 p-5 rounded-lg hover:border-white/20 transition-all group">
                    <p class="text-mb-silver text-sm uppercase mb-1">Total Cars</p>
                    <div class="flex items-end justify-between">
                        <span class="text-4xl font-light text-white">{{ $totalCars }}</span>
                        <svg class="w-6 h-6 text-mb-silver/30 group-hover:text-white transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Available Cars -->
                <div
                    class="bg-mb-surface border border-mb-subtle/20 p-5 rounded-lg hover:border-green-500/50 transition-all group">
                    <p class="text-mb-silver text-sm uppercase mb-1">Available</p>
                    <div class="flex items-end justify-between">
                        <span class="text-4xl font-light text-green-400">{{ $availableCars }}</span>
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    </div>
                </div>

                <!-- Running Cars (Rented) -->
                <div
                    class="bg-mb-surface border border-mb-subtle/20 p-5 rounded-lg hover:border-mb-accent/50 transition-all group">
                    <p class="text-mb-silver text-sm uppercase mb-1">Running / Rented</p>
                    <div class="flex items-end justify-between">
                        <span class="text-4xl font-light text-mb-accent">{{ $rentedCars }}</span>
                        <svg class="w-6 h-6 text-mb-accent/30 group-hover:text-mb-accent transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Workshop Cars (Maintenance) -->
                <div
                    class="bg-mb-surface border border-mb-subtle/20 p-5 rounded-lg hover:border-red-500/50 transition-all group">
                    <p class="text-mb-silver text-sm uppercase mb-1">Workshop</p>
                    <div class="flex items-end justify-between">
                        <span class="text-4xl font-light text-red-400">{{ $maintenanceCars }}</span>
                        <svg class="w-6 h-6 text-red-500/30 group-hover:text-red-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- Row 2: Daily Operations -->
        <section>
            <h3 class="text-white text-lg font-light mb-4 uppercase tracking-wider border-l-2 border-mb-accent pl-2">Daily
                Operations</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Today Returns -->
                <div
                    class="bg-mb-surface border border-mb-subtle/20 p-6 rounded-lg flex items-center justify-between hover:bg-mb-black/30 transition-colors cursor-pointer">
                    <div>
                        <p class="text-mb-silver text-sm uppercase mb-1">Today Returns</p>
                        <span class="text-3xl font-light text-white">{{ $todayReturns }} Vehicles</span>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-mb-accent/10 flex items-center justify-center text-mb-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Notifications -->
                <div
                    class="bg-mb-surface border border-mb-subtle/20 p-6 rounded-lg flex items-center justify-between hover:bg-mb-black/30 transition-colors cursor-pointer">
                    <div>
                        <p class="text-mb-silver text-sm uppercase mb-1">Notifications</p>
                        <span class="text-3xl font-light text-white">{{ $notifications }} New</span>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center text-yellow-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- Row 3: Business Performance -->
        <section>
            <h3 class="text-white text-lg font-light mb-4 uppercase tracking-wider border-l-2 border-mb-accent pl-2">
                Business Performance</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Daily Target -->
                <div class="bg-mb-surface border border-mb-subtle/20 p-5 rounded-lg">
                    <p class="text-mb-silver text-sm uppercase mb-2">Daily Target</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-light text-white">${{ number_format($dailyTarget) }}</span>
                        <span class="text-xs text-mb-silver">/ ${{ number_format($todayRevenue) }} achieved</span>
                    </div>
                    <div class="w-full bg-mb-black h-1.5 mt-3 rounded-full overflow-hidden">
                        <div class="bg-mb-accent h-full" style="width: {{ ($todayRevenue / $dailyTarget) * 100 }}%"></div>
                    </div>
                </div>

                <!-- Enquiries -->
                <div class="bg-mb-surface border border-mb-subtle/20 p-5 rounded-lg">
                    <p class="text-mb-silver text-sm uppercase mb-1">Enquiries</p>
                    <span class="text-3xl font-light text-white">{{ $enquiries }}</span>
                </div>

                <!-- Closed -->
                <div class="bg-mb-surface border border-mb-subtle/20 p-5 rounded-lg">
                    <p class="text-mb-silver text-sm uppercase mb-1">Closed Deals</p>
                    <span class="text-3xl font-light text-white">{{ $closedDeals }}</span>
                </div>

                <!-- Clients -->
                <div class="bg-mb-surface border border-mb-subtle/20 p-5 rounded-lg">
                    <p class="text-mb-silver text-sm uppercase mb-1">New Clients</p>
                    <span class="text-3xl font-light text-white">{{ $newClients }}</span>
                </div>
            </div>
        </section>

        <!-- Row 4: Accounts -->
        <section>
            <h3 class="text-white text-lg font-light mb-4 uppercase tracking-wider border-l-2 border-mb-accent pl-2">
                Accounts</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-mb-surface/50 border border-mb-subtle/20 p-4 rounded text-center">
                    <p class="text-xs text-mb-silver uppercase mb-1">Total</p>
                    <p class="text-2xl text-white font-light">${{ number_format($accounts['total']) }}</p>
                </div>
                <div class="bg-mb-surface/50 border border-mb-subtle/20 p-4 rounded text-center">
                    <p class="text-xs text-mb-silver uppercase mb-1">Cash</p>
                    <p class="text-2xl text-green-400 font-light">${{ number_format($accounts['cash']) }}</p>
                </div>
                <div class="bg-mb-surface/50 border border-mb-subtle/20 p-4 rounded text-center">
                    <p class="text-xs text-mb-silver uppercase mb-1">Bank (AC)</p>
                    <p class="text-2xl text-blue-400 font-light">${{ number_format($accounts['ac']) }}</p>
                </div>
                <div class="bg-mb-surface/50 border border-mb-subtle/20 p-4 rounded text-center">
                    <p class="text-xs text-mb-silver uppercase mb-1">Credit</p>
                    <p class="text-2xl text-red-400 font-light">${{ number_format($accounts['credit']) }}</p>
                </div>
            </div>
        </section>

        <!-- Row 5: Quick Links / List Items -->
        <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-center">
            @php
                $links = [
                    ['name' => 'Investments', 'route' => 'investments.index'],
                    ['name' => 'GPS Tracking', 'route' => 'gps.index'],
                    ['name' => 'Papers', 'route' => 'documents.index'],
                    ['name' => 'Expenses', 'route' => 'expenses.index'],
                    ['name' => 'Challans', 'route' => 'challans.index'],
                    ['name' => 'Staff', 'route' => 'staff.index'],
                ];
            @endphp

            @foreach($links as $link)
                <a href="{{ route($link['route']) }}"
                    class="bg-mb-surface border border-mb-subtle/20 p-4 rounded-lg hover:bg-mb-black hover:border-mb-accent/30 transition-all group duration-300 transform hover:-translate-y-1">
                    <p class="text-mb-silver group-hover:text-white transition-colors text-sm uppercase tracking-wide">
                        {{ $link['name'] }}</p>
                </a>
            @endforeach
        </section>

    </div>
@endsection