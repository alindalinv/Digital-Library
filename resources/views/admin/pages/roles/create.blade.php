@extends('layouts.admin.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="Create Role" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            Create Role
        </h3>

        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Role Name
                </label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Permissions
                </label>

                @foreach($permissions as $group => $items)
                    <div class="mb-4 rounded-lg border border-gray-200 p-3 dark:border-gray-800">
                        <h4 class="mb-2 text-sm font-semibold text-gray-800 dark:text-white/90">
                            {{ ucfirst($group) }}
                        </h4>
                        <div class="grid grid-cols-2 gap-2 md:grid-cols-3">
                            @foreach($items as $permission)
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission->name }}"
                                           {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800">
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.roles.index') }}"
                   class="rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-full bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                    Create Role
                </button>
            </div>
        </form>
    </div>
@endsection