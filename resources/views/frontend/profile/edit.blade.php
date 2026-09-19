<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="d-flex flex-column gap-4">

                <div class="p-4 p-sm-5 bg-white shadow-sm rounded-3">
                    <div style="max-width: 36rem;">
                        @include('frontend.profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 p-sm-5 bg-white shadow-sm rounded-3">
                    <div style="max-width: 36rem;">
                        @include('frontend.profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 p-sm-5 bg-white shadow-sm rounded-3">
                    <div style="max-width: 36rem;">
                        @include('frontend.profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>