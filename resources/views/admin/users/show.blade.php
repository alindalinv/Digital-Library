@extends('layouts.admin.app')

@section('content')
<div class="mx-auto max-w-4xl">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">User Details</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">View full profile and permissions</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
            @can('users.update')
            <a href="{{ route('admin.users.edit', $user) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            @endcan
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-4">
            {{-- Avatar --}}
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-brand-500 text-xl font-semibold text-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            {{-- Name & Email --}}
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            </div>

            {{-- Status badge --}}
            <div>
                @if ($user->status)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span>
                        Disabled
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-400">Account Information</h3>

        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <dt class="text-xs uppercase text-gray-400">Full Name</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">{{ $user->name }}</dd>
            </div>

            <div>
                <dt class="text-xs uppercase text-gray-400">Email Address</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">{{ $user->email }}</dd>
            </div>

            <div>
                <dt class="text-xs uppercase text-gray-400">Phone</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                    {{ $user->phone ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase text-gray-400">Gender</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                    {{ $user->gender ? ucfirst($user->gender) : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase text-gray-400">Date of Birth</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                    {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase text-gray-400">Organization</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                    {{ $user->organization ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase text-gray-400">Job Title</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                    {{ $user->job_title ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase text-gray-400">Member Since</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                    {{ $user->created_at?->format('M d, Y') ?? '—' }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-xs uppercase text-gray-400">Address</dt>
                <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                    {{ $user->address ?? '—' }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-xs uppercase text-gray-400">Bio</dt>
                <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                    {{ $user->bio ?? '—' }}
                </dd>
            </div>
        </dl>
    </div>

    {{-- Roles & Permissions --}}
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Roles --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Roles</h3>
                @can('users.update')
                <a href="{{ route('admin.users.roles.edit', $user) }}"
                    class="text-xs font-medium text-brand-500 hover:text-brand-600">Manage</a>
                @endcan
            </div>

            @forelse ($user->roles as $role)
                <div class="mb-2 flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-3 py-2 dark:border-gray-800 dark:bg-gray-800/50">
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $role->name }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $role->permissions->count() }} perms
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400">No roles assigned.</p>
            @endforelse
        </div>

        {{-- Direct Permissions --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-400">
                Direct Permissions
            </h3>

            @php
                $directPerms = $user->getDirectPermissions();
            @endphp

            @forelse ($directPerms as $perm)
                <span class="mb-1 mr-1 inline-flex rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                    {{ $perm->name }}
                </span>
            @empty
                <p class="text-sm text-gray-400">No direct permissions (inherited from roles).</p>
            @endforelse
        </div>
    </div>

    {{-- All Effective Permissions --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-400">
            All Effective Permissions
        </h3>

        @php
            $allPerms = $user->getAllPermissions()->pluck('name')->sort();
            $grouped = [];
            foreach ($allPerms as $perm) {
                $prefix = explode('.', $perm)[0];
                $grouped[$prefix][] = $perm;
            }
        @endphp

        @forelse ($grouped as $group => $perms)
            <div class="mb-3">
                <p class="mb-1.5 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    {{ $group }}
                </p>
                <div class="flex flex-wrap gap-1">
                    @foreach ($perms as $perm)
                        <span class="inline-flex rounded-md bg-gray-100 px-2 py-0.5 text-xs text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            {{ $perm }}
                        </span>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400">No permissions assigned.</p>
        @endforelse
    </div>

    {{-- Social Links --}}
    @if ($user->facebook || $user->twitter || $user->linkedin || $user->instagram)
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-400">Social Links</h3>

        <div class="flex flex-wrap gap-3">
            @if ($user->facebook)
                <a href="{{ $user->facebook }}" target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Facebook
                </a>
            @endif
            @if ($user->twitter)
                <a href="{{ $user->twitter }}" target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Twitter
                </a>
            @endif
            @if ($user->linkedin)
                <a href="{{ $user->linkedin }}" target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    LinkedIn
                </a>
            @endif
            @if ($user->instagram)
                <a href="{{ $user->instagram }}" target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Instagram
                </a>
            @endif
        </div>
    </div>
    @endif

    {{-- Danger Zone --}}
    @can('users.delete')
    @if ($user->id !== auth()->id())
    <div class="rounded-xl border border-red-200 bg-red-50 p-6 dark:border-red-500/20 dark:bg-red-500/5">
        <h3 class="mb-2 text-sm font-semibold uppercase tracking-wider text-red-600 dark:text-red-400">
            Danger Zone
        </h3>
        <p class="mb-4 text-sm text-red-700 dark:text-red-300">
            Deleting this user is permanent and cannot be undone.
        </p>

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this user?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete User
            </button>
        </form>
    </div>
    @endif
    @endcan

</div>
@endsection