<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-[10px] border border-transparent bg-[#1a1523] px-4 py-2.5 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-[#2d1f4e] focus:bg-[#2d1f4e] focus:outline-none focus:ring-2 focus:ring-[#8b5cf6] focus:ring-offset-2 active:bg-[#1a1523] disabled:opacity-50']) }}>
    {{ $slot }}
</button>
