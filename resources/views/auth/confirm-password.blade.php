<x-guest-layout>
    <div class="mb-4 small text-muted">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3">
            <x-frontend.input-label for="password" :value="__('Password')" />

            <x-frontend.text-input id="password" class="d-block mt-1 w-100"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-frontend.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="d-flex justify-content-end mt-4">
            <x-frontend.primary-button>
                {{ __('Confirm') }}
            </x-frontend.primary-button>
        </div>
    </form>
</x-guest-layout>