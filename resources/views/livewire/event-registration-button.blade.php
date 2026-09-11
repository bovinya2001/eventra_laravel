<div>
    @if($isRegistered)
        <button wire:click="cancel" class="w-full bg-red-600 hover:bg-red-500 text-white font-semibold py-3 rounded-xl transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-400">
            Cancel Registration
        </button>
    @elseif($event->remaining_capacity > 0)
        <button wire:click="register" class="w-full bg-violet-600 hover:bg-violet-500 text-white font-semibold py-3 rounded-xl transition-all hover:shadow-[0_0_20px_rgba(124,58,237,.35)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-400">
            Register Now
        </button>
    @else
        <button disabled class="w-full bg-white/5 text-[#8888aa] font-semibold py-3 rounded-xl cursor-not-allowed">
            Fully Booked
        </button>
    @endif

    @if($message)
        <p class="text-center text-sm mt-3 font-medium {{ strpos($message, 'Successfully') !== false ? 'text-green-400' : 'text-red-400' }}">
            {{ $message }}
        </p>
    @endif
</div>
