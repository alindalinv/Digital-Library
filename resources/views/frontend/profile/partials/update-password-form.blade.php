<section>
    <header>
        <h2 class="fs-5 fw-medium text-dark">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 small text-muted">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 d-flex flex-column gap-4">
        @csrf
        @method('put')

        <div>
            <x-frontend.input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-frontend.text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 d-block w-100" autocomplete="current-password" />
            <x-frontend.input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-frontend.input-label for="update_password_password" :value="__('New Password')" />
            <x-frontend.text-input id="update_password_password" name="password" type="password" class="mt-1 d-block w-100" autocomplete="new-password" />
            <x-frontend.input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-frontend.input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-frontend.text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 d-block w-100" autocomplete="new-password" />
            <x-frontend.input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-frontend.primary-button>{{ __('Save') }}</x-frontend.primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="small text-muted mb-0"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>