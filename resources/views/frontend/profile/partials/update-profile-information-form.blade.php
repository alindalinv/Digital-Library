<section>
    <header>
        <h2 class="fs-5 fw-medium text-dark">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 small text-muted">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 d-flex flex-column gap-4">
        @csrf
        @method('patch')

        <div>
            <x-frontend.input-label for="name" :value="__('Name')" />
            <x-frontend.text-input id="name" name="name" type="text" class="mt-1 d-block w-100" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-frontend.input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-frontend.input-label for="email" :value="__('Email')" />
            <x-frontend.text-input id="email" name="email" type="email" class="mt-1 d-block w-100" :value="old('email', $user->email)" required autocomplete="username" />
            <x-frontend.input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="small mt-2 text-dark mb-0">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0 small text-muted text-decoration-underline align-baseline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success small mt-2 mb-0 py-2">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-frontend.primary-button>{{ __('Save') }}</x-frontend.primary-button>

            @if (session('status') === 'profile-updated')
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