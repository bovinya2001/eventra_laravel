<div class="min-h-screen flex flex-col justify-center items-center bg-[#eeeaf8] px-6 py-10 sm:px-8">
    <div class="w-full max-w-md">
        {{ $logo }}

        <div class="mt-6 overflow-hidden rounded-2xl bg-white px-6 py-7 shadow-[0_4px_24px_rgba(100,70,200,0.07),0_1px_4px_rgba(0,0,0,0.04)] sm:px-8">
            {{ $slot }}
        </div>
    </div>
</div>
