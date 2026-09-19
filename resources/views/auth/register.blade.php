<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <x-frontend.input-label for="name" :value="__('Name')" />
            <x-frontend.text-input id="name" class="d-block mt-1 w-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-frontend.input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <x-frontend.input-label for="email" :value="__('Email')" />
            <x-frontend.text-input id="email" class="d-block mt-1 w-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
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
            <x-frontend.text-input id="password_confirmation" class="d-block mt-1 w-100" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-frontend.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center justify-content-end mt-4">
            <a class="small text-muted text-decoration-underline" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-frontend.primary-button class="ms-4">
                {{ __('Register') }}
            </x-frontend.primary-button>
        </div>
    </form>
</x-guest-layout>