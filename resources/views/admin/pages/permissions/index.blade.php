@extends('layouts.admin.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="Permissions" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">

        {{-- ============ Header ============ --}}
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Permissions
                </h3>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Manage individual permissions that can be assigned to roles.
                </p>
            </div>

            <div class="flex gap-2">
                <form method="GET" class="flex-1 sm:flex-initial">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search permissions..."
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">
                </form>

                <a href="{{ route('admin.permissions.create') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    New Permission
                </a>
            </div>
        </div>

        {{-- ============ Flash Messages ============ --}}
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700 dark:bg-green-500/15 dark:text-green-400">
                <i class="fas fa-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700 dark:bg-red-500/15 dark:text-red-400">
                <i class="fas fa-exclamation-circle me-1"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- ============ Table ============ --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-xs uppercase text-gray-500 dark:border-gray-800 dark:text-gray-400">
                        <th class="pb-3 font-medium">Name</th>
                        <th class="pb-3 font-medium">Group</th>
                        <th class="pb-3 font-medium">Roles</th>
                        <th class="pb-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        @php
                            $group = \Illuminate\Support\Str::before($permission->name, '.');
                            $hasRoles = $permission->roles_count > 0;
                        @endphp

                        <tr class="border-b border-gray-100 transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50">

                            {{-- Name --}}
                            <td class="py-3">
                                <span class="font-medium text-gray-800 dark:text-white/90">
                                    {{ $permission->name }}
                                </span>
                            </td>

                            {{-- Group --}}
                            <td class="py-3">
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                                    {{ $group }}
                                </span>
                            </td>

                            {{-- Roles Count --}}
                            <td class="py-3 text-gray-600 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-user-shield text-xs"></i>
                                    {{ $permission->roles_count }}
                                </span>
                            </td>

                            {{-- ============ Actions ============ --}}
                            <td class="py-3">
                                <div class="flex items-center justify-end gap-1">

                                    {{-- View --}}
                                    <a href="{{ route('admin.permissions.show', $permission) }}"
                                       title="View permission"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.permissions.edit', $permission) }}"
                                       title="Edit permission"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50 hover:text-blue-700 dark:text-blue-400 dark:hover:bg-blue-500/15 dark:hover:text-blue-300">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    @if($hasRoles)
                                        <button type="button"
                                                disabled
                                                title="Cannot delete — {{ $permission->roles_count }} role(s) use this permission"
                                                class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-lg text-gray-300 dark:text-gray-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        <form method="POST"
                                              action="{{ route('admin.permissions.destroy', $permission) }}"
                                              onsubmit="return confirm('Delete permission \'{{ $permission->name }}\'?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="Delete permission"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-500/15 dark:hover:text-red-300">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="text-4xl">🔐</div>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        No permissions found.
                                    </p>
                                    <a href="{{ route('admin.permissions.create') }}"
                                       class="text-sm text-blue-600 hover:underline dark:text-blue-400">
                                        Create your first permission
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ============ Pagination ============ --}}
        @if($permissions->hasPages())
            <div class="mt-4">
                {{ $permissions->links() }}
            </div>
        @endif

    </div>
@endsection