@extends('layouts.admin.app')

@section('content')

    @php
        $isAdminGuard = $role->guard_name === 'admin';
        $isWebGuard = $role->guard_name === 'web';

        $permissionsByGroup = $role->permissions
            ->sortBy('name')
            ->groupBy(function ($permission) {
                return str_contains($permission->name, '.')
                    ? \Illuminate\Support\Str::before($permission->name, '.')
                    : 'general';
            });

        $isProtected =
            ($isAdminGuard && in_array($role->name, ['Super Admin', 'Admin'], true))
            ||
            ($isWebGuard && $role->name === 'Member');
    @endphp

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex flex-wrap items-center gap-2">

                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Role: {{ $role->name }}
                </h3>

                {{-- Guard Badge --}}
                @if ($isAdminGuard)
                    <span
                        class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                        <i class="fas fa-shield-alt mr-1.5"></i>
                        admin
                    </span>
                @elseif ($isWebGuard)
                    <span
                        class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                        <i class="fas fa-users mr-1.5"></i>
                        web
                    </span>
                @endif

                {{-- Protected Badge --}}
                @if ($isProtected)
                    <span
                        class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                        <i class="fas fa-lock mr-1.5"></i>
                        Protected
                    </span>
                @endif

            </div>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if ($isAdminGuard)
                    Administrator role and permissions.
                @elseif ($isWebGuard)
                    Frontend / member role and permissions.
                @else
                    Role and permissions.
                @endif
            </p>
        </div>

    </div>


    {{-- Role Information --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

        {{-- Role --}}
        <div
            class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                <i class="fas fa-user-tag"></i>
            </div>

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Role
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                {{ $role->name }}
            </p>

        </div>


        {{-- Guard --}}
        <div
            class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div
                class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg
                {{ $isAdminGuard
                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400'
                    : 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400' }}">

                <i class="fas {{ $isAdminGuard ? 'fa-shield-alt' : 'fa-globe' }}"></i>
            </div>

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Guard
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                {{ $role->guard_name }}
            </p>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ $isAdminGuard ? 'Administrator authentication' : 'Frontend authentication' }}
            </p>

        </div>


        {{-- Users --}}
        <div
            class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div
                class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400">

                <i class="fas fa-users"></i>

            </div>

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Users
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                {{ $role->users->count() }}
            </p>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ Str::plural('user', $role->users->count()) }} assigned
            </p>

        </div>

    </div>


    {{-- Permissions --}}
    <div
        class="mb-6 rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

        <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Permissions
                </h4>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $role->permissions->count() }}
                    {{ Str::plural('permission', $role->permissions->count()) }}
                    assigned to this role.
                </p>
            </div>

            @if ($isAdminGuard)
                <span
                    class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                    Admin permissions
                </span>
            @elseif ($isWebGuard)
                <span
                    class="inline-flex w-fit items-center rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                    Frontend permissions
                </span>
            @endif

        </div>


        @if ($role->permissions->isEmpty())

            <div
                class="rounded-lg border border-dashed border-gray-300 px-4 py-8 text-center dark:border-gray-700">

                <div
                    class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">

                    <i class="fas fa-key"></i>

                </div>

                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    No permissions assigned
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    This role currently has no permissions.
                </p>

            </div>

        @else

            <div class="space-y-4">

                @foreach ($permissionsByGroup as $group => $permissions)

                    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">

                        {{-- Group Header --}}
                        <div
                            class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">

                            <h5 class="text-sm font-semibold capitalize text-gray-800 dark:text-white/90">
                                {{ $group }}
                            </h5>

                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $permissions->count() }}
                                {{ Str::plural('permission', $permissions->count()) }}
                            </span>

                        </div>


                        {{-- Permission List --}}
                        <div class="grid grid-cols-1 gap-2 p-4 sm:grid-cols-2 lg:grid-cols-3">

                            @foreach ($permissions as $permission)

                                <div
                                    class="flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2
                                           dark:bg-gray-800/50">

                                    <span
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full
                                               bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400">

                                        <i class="fas fa-check text-[10px]"></i>

                                    </span>

                                    <span
                                        class="break-all text-sm text-gray-700 dark:text-gray-300">
                                        {{ $permission->name }}
                                    </span>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>


    {{-- Users --}}
    <div
        class="mb-6 rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

        <div class="mb-5 flex items-center justify-between">

            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Users
                </h4>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Users currently assigned to this role.
                </p>
            </div>

            <span
                class="inline-flex items-center rounded-full bg-purple-50 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-500/10 dark:text-purple-400">
                {{ $role->users->count() }}
                {{ Str::plural('user', $role->users->count()) }}
            </span>

        </div>


        @if ($role->users->isEmpty())

            <div
                class="rounded-lg border border-dashed border-gray-300 px-4 py-8 text-center dark:border-gray-700">

                <div
                    class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">

                    <i class="fas fa-user-slash"></i>

                </div>

                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    No users assigned
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    No users currently have this role.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full text-left text-sm">

                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-800">

                            <th class="px-3 py-3 font-medium text-gray-500 dark:text-gray-400">
                                User
                            </th>

                            <th class="px-3 py-3 font-medium text-gray-500 dark:text-gray-400">
                                Email
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">

                        @foreach ($role->users as $user)

                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                <td class="px-3 py-3">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">

                                            {{ strtoupper(substr($user->displayName(), 0, 1)) }}

                                        </div>

                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $user->displayName() }}
                                        </span>

                                    </div>

                                </td>

                                <td class="px-3 py-3 text-gray-600 dark:text-gray-400">
                                    {{ $user->email }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    {{-- Actions --}}
    <div class="flex flex-wrap justify-end gap-3">

        @can('roles.update', 'admin')
            <a
                href="{{ route('admin.roles.edit', $role) }}"
                class="rounded-full bg-brand-500 px-4 py-2 text-sm font-semibold text-white
                       hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">

                <i class="fas fa-edit mr-1.5"></i>
                Edit Role

            </a>
        @endcan

        <a
            href="{{ route('admin.roles.index') }}"
            class="rounded-full border border-gray-300 px-4 py-2 text-sm font-medium
                   text-gray-700 hover:bg-gray-50
                   dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">

            <i class="fas fa-arrow-left mr-1.5"></i>
            Back to Roles

        </a>

    </div>

@endsection