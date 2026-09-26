@extends('layouts.admin.app')

@section('content')
{{-- ============ Header ============ --}}
<div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Roles
        </h3>

        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
            Manage user roles and their permissions.
        </p>
    </div>

    <div class="flex flex-col gap-2 sm:flex-row">
        {{-- Search --}}
        <form
            method="GET"
            action="{{ route('admin.roles.index') }}"
            class="w-full sm:w-64"
        >
            <label for="role-search" class="sr-only">
                Search roles
            </label>

            <div class="relative">
                <svg
                    class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"
                    />
                </svg>

                <input
                    id="role-search"
                    type="search"
                    name="search"
                    value="{{ $search ?? request('search', '') }}"
                    placeholder="Search roles..."
                    autocomplete="off"
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-2 pl-9 pr-3 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500"
                />
            </div>
        </form>

        @can('roles.create', 'admin')
            <a
                href="{{ route('admin.roles.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/30"
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                New Role
            </a>
        @endcan
    </div>
</div>

{{-- ============ Flash Messages ============ --}}
@if (session('success'))
    <div class="mb-4 flex items-center gap-2 rounded-lg bg-green-50 p-3 text-sm text-green-700 dark:bg-green-500/15 dark:text-green-400">
        <svg
            class="h-5 w-5 shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7"
            />
        </svg>

        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 flex items-center gap-2 rounded-lg bg-red-50 p-3 text-sm text-red-700 dark:bg-red-500/15 dark:text-red-400">
        <svg
            class="h-5 w-5 shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20.5h15.6a2 2 0 001.73-3.14l-7.82-13.5a2 2 0 00-3.42 0z"
            />
        </svg>

        <span>{{ session('error') }}</span>
    </div>
@endif

{{-- ============ Roles Table ============ --}}
<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">

    <div class="overflow-x-auto">
        <table class="w-full min-w-[750px] text-sm">

            <thead>
                <tr class="border-b border-gray-200 bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500 dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-400">

                    <th class="px-6 py-3 font-medium">
                        Role
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Guard
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Users
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Permissions
                    </th>

                    <th class="px-6 py-3 text-right font-medium">
                        Actions
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                @forelse ($roles as $role)

                    @php
                        /*
                         * Protected roles are guard-specific.
                         *
                         * Admin guard:
                         * - Super Admin
                         * - Admin
                         *
                         * Web guard:
                         * - Member
                         */
                        $isProtected =
                            ($role->guard_name === 'admin' &&
                                in_array($role->name, [
                                    'Super Admin',
                                    'Admin',
                                ], true))
                            ||
                            ($role->guard_name === 'web' &&
                                $role->name === 'Member');

                        $hasUsers = (int) $role->users_count > 0;
                    @endphp

                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">

                        {{-- Role --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">

                                <span class="font-medium text-gray-800 dark:text-white/90">
                                    {{ $role->name }}
                                </span>

                                @if ($isProtected)
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-500/15 dark:text-amber-400">
                                        Protected
                                    </span>
                                @endif

                            </div>
                        </td>

                        {{-- Guard --}}
                        <td class="px-6 py-4">

                            @if ($role->guard_name === 'admin')

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                    admin
                                </span>

                            @elseif ($role->guard_name === 'web')

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-xs font-medium text-purple-600 dark:bg-purple-500/10 dark:text-purple-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                                    web
                                </span>

                            @else

                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                    {{ $role->guard_name }}
                                </span>

                            @endif

                        </td>

                        {{-- Users --}}
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            <span class="inline-flex items-center gap-1.5">

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H3v-2a4 4 0 014-4h3m4-5a4 4 0 11-8 0 4 4 0 018 0zm6 1a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>

                                {{ $role->users_count }}

                            </span>
                        </td>

                        {{-- Permissions --}}
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            <span class="inline-flex items-center gap-1.5">

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 7a3 3 0 11-6 0 3 3 0 016 0zM5 20a7 7 0 0114 0M19 11h2m-1-1v2"
                                    />
                                </svg>

                                {{ $role->permissions_count }}

                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">

                            <div class="flex items-center justify-end gap-1">

                                {{-- View --}}
                                @can('roles.view', 'admin')
                                    <a
                                        href="{{ route('admin.roles.show', $role) }}"
                                        title="View role"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </a>
                                @endcan

                                {{-- Edit --}}
                                @can('roles.update', 'admin')

                                    <a
                                        href="{{ route('admin.roles.edit', $role) }}"
                                        title="Edit role"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50 hover:text-blue-700 dark:text-blue-400 dark:hover:bg-blue-500/15 dark:hover:text-blue-300"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            />
                                        </svg>
                                    </a>

                                @endcan

                                {{-- Delete --}}
                                @can('roles.delete', 'admin')

                                    @if ($isProtected)

                                        <button
                                            type="button"
                                            disabled
                                            title="Protected role — cannot be deleted"
                                            class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-lg text-gray-300 dark:text-gray-700"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                        </button>

                                    @elseif ($hasUsers)

                                        <button
                                            type="button"
                                            disabled
                                            title="Cannot delete — {{ $role->users_count }} user(s) assigned"
                                            class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-lg text-gray-300 dark:text-gray-700"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                        </button>

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('admin.roles.destroy', $role) }}"
                                            class="inline"
                                            data-delete-role-form
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Delete role"
                                                data-delete-role
                                                data-role-name="{{ $role->name }}"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-500/15 dark:hover:text-red-300"
                                            >
                                                <svg
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16"
                                                    />
                                                </svg>
                                            </button>
                                        </form>

                                    @endif

                                @endcan

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">

                            <div class="flex flex-col items-center gap-2">

                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <svg
                                        class="h-6 w-6 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 15l2 2 4-4m5-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>

                                <p class="font-medium text-gray-600 dark:text-gray-300">
                                    No roles found.
                                </p>

                                @if (!empty($search))
                                    <p class="text-sm text-gray-400 dark:text-gray-500">
                                        No roles match "{{ $search }}".
                                    </p>
                                @endif

                                @can('roles.create', 'admin')
                                    <a
                                        href="{{ route('admin.roles.create') }}"
                                        class="mt-1 text-sm font-medium text-brand-500 hover:text-brand-600 hover:underline"
                                    >
                                        Create your first role
                                    </a>
                                @endcan

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>

</div>

{{-- ============ Pagination ============ --}}
@if ($roles->hasPages())
    <div class="mt-4">
        {{ $roles->withQueryString()->links() }}
    </div>
@endif

@endsection

@push('scripts') <script>
(() => {
'use strict';
        document.addEventListener('click', (event) => {
            const button = event.target.closest('[data-delete-role]');

            if (!button) {
                return;
            }

            const roleName = button.dataset.roleName || 'this role';

            const confirmed = window.confirm(
                `Delete role "${roleName}"?\n\nThis action cannot be undone.`
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    })();
</script>
@endpush
