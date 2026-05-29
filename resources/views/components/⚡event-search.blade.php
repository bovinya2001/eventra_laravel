<div>
    <!-- Search & Filter Bar -->
    <div class="flex flex-col md:flex-row gap-4 mb-8">
        <div class="flex-1 relative">
            <svg class="absolute left-4 top-3.5 w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search events or locations..."
                class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <select wire:model.live="status"
            class="bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Status</option>
            <option value="upcoming">Upcoming</option>
            <option value="ongoing">Ongoing</option>
            <option value="completed">Completed</option>
        </select>
        <select wire:model.live="sort"
            class="bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="event_date">Date</option>
            <option value="title">Name</option>
            <option value="price">Price</option>
        </select>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="text-center py-4">
        <div class="inline-flex items-center gap-2 text-indigo-400">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
            Searching...
        </div>
    </div>

    <!-- Events Grid -->
    <div wire:loading.remove class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all hover:-translate-y-1 duration-300">
            @if($event->image)
                <img src="{{ Storage::url($event->image) }}" class="w-full h-48 object-cover" alt="{{ $event->title }}">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-indigo-900 to-purple-900 flex items-center justify-center">
                    <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium px-3 py-1 rounded-full
                        @if($event->status === 'upcoming') bg-green-500/20 text-green-400
                        @elseif($event->status === 'ongoing') bg-blue-500/20 text-blue-400
                        @else bg-slate-600/50 text-slate-400 @endif">
                        {{ ucfirst($event->status) }}
                    </span>
                    <span class="text-indigo-400 font-bold text-sm">
                        {{ $event->price > 0 ? 'LKR ' . number_format($event->price, 2) : 'Free' }}
                    </span>
                </div>
                <h3 class="font-bold text-white mb-2">{{ $event->title }}</h3>
                <p class="text-slate-400 text-sm mb-4 line-clamp-2">{{ $event->description }}</p>
                <div class="flex items-center gap-4 text-xs text-slate-500 mb-4">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $event->location }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $event->event_date->format('M d, Y') }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        {{ $event->remaining_capacity }} spots left
                    </span>
                    <a href="{{ route('events.show', $event) }}"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-20 text-slate-500">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-medium">No events found</p>
            <p class="text-sm mt-1">Try adjusting your search or filters</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $events->links() }}</div>
</div>