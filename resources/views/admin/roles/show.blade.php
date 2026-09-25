@extends('layouts.admin.app')

@section('content')

        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            Role: {{ $role->name }}
        </h3>

        <div class="mb-6">
            <h4 class="mb-2 text-sm font-semibold text-gray-800 dark:text-white/90">
                Permissions ({{ $role->permissions->count() }})
            </h4>

            @if($role->permissions->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No permissions assigned.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($role->permissions as $permission)
                        <span
                            class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                            {{ $permission->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h4 class="mb-2 text-sm font-semibold text-gray-800 dark:text-white/90">
                Users ({{ $role->users->count() }})
            </h4>

            @if($role->users->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No users assigned.</p>
            @else
                <ul class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                    @foreach($role->users as $user)
                        <li>{{ $user->displayName() }} — {{ $user->email }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.roles.edit', $role) }}"
                class="rounded-full bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 dark:bg-blue-500">
                Edit
            </a>
            <a href="{{ route('admin.roles.index') }}"
                class="rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                Back
            </a>
        </div>

@endsection