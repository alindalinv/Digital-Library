@extends('layouts.admin.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="User Profile" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 lg:mb-7">
            Profile
        </h3>

        <x-admin.profile.profile-card :user="$user" />

        <x-admin.profile.personal-info-card :user="$user" />

        <x-admin.profile.address-card :user="$user" />
    </div>
@endsection