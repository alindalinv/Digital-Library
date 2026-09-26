@extends('layouts.admin.app')

@section('content')

    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Edit Role: {{ $role->name }}
            </h3>

            <div class="mt-1 flex items-center gap-2">
                @if ($role->guard_name === 'admin')
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                        Admin Guard
                    </span>
                @elseif ($role->guard_name === 'web')
                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                        Web / Frontend Guard
                    </span>
                @endif
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')

        {{-- Role Information --}}
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-4">
                <label for="name"
                       class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Role Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $role->name) }}"
                    @disabled(
                        ($role->guard_name === 'admin' &&
                            in_array($role->name, ['Super Admin', 'Admin'], true))
                        ||
                        ($role->guard_name === 'web' &&
                            $role->name === 'Member')
                    )
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm
                           text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none
                           disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500
                           dark:border-gray-700 dark:text-white/90 dark:disabled:bg-gray-800/50 dark:disabled:text-gray-500"
                >

                @if (
                    ($role->guard_name === 'admin' &&
                        in_array($role->name, ['Super Admin', 'Admin'], true))
                    ||
                    ($role->guard_name === 'web' &&
                        $role->name === 'Member')
                )
                    {{-- Disabled fields are not submitted, so preserve the name --}}
                    <input type="hidden" name="name" value="{{ $role->name }}">

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        This is a protected system role name and cannot be changed.
                    </p>
                @endif

                @error('name')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Guard --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Guard
                </label>

                <div class="flex items-center gap-2">
                    @if ($role->guard_name === 'admin')
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                            <i class="fas fa-shield-alt mr-1.5"></i>
                            admin
                        </span>

                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Used for administrator accounts.
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700 dark:bg-green-500/10 dark:text-green-400">
                            <i class="fas fa-users mr-1.5"></i>
                            web
                        </span>

                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Used for frontend/member accounts.
                        </span>
                    @endif
                </div>
            </div>

        </div>


        {{-- Permissions --}}
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-4">
                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Permissions
                </h4>

                @if ($role->guard_name === 'web')
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Select the permissions that frontend members are allowed to use.
                    </p>
                @else
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Select the permissions that administrators with this role are allowed to use.
                    </p>
                @endif
            </div>

            @if ($permissions->isEmpty())

                <div class="rounded-lg border border-dashed border-gray-300 px-4 py-8 text-center dark:border-gray-700">
                    <i class="fas fa-key mb-2 text-xl text-gray-400"></i>

                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        No permissions available
                    </p>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        No permissions have been created for the
                        <strong>{{ $role->guard_name }}</strong> guard.
                    </p>
                </div>

            @else

                @php
                    $selectedPermissions = old('permissions', $rolePermissions ?? []);
                @endphp

                @foreach($permissions as $group => $items)

                    <div class="mb-4 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">

                        {{-- Permission Group Header --}}
                        <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">

                            <h5 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ ucfirst($group) }}
                            </h5>

                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $items->count() }}
                                {{ Str::plural('permission', $items->count()) }}
                            </span>

                        </div>

                        {{-- Permissions --}}
                        <div class="grid grid-cols-1 gap-2 p-4 sm:grid-cols-2 lg:grid-cols-3">

                            @foreach($items as $permission)

                                <label
                                    class="flex cursor-pointer items-center gap-2 rounded-lg border border-transparent p-2 text-sm
                                           text-gray-700 transition hover:border-gray-200 hover:bg-gray-50
                                           dark:text-gray-300 dark:hover:border-gray-700 dark:hover:bg-gray-800/50"
                                >

                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        @checked(in_array($permission->name, $selectedPermissions, true))
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600
                                               focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800"
                                    >

                                    <span>
                                        {{ $permission->name }}
                                    </span>

                                </label>

                            @endforeach

                        </div>
                    </div>

                @endforeach

            @endif

            @error('permissions')
                <p class="mt-2 text-xs text-red-500 dark:text-red-400">
                    {{ $message }}
                </p>
            @enderror

            @error('permissions.*')
                <p class="mt-2 text-xs text-red-500 dark:text-red-400">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- Actions --}}
        <div class="flex justify-end gap-3">

            <a
                href="{{ route('admin.roles.index') }}"
                class="rounded-full border border-gray-300 px-4 py-2 text-sm font-medium
                       text-gray-700 hover:bg-gray-50
                       dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-full bg-brand-500 px-4 py-2 text-sm font-semibold text-white
                       hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600"
            >
                <i class="fas fa-save mr-1.5"></i>
                Save Changes
            </button>

        </div>

    </form>

@endsection