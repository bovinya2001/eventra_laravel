<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-xl font-bold text-white">
                My Dashboard
            </h2>
            <a href="{{ route('events.index') }}"
                class="bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
                Browse Events
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6 space-y-8">

            <!-- Welcome Banner -->
            <div class="relative overflow-hidden bg-[#12121a] border border-violet-500/20 rounded-3xl p-8">
                <div class="absolute top-0 right-0 w-64 h-64 bg-violet-600 rounded-full filter blur-[100px] opacity-20"></div>
                <div class="relative">
                    <p class="text-violet-300 text-sm font-medium mb-1 uppercase tracking-widest">Your event space</p>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ auth()->user()->name }} 👋</h1>
                    <p class="text-slate-400">Here's what's happening with your events.</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $registrations = auth()->user()->registrations()->with('event')->latest()->get();
                    $upcoming = $registrations->filter(fn($r) => $r->event->status === 'upcoming')->count();
                    $attended = $registrations->filter(fn($r) => $r->event->status === 'completed')->count();
                @endphp

                @foreach([
                    ['label' => 'Total Registrations', 'value' => $registrations->count(), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'indigo'],
                    ['label' => 'Upcoming Events', 'value' => $upcoming, 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'green'],
                    ['label' => 'Events Attended', 'value' => $attended, 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'color' => 'purple'],
                ] as $stat)
                <div class="bg-[#12121a] border border-white/5 rounded-2xl p-6 hover:border-violet-500/25 transition-colors">
                    <div class="w-10 h-10 bg-violet-600/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="font-display text-3xl font-black text-white">{{ $stat['value'] }}</div>
                    <div class="text-[#8888aa] text-sm mt-1">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>

            <!-- My Registered Events -->
            <div class="bg-[#12121a] border border-white/5 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display text-lg font-bold text-white">My Registered Events</h2>
                    <a href="{{ route('my.events') }}" class="text-violet-400 hover:text-violet-300 text-sm transition-colors">
                        View all →
                    </a>
                </div>

                @if($registrations->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-700/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-400 font-medium">No events yet</p>
                    <p class="text-slate-500 text-sm mt-1">Start by browsing our upcoming events</p>
                    <a href="{{ route('events.index') }}"
                        class="inline-block mt-4 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
                        Browse Events
                    </a>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($registrations->take(5) as $registration)
                    <div class="flex items-center justify-between py-4 border-b border-white/5 last:border-0">
                        <div class="flex items-center gap-4">
                            <!-- Color dot based on status -->
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                @if($registration->event->status === 'upcoming') bg-green-500/20
                                @elseif($registration->event->status === 'ongoing') bg-blue-500/20
                                @else bg-slate-700/50 @endif">
                                <svg class="w-5 h-5
                                    @if($registration->event->status === 'upcoming') text-green-400
                                    @elseif($registration->event->status === 'ongoing') text-blue-400
                                    @else text-slate-500 @endif"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-white text-sm">{{ $registration->event->title }}</p>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-slate-500 text-xs">
                                        📅 {{ $registration->event->event_date->format('M d, Y') }}
                                    </span>
                                    <span class="text-slate-500 text-xs">
                                        📍 {{ $registration->event->location }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                @if($registration->event->status === 'upcoming') bg-green-500/20 text-green-400
                                @elseif($registration->event->status === 'ongoing') bg-blue-500/20 text-blue-400
                                @else bg-slate-600/50 text-slate-400 @endif">
                                {{ ucfirst($registration->event->status) }}
                            </span>
                            <a href="{{ route('events.show', $registration->event) }}"
                                class="text-violet-400 hover:text-violet-300 text-xs transition-colors">
                                View →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Browse More Events -->
            <div class="bg-[#12121a] border border-violet-500/20 rounded-2xl p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-white">Discover more events</h3>
                    <p class="text-slate-400 text-sm mt-1">Find your next unforgettable experience</p>
                </div>
                <a href="{{ route('events.index') }}"
                    class="bg-violet-600 hover:bg-violet-500 text-white font-medium px-5 py-2.5 rounded-xl transition-all hover:shadow-[0_0_20px_rgba(124,58,237,.4)] whitespace-nowrap">
                    Browse Events →
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
