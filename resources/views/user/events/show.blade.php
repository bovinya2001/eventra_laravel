<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} — Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @livewireStyles
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white min-h-screen">

    <nav class="bg-slate-900/80 backdrop-blur-md border-b border-white/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('events.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors">
                ← Back to Events
            </a>
            @auth
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white text-sm transition-colors">Dashboard</a>
                <a href="{{ route('my.events') }}" class="text-slate-400 hover:text-white text-sm transition-colors">My Events</a>
            </div>
            @endauth
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-6 py-12">
        @if($event->image)
            <img src="{{ Storage::url($event->image) }}" class="w-full h-72 object-cover rounded-2xl mb-8" alt="{{ $event->title }}">
        @else
            <div class="w-full h-72 bg-gradient-to-br from-indigo-900 to-purple-900 rounded-2xl mb-8 flex items-center justify-center">
                <svg class="w-24 h-24 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($event->status === 'upcoming') bg-green-500/20 text-green-400
                        @elseif($event->status === 'ongoing') bg-blue-500/20 text-blue-400
                        @else bg-slate-600/50 text-slate-400 @endif">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold mb-4">{{ $event->title }}</h1>
                <p class="text-slate-400 leading-relaxed mb-6">{{ $event->description }}</p>

                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['label'=>'Date & Time','value'=>$event->event_date->format('F d, Y · g:i A'),'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['label'=>'Location','value'=>$event->location,'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                        ['label'=>'Capacity','value'=>$event->capacity . ' total · ' . $event->remaining_capacity . ' left','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['label'=>'Price','value'=>$event->price > 0 ? 'LKR ' . number_format($event->price, 2) : 'Free','icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ] as $info)
                    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/>
                            </svg>
                            <span class="text-xs text-slate-500">{{ $info['label'] }}</span>
                        </div>
                        <div class="text-sm font-medium">{{ $info['value'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Registration Sidebar -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 h-fit">
                <div class="text-2xl font-bold mb-1">
                    {{ $event->price > 0 ? 'LKR ' . number_format($event->price, 2) : 'Free' }}
                </div>
                <p class="text-slate-400 text-sm mb-6">{{ $event->remaining_capacity }} spots remaining</p>

                <!-- Livewire Registration Button -->
                @livewire('event-registration-button', ['event' => $event])

                <!-- Favorite Button -->
                <div class="mt-4">
                    @livewire('favorite-button', ['event' => $event])
                </div>

                @guest
                <p class="text-center text-slate-500 text-xs mt-3">
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:underline">Login</a> to register
                </p>
                @endguest
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>