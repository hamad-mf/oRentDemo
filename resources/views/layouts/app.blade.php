<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'O Rent') | Mercedes-Benz Style</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <!-- Theme: apply before paint to avoid flash -->
    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            if (saved === 'light') {
                document.getElementById('html-root').classList.add('light-mode');
            }
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
                    },
                    colors: {
                        'mb-black': '#000000',
                        'mb-surface': '#1f1f1f',
                        'mb-silver': '#e5e5e5',
                        'mb-accent': '#00adef',
                        'mb-subtle': '#4a4a4a',
                    }
                }
            }
        }
    </script>

    <style>
        /* ── Light Mode (only applies when .light-mode class is on <html>) ── */
        .light-mode body {
            background-color: #f0f4f8 !important;
            color: #0f172a !important;
        }

        /* Smooth transitions */
        *,
        *::before,
        *::after {
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.2s ease;
        }

        /* Light-mode text override for white-coloured elements */
        .light-mode .text-white {
            color: #0f172a !important;
        }

        .light-mode .text-mb-silver {
            color: #334155 !important;
        }

        .light-mode .text-mb-subtle {
            color: #64748b !important;
        }

        .light-mode .bg-mb-black {
            background-color: #f0f4f8 !important;
        }

        .light-mode .bg-mb-surface {
            background-color: #ffffff !important;
        }

        .light-mode .border-mb-subtle\/20 {
            border-color: rgba(15, 23, 42, 0.12) !important;
        }

        .light-mode .placeholder-mb-subtle::placeholder {
            color: #94a3b8 !important;
        }

        /* Sidebar & header in light mode */
        .light-mode aside {
            background-color: #ffffff !important;
            border-color: rgba(15, 23, 42, 0.08) !important;
        }

        .light-mode aside a {
            color: #475569;
        }

        .light-mode aside a:hover,
        .light-mode aside a.active {
            color: #0f172a !important;
        }

        .light-mode aside a.bg-mb-black {
            background-color: #e8eef5 !important;
        }

        .light-mode header {
            background-color: rgba(248, 250, 252, 0.85) !important;
            border-color: rgba(15, 23, 42, 0.08) !important;
        }

        .light-mode main>div {
            background: linear-gradient(135deg, #e8eef5, #f8fafc) !important;
        }

        /* Inputs in light mode */
        .light-mode input,
        .light-mode textarea,
        .light-mode select {
            background-color: #f8fafc !important;
            color: #0f172a !important;
            border-color: rgba(15, 23, 42, 0.15) !important;
        }

        /* Table rows */
        .light-mode tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }

        .light-mode thead {
            background-color: rgba(0, 0, 0, 0.04) !important;
        }

        .light-mode .divide-y>tr {
            border-color: rgba(0, 0, 0, 0.06) !important;
        }

        /* Background utility overrides */
        .light-mode .bg-mb-black\/50 {
            background-color: rgba(240, 244, 248, 0.9) !important;
        }

        .light-mode .bg-mb-black\/40 {
            background-color: rgba(240, 244, 248, 0.8) !important;
        }

        .light-mode .bg-mb-black\/30 {
            background-color: rgba(240, 244, 248, 0.7) !important;
        }

        .light-mode .bg-mb-black\/20 {
            background-color: rgba(240, 244, 248, 0.6) !important;
        }

        .light-mode .bg-mb-black\/60 {
            background-color: rgba(226, 232, 240, 0.9) !important;
        }

        .light-mode .from-mb-black {
            --tw-gradient-from: #e8eef5 !important;
        }

        .light-mode .to-mb-surface {
            --tw-gradient-to: #f8fafc !important;
        }
    </style>
</head>

<body
    class="bg-mb-black text-white font-sans antialiased h-screen flex selection:bg-mb-accent selection:text-white overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-mb-surface hidden md:flex flex-col border-r border-mb-subtle/20">
        <div class="h-20 flex items-center justify-center border-b border-mb-subtle/20">
            <!-- Logo Placeholder -->
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                </svg>
                <span class="text-xl font-light tracking-widest text-white uppercase">O Rent</span>
            </div>
        </div>

        <nav class="flex-1 py-8 space-y-2 px-4 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('dashboard') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                <span class="font-light">Dashboard</span>
            </a>

            <a href="{{ route('vehicles.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('vehicles.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2 2 2 0 002 2 2 2 0 11-2-2 2 2 0 00-2 2z">
                    </path>
                </svg>
                <span class="font-light">Vehicles</span>
            </a>

            <a href="{{ route('reservations.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('reservations.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="font-light">Reservations</span>
            </a>

            <a href="{{ route('clients.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('clients.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span class="font-light">Clients</span>
            </a>

            <!-- Expanded Modules -->
            <a href="{{ route('investments.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('investments.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span class="font-light">Investments</span>
            </a>

            <a href="{{ route('gps.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('gps.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-light">GPS Tracking</span>
            </a>

            <a href="{{ route('documents.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('documents.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="font-light">Papers</span>
            </a>

            <a href="{{ route('expenses.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('expenses.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="font-light">Expenses</span>
            </a>

            <a href="{{ route('challans.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('challans.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="font-light">Challans</span>
            </a>

            <a href="{{ route('staff.index') }}"
                class="flex items-center gap-4 px-4 py-3 hover:bg-mb-black hover:text-white transition-all rounded-md group {{ request()->routeIs('staff.*') ? 'bg-mb-black text-white border-l-2 border-mb-accent' : 'text-mb-silver' }}">
                <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="font-light">Staff</span>
            </a>
        </nav>

        <div class="p-4 border-t border-mb-subtle/20">
            <a href="#" class="flex items-center gap-3 px-2 py-2 text-mb-silver hover:text-white transition-colors">
                <div
                    class="w-8 h-8 rounded-full bg-mb-surface border border-mb-subtle flex items-center justify-center text-xs">
                    AS</div>
                <div class="flex-1">
                    <p class="text-sm font-medium">Abrar Salim</p>
                    <p class="text-xs text-mb-subtle">Admin</p>
                </div>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Header -->
        <header
            class="h-20 flex items-center justify-between px-8 bg-mb-black/50 backdrop-blur-md sticky top-0 z-50 border-b border-mb-subtle/10">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-light text-white tracking-wide">@yield('header', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-4">
                <!-- Theme Toggle -->
                <button id="theme-toggle" onclick="toggleTheme()" title="Switch theme"
                    class="relative w-9 h-9 rounded-full flex items-center justify-center border border-mb-subtle/20 hover:border-mb-accent/50 transition-all hover:bg-mb-accent/5 group">
                    <!-- Moon (dark mode icon) -->
                    <svg id="icon-moon" class="w-4.5 h-4.5 text-mb-silver group-hover:text-mb-accent transition-colors"
                        style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                    <!-- Sun (light mode icon) -->
                    <svg id="icon-sun"
                        class="w-4.5 h-4.5 text-mb-silver group-hover:text-mb-accent transition-colors hidden"
                        style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07-.71.71M6.34 17.66l-.71.71m12.73 0-.71-.71M6.34 6.34l-.71-.71M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                </button>

                <!-- Notification Bell -->
                <button
                    class="relative w-9 h-9 rounded-full flex items-center justify-center border border-mb-subtle/20 hover:border-mb-accent/50 transition-all hover:bg-mb-accent/5 group">
                    <svg class="w-4.5 h-4.5 text-mb-silver group-hover:text-mb-accent transition-colors"
                        style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto bg-gradient-to-br from-mb-black to-mb-surface p-8">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('select').select2({ width: '100%', theme: 'default' });
        });

        // ── Theme Toggle ──────────────────────────────────
        const root = document.getElementById('html-root');

        function applyThemeIcons() {
            const isLight = root.classList.contains('light-mode');
            document.getElementById('icon-moon').classList.toggle('hidden', isLight);
            document.getElementById('icon-sun').classList.toggle('hidden', !isLight);
        }

        function toggleTheme() {
            const isLight = root.classList.toggle('light-mode');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
            applyThemeIcons();
        }

        // Set correct icon on load
        document.addEventListener('DOMContentLoaded', applyThemeIcons);
    </script>
    <style>
        /* ── Select2 theming via CSS variables ─────────── */
        .select2-container--default .select2-selection--single {
            background-color: var(--select2-bg) !important;
            border: 1px solid var(--select2-border) !important;
            border-radius: 0.5rem !important;
            height: 46px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--select2-text) !important;
            padding-left: 1rem !important;
            line-height: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 10px !important;
        }

        .select2-dropdown {
            background-color: var(--select2-dd) !important;
            border: 1px solid var(--select2-border) !important;
            color: var(--select2-text) !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: var(--select2-search-bg) !important;
            border: 1px solid var(--select2-border) !important;
            color: var(--select2-text) !important;
            border-radius: 0.25rem !important;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #00adef !important;
            color: white !important;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: rgba(0, 173, 239, 0.15) !important;
        }

        .select2-results__option {
            color: var(--select2-text) !important;
        }

        /* Global Fade In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        main {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</body>

</html>