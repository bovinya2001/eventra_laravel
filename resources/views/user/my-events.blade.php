<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Events — Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <nav class="bg-slate-900/80 border-b border-white/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('events.index') }}" class="text-slate-400 hover:text-white transition-colors">← Browse Events</a>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white text-sm transition-colors">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-slate-400 hover:text-red-400 text-sm transition-colors">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-6 py-12">
        <h1 class="text-3xl font-bold mb-2">My Registered Events</h1>
        <p class="text-slate-400 mb-8">Events you've signed up for</p>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500/30 text-green-400 rounded-xl px-4 py-3 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($registrations as $registration)
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-lg">{{ $registration->event->title }}</h3>
                    <div class="flex items-center gap-4 mt-2 text-sm text-slate-400">
                        <span>📍 {{ $registration->event->location }}</span>
                        <span>📅 {{ $registration->event->event_date->format('M d, Y') }}</span>
                    </div>
                    <div class="mt-2">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $registration->status === 'confirmed' ? 'bg-green-500/20 text-green-400' : 'bg-slate-600/50 text-slate-400' }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-3">
                    <a href="{{ route('events.show', $registration->event) }}"
                        class="text-indigo-400 hover:text-indigo-300 text-sm transition-colors">View →</a>
                    @if($registration->event->status === 'upcoming')
                    <form method="POST" action="{{ route('events.cancel', $registration->event) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-300 text-xs transition-colors">Cancel</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-20 text-slate-500">
                <p class="text-lg font-medium mb-2">No registrations yet</p>
                <a href="{{ route('events.index') }}" class="text-indigo-400 hover:underline">Browse events →</a>
            </div>
            @endforelse
        </div>
    </div>
</body>
</html>