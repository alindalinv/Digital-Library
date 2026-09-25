@extends('layouts.admin.app')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold text-gray-800 dark:text-white">Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST"
        class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="first_name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    First name
                </label>
                <input type="text" id="first_name" name="first_name"
                    placeholder="Jane"
                    value="{{ old('first_name', $user->first_name ?? '') }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @error('first_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="last_name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Last name
                </label>
                <input type="text" id="last_name" name="last_name"
                    placeholder="Doe"
                    value="{{ old('last_name', $user->last_name ?? '') }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @error('last_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Password <span class="text-xs text-gray-400">(leave blank to keep current)</span>
            </label>
            <input type="password" name="password"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
            <input type="password" name="password_confirmation"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Roles</label>
            <div class="space-y-2">
                @foreach ($roles as $role)
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                            {{ $user->hasRole($role->name) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-brand-500">
                        {{ $role->name }}
                    </label>
                @endforeach
            </div>
        </div>
        {{-- Status --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Account Status
            </label>

            {{-- Hidden fallback: sends 0 when checkbox is unchecked --}}
            <input type="hidden" name="status" value="0">

            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox"
                    name="status"
                    value="1"
                    {{ old('status', $user->status) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                Active
            </label>

            @error('status')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.users.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                Cancel
            </a>
            <button type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Update User
            </button>
        </div>
    </form>

@endsection