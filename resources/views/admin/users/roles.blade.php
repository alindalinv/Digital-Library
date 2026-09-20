@extends('layouts.admin.app')

@section('content')
<div class="mx-auto max-w-3xl">
    <h1 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white">Assign Roles</h1>
    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
        Editing roles for <strong>{{ $user->name }}</strong> ({{ $user->email }})
    </p>

    <form action="{{ route('admin.users.roles.update', $user) }}" method="POST"
        class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        @csrf
        @method('PUT')

        <div class="space-y-3">
            @foreach ($roles as $role)
                <label class="flex items-start gap-3 rounded-lg border border-gray-200 p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        {{ in_array($role->name, $userRoles) ? 'checked' : '' }}
                        class="mt-0.5 rounded border-gray-300 text-brand-500">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $role->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $role->permissions->count() }} permissions
                        </p>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.users.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                Cancel
            </a>
            <button type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Save Roles
            </button>
        </div>
    </form>
</div>
@endsection