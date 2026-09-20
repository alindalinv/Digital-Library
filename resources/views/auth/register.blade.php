<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- ============ Name Row ============ --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <x-frontend.input-label for="first_name" :value="__('First Name')" />
                <x-frontend.text-input
                    id="first_name"
                    class="d-block mt-1 w-100"
                    type="text"
                    name="first_name"
                    :value="old('first_name')"
                    required
                    autofocus
                    autocomplete="given-name" />
                <x-frontend.input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div class="col-md-6">
                <x-frontend.input-label for="last_name" :value="__('Last Name')" />
                <x-frontend.text-input
                    id="last_name"
                    class="d-block mt-1 w-100"
                    type="text"
                    name="last_name"
                    :value="old('last_name')"
                    required
                    autocomplete="family-name" />
                <x-frontend.input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        {{-- ============ Email ============ --}}
        <div class="mb-3">
            <x-frontend.input-label for="email" :value="__('Email')" />
            <x-frontend.text-input
                id="email"
                class="d-block mt-1 w-100"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username" />
            <x-frontend.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- ============ Phone (optional) ============ --}}
        <div class="mb-3">
            <x-frontend.input-label for="phone" :value="__('Phone (optional)')" />
            <x-frontend.text-input
                id="phone"
                class="d-block mt-1 w-100"
                type="text"
                name="phone"
                :value="old('phone')"
                autocomplete="tel" />
            <x-frontend.input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        {{-- ============ Password Row ============ --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <x-frontend.input-label for="password" :value="__('Password')" />
                <x-frontend.text-input
                    id="password"
                    class="d-block mt-1 w-100"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password" />
                <x-frontend.input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="col-md-6">
                <x-frontend.input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-frontend.text-input
                    id="password_confirmation"
                    class="d-block mt-1 w-100"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password" />
                <x-frontend.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        {{-- ============ Terms Checkbox (optional) ============ --}}
        {{-- <div class="form-check mb-3">
            <input type="checkbox"
                   name="terms"
                   id="terms"
                   class="form-check-input @error('terms') is-invalid @enderror"
                   {{ old('terms') ? 'checked' : '' }}
                   required>
            <label class="form-check-label small text-muted" for="terms">
                I agree to the
                <a href="#" class="text-decoration-underline">Terms of Service</a>
                and
                <a href="#" class="text-decoration-underline">Privacy Policy</a>
            </label>
            <x-frontend.input-error :messages="$errors->get('terms')" class="mt-2" />
        </div> --}}

        {{-- ============ Actions ============ --}}
        <div class="d-flex align-items-center justify-content-between mt-4">
            <a class="small text-muted text-decoration-underline" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-frontend.primary-button>
                {{ __('Register') }}
            </x-frontend.primary-button>
        </div>
    </form>
</x-guest-layout>