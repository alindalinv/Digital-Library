@extends('layouts.admin.app')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-6 text-2xl font-semibold text-gray-800 dark:text-white">Add User</h1>

        <form action="{{ route('admin.users.store') }}" method="POST"
            class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="first_name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        First name
                    </label>
                    <input type="text" id="first_name" name="first_name" placeholder="Jane" value="{{ old('first_name') }}"
                        required autofocus
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('first_name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="last_name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Last name
                    </label>
                    <input type="text" id="last_name" name="last_name" placeholder="Doe" value="{{ old('last_name') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('last_name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @error('email')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>

            {{-- ============================================================
            USER ROLES
            ============================================================ --}}

            <div class="space-y-6">

                {{-- ========================================================
                ADMIN ROLES
                ======================================================== --}}
                <div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Admin Roles
                        </label>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Select an admin role only if this user should access
                            the administration panel.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                        @forelse ($adminRoles as $role)

                                <label for="admin-role-{{ $role->id }}"
                                    class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">

                                    <input id="admin-role-{{ $role->id }}" type="checkbox" name="admin_roles[]"
                                        value="{{ $role->id }}" @checked(
                                            in_array(
                                                (string) $role->id,
                                                array_map(
                                                    'strval',
                                                    old(
                                                        'admin_roles',
                                                        $adminRoleIds ?? []
                                                    )
                                                ),
                                                true
                                            )
                                        )
                              class="mt-0.5 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">

                                    <span class="min-w-0 flex-1">

                                        <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $role->name }}
                                        </span>

                                        <span class="mt-0.5 block text-xs text-gray-400 dark:text-gray-500">
                                            Admin
                                        </span>

                                    </span>

                                </label>

                        @empty

                            <div
                                class="col-span-full rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                No admin roles are available.
                            </div>

                        @endforelse

                    </div>

                    @error('admin_roles')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('admin_roles.*')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- ========================================================
                FRONTEND ROLE
                ======================================================== --}}
                <div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Frontend Role
                        </label>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            The default frontend role is Member. Member always uses
                            the <code>web</code> guard.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                        @forelse ($frontendRoles as $role)

                                    <label for="frontend-role-{{ $role->id }}"
                                        class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">

                                        <input id="frontend-role-{{ $role->id }}" type="checkbox" name="frontend_roles[]"
                                            value="{{ $role->id }}" @checked(
                                                in_array(
                                                    (string) $role->id,
                                                    array_map(
                                                        'strval',
                                                        old(
                                                            'frontend_roles',
                                                            $frontendRoleIds ?? []
                                                        )
                                                    ),
                                                    true
                                                )
                                            )
                            class="mt-0.5 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">

                                        <span class="min-w-0 flex-1">

                                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $role->name }}
                                            </span>

                                            <span class="mt-0.5 block text-xs text-gray-400 dark:text-gray-500">
                                                Frontend / Web
                                            </span>

                                        </span>

                                    </label>

                        @empty

                            <div
                                class="col-span-full rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                No frontend roles are available.
                            </div>

                        @endforelse

                    </div>

                    @error('frontend_roles')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('frontend_roles.*')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>
            <div>
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" checked class="rounded border-gray-300 text-brand-500">
                    Active
                </label>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.users.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Create User
                </button>
            </div>
        </form>
    </div>
@endsection