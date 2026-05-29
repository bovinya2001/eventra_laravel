<div>
    @if($message)
        <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium
            {{ str_contains($message, 'Successfully') ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
            {{ $message }}
        </div>
    @endif

    @if($isRegistered)
        <button wire:click="cancel"
            wire:loading.attr="disabled"
            class="w-full bg-red-600/20 hover:bg-red-600/30 border border-red-500/50 text-red-400 font-semibold py-3 rounded-xl transition-all">
            <span wire:loading.remove wire:target="cancel">Cancel Registration</span>
            <span wire:loading wire:target="cancel">Cancelling...</span>
        </button>
    @else
        <button wire:click="register"
            wire:loading.attr="disabled"
            @if($event->remaining_capacity <= 0) disabled @endif
            class="w-full font-semibold py-3 rounded-xl transition-all
                {{ $event->remaining_capacity > 0
                    ? 'bg-indigo-600 hover:bg-indigo-500 text-white hover:shadow-[0_0_20px_rgba(99,102,241,0.4)]'
                    : 'bg-slate-700 text-slate-500 cursor-not-allowed' }}">
            <span wire:loading.remove wire:target="register">
                {{ $event->remaining_capacity > 0 ? 'Register Now' : 'Fully Booked' }}
            </span>
            <span wire:loading wire:target="register">Registering...</span>
        </button>
    @endif
</div>
