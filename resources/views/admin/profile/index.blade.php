@extends('layouts.admin.app')

@section('content')
    <div x-data="{
            openProfileHeaderModal: false,
            openProfileInfoModal: false
         }"
         @open-profile-header-modal.window="openProfileHeaderModal = true"
         @open-profile-info-modal.window="openProfileInfoModal = true"
         @keydown.escape.window="openProfileHeaderModal = false; openProfileInfoModal = false">


            <h3 class="mb-5 text-lg font-semibold text-gray-800 lg:mb-7 dark:text-white/90">
                Profile
            </h3>

            <x-admin.profile.profile-card :user="$user" />
            <x-admin.profile.personal-info-card :user="$user" />

        {{-- Modals INSIDE the Alpine scope --}}
        <x-admin.profile.profile-header-modal :user="$user" />
        <x-admin.profile.edit-modal :user="$user" />

    </div>
@endsection