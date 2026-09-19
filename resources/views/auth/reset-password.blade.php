<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <x-frontend.input-label for="email" :value="__('Email')" />
            <x-frontend.text-input id="email" class="d-block mt-1 w-100" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-frontend.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-frontend.input-label for="password" :value="__('Password')" />
            <x-frontend.text-input id="password" class="d-block mt-1 w-100" type="password" name="password" required autocomplete="new-password" />
            <x-frontend.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <x-frontend.input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-frontend.text-input id="password_confirmation" class="d-block mt-1 w-100"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-frontend.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center justify-content-end mt-4">
            <x-frontend.primary-button>
                {{ __('Reset Password') }}
            </x-frontend.primary-button>
        </div>
    </form>
</x-guest-layout>