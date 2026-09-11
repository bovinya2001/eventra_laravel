@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-[10px] border-[1.5px] border-[#ddd6fe] bg-[#f5f3ff] shadow-none focus:border-[#8b5cf6] focus:ring-[#8b5cf6]']) !!}>
