@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-[#1a1523]']) }}>
    {{ $value ?? $slot }}
</label>
