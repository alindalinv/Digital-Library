<x-guest-layout>
    <div class="mb-4 small text-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-frontend.auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <x-frontend.input-label for="email" :value="__('Email')" />
            <x-frontend.text-input id="email" class="d-block mt-1 w-100" type="email" name="email" :value="old('email')" required autofocus />
            <x-frontend.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center justify-content-end mt-4">
            <x-frontend.primary-button>
                {{ __('Email Password Reset Link') }}
            </x-frontend.primary-button>
        </div>
    </form>
</x-guest-layout>