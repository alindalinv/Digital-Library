@extends('layouts.admin.app')

@section('content')

        {{-- ============ Header ============ --}}
        <div class="mb-5">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Edit Permission
            </h3>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                Update the permission name and group.
            </p>
        </div>

        {{-- ============ Form ============ --}}
        <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
            @csrf
            @method('PUT')

            @php
                // Split "users.create" into group="users", name="create"
                $parts = explode('.', $permission->name, 2);
                $currentGroup = count($parts) > 1 ? $parts[0] : '';
                $currentName  = count($parts) > 1 ? $parts[1] : $parts[0];
            @endphp

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Group --}}
                <div>
                    <label for="group" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Group
                    </label>
                    <input type="text"
                           id="group"
                           name="group"
                           value="{{ old('group', $currentGroup) }}"
                           placeholder="e.g. users, books, roles"
                           list="group-suggestions"
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                    <datalist id="group-suggestions">
                        @foreach($groups as $group)
                            <option value="{{ $group }}">
                        @endforeach
                    </datalist>

                    @error('group')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        Optional. Groups permissions like <code>users.create</code>.
                    </p>
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Permission Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $currentName) }}"
                           placeholder="e.g. create, edit, delete"
                           required
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ============ Preview ============ --}}
            <div class="mt-5 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-800/50">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                    Full permission name:
                </p>
                <p class="mt-1 font-mono text-sm text-gray-800 dark:text-white/90">
                    <span id="preview-group">{{ $currentGroup ?: '—' }}</span><span id="preview-dot">{{ $currentGroup ? '.' : '' }}</span><span id="preview-name">{{ $currentName }}</span>
                </p>
            </div>

            {{-- ============ Assigned Roles ============ --}}
            @if($permission->roles->count() > 0)
                <div class="mt-5 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-500/30 dark:bg-blue-500/10">
                    <p class="mb-2 text-sm font-medium text-blue-800 dark:text-blue-300">
                        <i class="fas fa-info-circle me-1"></i>
                        This permission is used by {{ $permission->roles->count() }} role(s):
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($permission->roles as $role)
                            <span class="rounded-full bg-white px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ============ Actions ============ --}}
            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.permissions.index') }}"
                   class="rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-[0.98] dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
            </div>

        </form>



    {{-- ============ Live Preview Script ============ --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const groupInput = document.getElementById('group');
                const nameInput  = document.getElementById('name');
                const previewGroup = document.getElementById('preview-group');
                const previewDot   = document.getElementById('preview-dot');
                const previewName  = document.getElementById('preview-name');

                function updatePreview() {
                    const group = groupInput.value.trim();
                    const name  = nameInput.value.trim();

                    previewGroup.textContent = group || '—';
                    previewDot.textContent   = group ? '.' : '';
                    previewName.textContent  = name || '...';
                }

                groupInput.addEventListener('input', updatePreview);
                nameInput.addEventListener('input', updatePreview);
            });
        </script>
    @endpush
@endsection