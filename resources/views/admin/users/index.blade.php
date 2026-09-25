@extends('layouts.admin.app')

@section('content')
        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Users</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage system users and their roles</p>
            </div>

            @can('users.create')
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add User
                </a>
            @endcan
        </div>

        <form id="user-filters" method="GET" action="{{ route('admin.users.index') }}" class="mb-5 flex flex-col gap-3 sm:flex-row">
            <label class="sr-only" for="user-search">Search users</label>
            <input id="user-search" name="search" value="{{ $search ?? '' }}" placeholder="Search by name or email"
                class="w-full max-w-md rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm dark:border-gray-700 dark:text-white">
            <label class="sr-only" for="user-status">Filter by status</label>
            <select id="user-status" name="status" class="rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm dark:border-gray-700 dark:text-white">
                <option value="all" @selected(($status ?? 'all') === 'all')>All statuses</option>
                <option value="active" @selected(($status ?? 'all') === 'active')>Active</option>
                <option value="inactive" @selected(($status ?? 'all') === 'inactive')>Inactive</option>
            </select>
        </form>

        <div id="users-results">
        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400">#</th>
                        <th class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400">Name</th>
                        <th class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400">Email</th>
                        <th class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400">Roles</th>
                        <th class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-6 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($user->roles as $role)
                                        <span
                                            class="inline-flex rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">No role</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->status)
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                        Disabled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.users.show', $user) }}"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400">View</a>
                                @can('users.update')
                                    <a href="{{ route('admin.users.roles.edit', $user) }}"
                                        class="text-brand-500 hover:text-brand-600">Roles</a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="ml-3 text-brand-500 hover:text-brand-600">Edit</a>
                                @endcan

                                @can('users.delete')
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this user?')"
                                            class="ml-3 text-red-500 hover:text-red-600">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>
        </div>

@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('user-filters');
            const results = document.getElementById('users-results');
            const searchInput = document.getElementById('user-search');
            const statusInput = document.getElementById('user-status');
            let debounceTimer;
            let activeRequest;

            const buildUrl = () => {
                const params = new URLSearchParams();
                if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
                if (statusInput.value !== 'all') params.set('status', statusInput.value);
                const query = params.toString();
                return `${form.action}${query ? `?${query}` : ''}`;
            };

            const fetchResults = async (url, pushState = true) => {
                activeRequest?.abort();
                activeRequest = new AbortController();

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    signal: activeRequest.signal,
                });
                if (!response.ok) throw new Error(`HTTP ${response.status}`);

                const data = await response.json();
                results.innerHTML = data.html;
                if (pushState) window.history.replaceState({}, '', url);
            };

            const refresh = () => fetchResults(buildUrl()).catch(error => {
                if (error.name !== 'AbortError') console.error('Unable to load users', error);
            });

            form.addEventListener('submit', event => { event.preventDefault(); refresh(); });
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(refresh, 300);
            });
            statusInput.addEventListener('change', refresh);
            results.addEventListener('click', event => {
                const link = event.target.closest('a[href*="page="]');
                if (!link) return;
                event.preventDefault();
                fetchResults(link.href);
            });
        })();
    </script>
@endpush