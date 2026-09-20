@extends('layouts.admin.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="Create Permission" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            Create Permission
        </h3>

        <form method="POST" action="{{ route('admin.permissions.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="group" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Group
                    </label>
                    <input type="text" id="group" name="group"
                           value="{{ old('group') }}"
                           placeholder="e.g. users, books, roles"
                           list="group-suggestions"
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90">
                    <datalist id="group-suggestions">
                        @foreach($groups as $group)
                            <option value="{{ $group }}">
                        @endforeach
                    </datalist>
                    @error('group')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Permission Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. create, edit, delete"
                           required
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.permissions.index') }}"
                   class="rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                    Create Permission
                </button>
            </div>
        </form>
    </div>
@endsection