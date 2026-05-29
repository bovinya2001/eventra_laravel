<div class="relative">
    <button wire:click="toggleDropdown" 
        class="relative p-2 text-slate-400 hover:text-white transition-colors rounded-lg hover:bg-slate-800">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1 -translate-y-1 bg-red-600 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    @if($showDropdown)
    <div class="absolute right-0 mt-2 w-80 bg-slate-800/95 border border-slate-700 rounded-2xl shadow-2xl z-50 backdrop-blur">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-slate-700">
            <h3 class="font-semibold text-white">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-indigo-400 hover:text-indigo-300">
                    Mark all as read
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-slate-700/50 hover:bg-slate-700/30 transition-colors cursor-pointer {{ !$notification->isRead() ? 'bg-slate-700/20' : '' }}">
                    <div class="flex gap-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-{{ $notification->color }}-500/20 rounded-lg flex items-center justify-center">
                                @if($notification->icon === 'check-circle')
                                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($notification->icon === 'clock')
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.447.894l1.447 1.447a1 1 0 001.414-1.414L11 9.618V6z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white">{{ $notification->title }}</p>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $notification->message }}</p>
                            <p class="text-xs text-slate-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-1 flex-shrink-0">
                            @if(!$notification->isRead())
                                <button wire:click="markAsRead({{ $notification->id }})" class="text-slate-400 hover:text-indigo-400 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            @endif
                            <button wire:click="deleteNotification({{ $notification->id }})" class="text-slate-400 hover:text-red-400 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-slate-700 text-center">
            <a href="#" class="text-sm text-indigo-400 hover:text-indigo-300">View all notifications →</a>
        </div>
    </div>
    @endif
</div>
