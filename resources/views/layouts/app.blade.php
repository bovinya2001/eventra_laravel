<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Eventra') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: white; }
        [x-cloak] { display: none !important; }
    </style>
    {{ $style ?? '' }}
</head>
<body class="min-h-screen bg-slate-900">

    <!-- Top Navigation -->
    <nav class="bg-slate-900/80 backdrop-blur-md border-b border-white/10 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-white">Eventra</span>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('dashboard') }}"
                        class="text-sm transition-colors {{ request()->routeIs('dashboard') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="text-sm transition-colors {{ request()->routeIs('events*') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">
                        Events
                    </a>
                    <a href="{{ route('my.events') }}"
                        class="text-sm transition-colors {{ request()->routeIs('my.events') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">
                        My Events
                    </a>
                    <a href="{{ route('favorites') }}"
                        class="text-sm transition-colors {{ request()->routeIs('favorites') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">
                        ❤️ Saved
                    </a>
                </div>

                <!-- Notifications & User Dropdown -->
                <div class="flex items-center gap-4" x-data="{ open: false }">
                    <!-- Notification Bell -->
                    @livewire('notification-bell')

                    <button @click="open = !open"
                        class="flex items-center gap-2 bg-slate-800 hover:bg-slate-700 border border-white/10 rounded-xl px-3 py-2 transition-colors">
                        <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center text-xs font-bold text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm text-white hidden md:block">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-6 top-16 w-48 bg-slate-800 border border-white/10 rounded-2xl shadow-xl overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-white/10">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center gap-2 px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- Page Header -->
    @if (isset($header))
    <header class="bg-slate-800/30 border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-4">
            {{ $header }}
        </div>
    </header>
    @endif

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    @livewireScripts
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>