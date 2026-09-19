<x-guest-layout>
    <!-- Session Status -->
    <x-frontend.auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <x-frontend.input-label for="email" :value="__('Email')" />
            <x-frontend.text-input id="email" class="d-block mt-1 w-100" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-frontend.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-frontend.input-label for="password" :value="__('Password')" />

            <x-frontend.text-input id="password" class="d-block mt-1 w-100"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-frontend.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label small text-muted">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="d-flex align-items-center justify-content-end mt-4">
            @if (Route::has('password.request'))
                <a class="small text-muted text-decoration-underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-frontend.primary-button class="ms-3">
                {{ __('Log in') }}
            </x-frontend.primary-button>
        </div>
    </form>
</x-guest-layout>