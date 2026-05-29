<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Eventra Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-800/50 border-r border-white/10 flex flex-col min-h-screen fixed">
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-sm">Eventra</div>
                    <div class="text-xs text-indigo-400">Admin Panel</div>
                </div>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-600/20 text-indigo-300 font-medium text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-sm transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Events
            </a>
        </nav>
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="w-full text-left text-sm text-slate-400 hover:text-red-400 transition-colors px-2 py-1">
                    → Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="ml-64 flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-slate-400 text-sm mt-1">Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach([
                ['label'=>'Total Events','value'=>$stats['total_events'],'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','color'=>'indigo'],
                ['label'=>'Total Users','value'=>$stats['total_users'],'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z','color'=>'purple'],
                ['label'=>'Registrations','value'=>$stats['total_registrations'],'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'blue'],
                ['label'=>'Upcoming','value'=>$stats['upcoming_events'],'icon'=>'M13 10V3L4 14h7v7l9-11h-7z','color'=>'green'],
            ] as $stat)
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-{{ $stat['color'] }}-600/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-{{ $stat['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold">{{ $stat['value'] }}</div>
                <div class="text-slate-400 text-sm mt-1">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>

        <!-- Recent Events -->
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold">Recent Events</h2>
                <a href="{{ route('admin.events.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm px-4 py-2 rounded-xl transition-colors">+ New Event</a>
            </div>
            <div class="space-y-3">
                @forelse($recentEvents as $event)
                <div class="flex items-center justify-between py-3 border-b border-white/5">
                    <div>
                        <div class="font-medium text-sm">{{ $event->title }}</div>
                        <div class="text-slate-500 text-xs mt-1">{{ $event->event_date->format('M d, Y') }} · {{ $event->location }}</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($event->status === 'upcoming') bg-green-500/20 text-green-400
                        @elseif($event->status === 'ongoing') bg-blue-500/20 text-blue-400
                        @else bg-slate-600/50 text-slate-400 @endif">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
                @empty
                <p class="text-slate-500 text-sm">No events yet.</p>
                @endforelse
            </div>
        </div>
    </main>
</body>
</html>