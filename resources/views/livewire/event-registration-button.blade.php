<div>
    @if($isRegistered)
        <button wire:click="cancel" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-3 rounded-xl transition-all">
            Cancel Registration
        </button>
    @elseif($event->remaining_capacity > 0)
        <button wire:click="register" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition-all">
            Register Now
        </button>
    @else
        <button disabled class="w-full bg-slate-700 text-slate-400 font-bold py-3 rounded-xl cursor-not-allowed">
            Fully Booked
        </button>
    @endif

    @if($message)
        <p class="text-center text-sm mt-3 font-medium {{ strpos($message, 'Successfully') !== false ? 'text-green-400' : 'text-red-400' }}">
            {{ $message }}
        </p>
    @endif
</div>
