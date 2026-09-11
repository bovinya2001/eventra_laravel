<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Events — Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white min-h-screen">

    <!-- Nav -->
    <nav class="bg-slate-900/80 backdrop-blur-md border-b border-white/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('events.index') }}" class="flex items-center gap-2">
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
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-bold mb-2">Saved Events</h1>
            <p class="text-slate-400">Events you've bookmarked for later</p>
        </div>

        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $favorite)
                <div class="group bg-slate-800/50 border border-white/10 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all hover:shadow-[0_0_20px_rgba(99,102,241,0.2)]">
                    <!-- Image -->
                    @if($favorite->event->image)
                        <img src="{{ Storage::url($favorite->event->image) }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $favorite->event->title }}">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-indigo-900 to-purple-900 flex items-center justify-center">
                            <svg class="w-16 h-16 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="p-4">
                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                @if($favorite->event->status === 'upcoming') bg-green-500/20 text-green-400
                                @elseif($favorite->event->status === 'ongoing') bg-blue-500/20 text-blue-400
                                @else bg-slate-600/50 text-slate-400 @endif">
                                {{ ucfirst($favorite->event->status) }}
                            </span>
                            <span class="text-xs text-slate-500">{{ $favorite->event->remaining_capacity }} left</span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-bold text-white mb-2 line-clamp-2">{{ $favorite->event->title }}</h3>

                        <!-- Meta Info -->
                        <div class="space-y-2 mb-4 text-sm text-slate-400">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $favorite->event->event_date->format('M d, Y') }}
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $favorite->event->location }}
                            </div>
                        </div>

                        <!-- Price and CTA -->
                        <div class="flex items-center justify-between">
                            <div class="text-lg font-bold text-indigo-400">
                                {{ $favorite->event->price > 0 ? 'LKR ' . number_format($favorite->event->price, 0) : 'Free' }}
                            </div>
                            <a href="{{ route('events.show', $favorite->event) }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium transition-colors">
                                View →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <p class="text-slate-400 font-medium">No saved events yet</p>
                <p class="text-slate-500 text-sm mt-1">Start saving events to keep track of them</p>
                <a href="{{ route('events.index') }}" class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
                    Browse Events
                </a>
            </div>
        @endif
    </div>

</body>
</html>
