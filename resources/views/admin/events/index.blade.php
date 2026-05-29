<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events — Eventra Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex">

    @include('admin.partials.sidebar')

    <main class="ml-64 flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold">Events</h1>
                <p class="text-slate-400 text-sm mt-1">Manage all events</p>
            </div>
            <a href="{{ route('admin.events.create') }}"
                class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl font-medium transition-colors">
                + New Event
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-400 rounded-xl px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-slate-800/50 border border-white/10 rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead class="border-b border-white/10">
                    <tr class="text-left text-slate-400 text-sm">
                        <th class="px-6 py-4 font-medium">Event</th>
                        <th class="px-6 py-4 font-medium">Date</th>
                        <th class="px-6 py-4 font-medium">Registrations</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($events as $event)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $event->title }}</div>
                            <div class="text-slate-500 text-xs mt-1">{{ $event->location }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-300">
                            {{ $event->event_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="text-white font-medium">{{ $event->registrations_count }}</span>
                            <span class="text-slate-500"> / {{ $event->capacity }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                @if($event->status === 'upcoming') bg-green-500/20 text-green-400
                                @elseif($event->status === 'ongoing') bg-blue-500/20 text-blue-400
                                @elseif($event->status === 'cancelled') bg-red-500/20 text-red-400
                                @else bg-slate-600/50 text-slate-400 @endif">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.events.edit', $event) }}"
                                    class="text-indigo-400 hover:text-indigo-300 text-sm transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                    onsubmit="return confirm('Delete this event?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-300 text-sm transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">No events yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-white/10">
                {{ $events->links() }}
            </div>
        </div>
    </main>
</body>
</html>