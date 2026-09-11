<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Before continuing, enter the six-digit verification code we sent to your email address.') }}
        </div>

        <div class="mb-4 text-sm font-medium text-gray-700">
            {{ auth()->user()->email }}
        </div>

        @if (config('mail.default') === 'log')
            <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                {{ __('Email delivery is using the local log mailer. The verification code is being written to storage/logs/laravel.log instead of being sent to your inbox.') }}
            </div>
        @endif

        @if (session('status') == 'verification-otp-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification code has been sent to your email address.') }}
            </div>
        @endif

        @if ($errors->has('code'))
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first('code') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.otp.verify') }}" class="mt-4">
            @csrf

            <div>
                <x-label for="code" value="Verification code" />
                <x-input id="code" class="block mt-1 w-full text-center tracking-[0.5em]" type="text" name="code"
                    inputmode="numeric" autocomplete="one-time-code" maxlength="6" required autofocus />
            </div>

            <div class="mt-4">
                <x-button type="submit" class="w-full justify-center">
                    {{ __('Verify Email') }}
                </x-button>
            </div>
        </form>

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.otp.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        {{ __('Send New Code') }}
                    </x-button>
                </div>
            </form>

            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ __('Edit Profile') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
