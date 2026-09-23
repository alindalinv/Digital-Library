@extends('layouts.admin.app')

@section('content')

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            Permission: {{ $permission->name }}
        </h3>

        <div class="mb-6">
            <h4 class="mb-2 text-sm font-semibold text-gray-800 dark:text-white/90">
                Assigned Roles ({{ $permission->roles->count() }})
            </h4>

            @if($permission->roles->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No roles use this permission yet.
                </p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($permission->roles as $role)
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.permissions.edit', $permission) }}"
               class="rounded-full bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 dark:bg-blue-500">
                Edit
            </a>
            <a href="{{ route('admin.permissions.index') }}"
               class="rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                Back
            </a>
        </div>
    </div>
@endsection