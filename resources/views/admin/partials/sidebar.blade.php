<aside class="w-64 bg-slate-800/50 border-r border-white/10 flex flex-col min-h-screen fixed">
    <div class="p-6 border-b border-white/10">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <div class="font-bold text-sm">Eventra</div>
                <div class="text-xs text-indigo-400">Admin Panel</div>
            </div>
        </div>
    </div>
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/20 text-indigo-300' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.events.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                {{ request()->routeIs('admin.events*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Events
        </a>
        <a href="{{ route('landing') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            View Site
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
            <button class="text-sm text-slate-400 hover:text-red-400 transition-colors">→ Logout</button>
        </form>
    </div>
</aside>
