<div>
    <button wire:click="toggleFavorite" 
        class="flex items-center gap-2 px-4 py-2 rounded-xl font-medium transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-400 {{ $isFavorited ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-[#12121a] text-[#8888aa] border border-white/5 hover:text-white hover:border-violet-500/30' }}">
        <svg class="w-5 h-5 {{ $isFavorited ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        {{ $isFavorited ? 'Saved' : 'Save Event' }}
    </button>
</div>
