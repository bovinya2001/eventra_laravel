<div class="space-y-8">
    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4">
        <input type="text" placeholder="Search events or venues..." wire:model.live="search"
            class="flex-1 bg-[#12121a] border border-white/5 text-white placeholder-[#8888aa] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-500/50">
        
        <select wire:model.live="status" class="bg-[#12121a] border border-white/5 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-500/50">
            <option value="">All Statuses</option>
            <option value="upcoming">Upcoming</option>
            <option value="ongoing">Ongoing</option>
            <option value="completed">Completed</option>
        </select>

        <select wire:model.live="sort" class="bg-[#12121a] border border-white/5 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-500/50">
            <option value="event_date">Soonest First</option>
            <option value="-event_date">Latest First</option>
            <option value="title">Title (A-Z)</option>
        </select>
    </div>

    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
            <div class="group bg-[#12121a] border border-white/5 rounded-2xl overflow-hidden hover:border-violet-500/30 transition-all hover:shadow-[0_0_24px_rgba(124,58,237,.14)]">
                <!-- Image -->
                @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $event->title }}">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-violet-950 to-[#1a1a26] flex items-center justify-center">
                        <svg class="w-16 h-16 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                <!-- Content -->
                <div class="p-4">
                    <!-- Status Badge -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                            @if($event->status === 'upcoming') bg-green-500/20 text-green-400
                            @elseif($event->status === 'ongoing') bg-blue-500/20 text-blue-400
                            @else bg-slate-600/50 text-slate-400 @endif">
                            {{ ucfirst($event->status) }}
                        </span>
                        <span class="text-xs text-slate-500">{{ $event->remaining_capacity }} left</span>
                    </div>

                    <!-- Title -->
                    <h3 class="font-display text-lg font-bold text-white mb-2 line-clamp-2">{{ $event->title }}</h3>

                    <!-- Meta Info -->
                    <div class="space-y-2 mb-4 text-sm text-[#8888aa]">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $event->event_date->format('M d, Y') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $event->location }}
                        </div>
                    </div>

                    <!-- Price and CTA -->
                    <div class="flex items-center justify-between">
                        <div class="font-display text-lg font-bold text-violet-400">
                            {{ $event->price > 0 ? 'LKR ' . number_format($event->price, 0) : 'Free' }}
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="bg-violet-600 hover:bg-violet-500 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                            View →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-slate-400 font-medium">No events found</p>
            <p class="text-slate-500 text-sm mt-1">Try adjusting your search or filters</p>
        </div>
    @endif
</div>
