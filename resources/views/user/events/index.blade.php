<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events — Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @livewireStyles
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white min-h-screen">

    <!-- Nav -->
    <nav class="bg-slate-900/80 backdrop-blur-md border-b border-white/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="font-bold text-lg">Eventra</span>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white text-sm transition-colors">Dashboard</a>
                    <a href="{{ route('my.events') }}" class="text-slate-400 hover:text-white text-sm transition-colors">My Events</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-slate-400 hover:text-white text-sm transition-colors">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-400 hover:text-white text-sm">Login</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm px-4 py-2 rounded-xl transition-colors">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-bold mb-2">Upcoming Events</h1>
            <p class="text-slate-400">Discover and register for amazing experiences</p>
        </div>

        {{-- Livewire Search Component --}}
        @livewire('event-search')
    </div>

    @livewireScripts
</body>
</html>
