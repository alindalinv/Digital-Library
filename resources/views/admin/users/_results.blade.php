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
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $users->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @forelse ($user->roles as $role)
                                <span class="badge bg-primary-subtle text-primary-emphasis">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if ($user->status)
                            <span
                                class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">Active</span>
                        @else
                            <span
                                class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.users.show', $user) }}"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400">View</a>
                        @can('users.update')
                            <a href="{{ route('admin.users.roles.edit', $user) }}"
                                class="ml-3 text-brand-500 hover:text-brand-600">Roles</a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="ml-3 text-brand-500 hover:text-brand-600">Edit</a>
                        @endcan
                        @can('users.delete')
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Delete this user?')"
                                    class="ml-3 text-red-500 hover:text-red-600">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $users->links() }}</div>