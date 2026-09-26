<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private const ADMIN_GUARD = 'admin';
    private const WEB_GUARD = 'web';

    /**
     * Roles that cannot be renamed or deleted.
     *
     * Permissions for these roles can still be edited.
     */
    private const PROTECTED_ROLES = [
        self::ADMIN_GUARD => [
            'Super Admin',
            'Admin',
        ],

        self::WEB_GUARD => [
            'Member',
        ],
    ];


    /**
     * Display all managed roles.
     *
     * Includes:
     * - admin roles
     * - web / frontend roles such as Member
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $roles = Role::query()
            ->whereIn('guard_name', [
                self::ADMIN_GUARD,
                self::WEB_GUARD,
            ])
            ->withCount([
                'users',
                'permissions',
            ])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByRaw(
                "CASE
                    WHEN guard_name = 'admin' THEN 1
                    WHEN guard_name = 'web' THEN 2
                    ELSE 3
                END"
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.roles.index', [
            'title' => 'Roles',
            'roles' => $roles,
            'search' => $search,
        ]);
    }


    /**
     * Show create role form.
     *
     * Default guard is admin.
     */
    public function create(Request $request): View
    {
        $guard = $this->normalizeGuard(
            $request->input('guard', self::ADMIN_GUARD)
        );

        return view('admin.roles.create', [
            'title' => 'Create Role',
            'guard' => $guard,
            'permissions' => $this->permissionsForGuard($guard),
        ]);
    }


    /**
     * Store a new role.
     */
    public function store(Request $request): RedirectResponse
    {
        $guard = $this->normalizeGuard(
            $request->input('guard', self::ADMIN_GUARD)
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',

                Rule::unique('roles', 'name')
                    ->where(function (Builder $query) use ($guard) {
                        $query->where('guard_name', $guard);
                    }),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'string',

                Rule::exists('permissions', 'name')
                    ->where(function (Builder $query) use ($guard) {
                        $query->where('guard_name', $guard);
                    }),
            ],
        ]);

        $role = Role::create([
            'name' => trim($validated['name']),
            'guard_name' => $guard,
        ]);

        $this->syncPermissions(
            $role,
            $validated['permissions'] ?? []
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-created')
            ->with('success', 'Role created successfully.');
    }


    /**
     * Display a role.
     *
     * Supports both admin and web roles.
     */
    public function show(Role $role): View
    {
        $this->ensureManagedRole($role);

        $role->load([
            'permissions',
            'users',
        ]);

        return view('admin.roles.show', [
            'title' => "Role: {$role->name}",
            'role' => $role,
        ]);
    }


    /**
     * Show edit role form.
     *
     * IMPORTANT:
     * Permissions are loaded according to the role guard.
     *
     * Member -> web permissions
     * Admin roles -> admin permissions
     */
    public function edit(Role $role): View
    {
        $this->ensureManagedRole($role);

        $guard = $role->guard_name;

        $permissions = $this->permissionsForGuard($guard);

        $rolePermissions = $role->permissions
            ->where('guard_name', $guard)
            ->pluck('name')
            ->values()
            ->toArray();

        return view('admin.roles.edit', [
            'title' => "Edit Role: {$role->name}",
            'role' => $role,

            // Important for the Blade
            'guard' => $guard,

            // Permissions belonging to this guard
            'permissions' => $permissions,

            // Permissions currently assigned to this role
            'rolePermissions' => $rolePermissions,
        ]);
    }


    /**
     * Update a role.
     *
     * Member @ web can update its permissions.
     *
     * Protected role names cannot be changed.
     */
    public function update(
        Request $request,
        Role $role
    ): RedirectResponse {
        $this->ensureManagedRole($role);

        /*
         * Never allow the guard to be changed from the edit page.
         *
         * Example:
         * Member @ web
         *
         * must remain:
         * Member @ web
         */
        $guard = $role->guard_name;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',

                Rule::unique('roles', 'name')
                    ->ignore($role->getKey())
                    ->where(function (Builder $query) use ($guard) {
                        $query->where('guard_name', $guard);
                    }),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'string',

                Rule::exists('permissions', 'name')
                    ->where(function (Builder $query) use ($guard) {
                        $query->where('guard_name', $guard);
                    }),
            ],
        ]);

        $newName = trim($validated['name']);

        /*
         * Protected roles:
         *
         * Super Admin @ admin
         * Admin       @ admin
         * Member      @ web
         *
         * Their names cannot change.
         *
         * Their permissions CAN change.
         */
        if ($this->isProtectedRole($role)) {
            if ($newName !== $role->name) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        "The '{$role->name}' role name cannot be changed."
                    );
            }
        }

        /*
         * Update the name only if it actually changed.
         *
         * Guard remains unchanged.
         */
        if ($newName !== $role->name) {
            $role->update([
                'name' => $newName,
            ]);
        }

        /*
         * Update permissions for the SAME guard.
         *
         * Member:
         *     web permissions only
         *
         * Admin roles:
         *     admin permissions only
         */
        $this->syncPermissions(
            $role,
            $validated['permissions'] ?? []
        );

        return redirect()
            ->route('admin.roles.show',$role)
            ->with('status', 'role-updated')
            ->with(
                'success',
                "'{$role->name}' role updated successfully."
            );
    }


    /**
     * Delete a role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $this->ensureManagedRole($role);

        /*
         * Protected roles cannot be deleted:
         *
         * Super Admin @ admin
         * Admin       @ admin
         * Member      @ web
         */
        if ($this->isProtectedRole($role)) {
            return back()
                ->with(
                    'error',
                    "The '{$role->name}' role cannot be deleted."
                );
        }

        $userCount = $role->users()->count();

        if ($userCount > 0) {
            return back()
                ->with(
                    'error',
                    "Cannot delete '{$role->name}' — it has {$userCount} user(s) assigned."
                );
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-deleted')
            ->with(
                'success',
                "'{$role->name}' role deleted successfully."
            );
    }


    /**
     * Get permissions for a specific guard.
     *
     * admin:
     *     admin.access
     *     books.view
     *     users.view
     *     ...
     *
     * web:
     *     books.view
     *     books.download
     *     borrowings.view
     *     ...
     */
    private function permissionsForGuard(string $guard)
    {
        return Permission::query()
            ->where('guard_name', $guard)
            ->orderBy('name')
            ->get()
            ->groupBy(function (Permission $permission) {
                return str_contains($permission->name, '.')
                    ? Str::before($permission->name, '.')
                    : 'General';
            });
    }


    /**
     * Sync permissions safely according to the role guard.
     *
     * This prevents:
     *
     * Member @ web
     * from accidentally receiving
     * admin permissions.
     */
    private function syncPermissions(
        Role $role,
        array $permissionNames
    ): void {
        if (empty($permissionNames)) {
            $role->syncPermissions([]);

            return;
        }

        $permissions = Permission::query()
            ->where('guard_name', $role->guard_name)
            ->whereIn('name', $permissionNames)
            ->get();

        $role->syncPermissions($permissions);
    }


    /**
     * Check whether the role is protected.
     */
    private function isProtectedRole(Role $role): bool
    {
        return in_array(
            $role->name,
            self::PROTECTED_ROLES[$role->guard_name] ?? [],
            true
        );
    }


    /**
     * Ensure the role belongs to one of the guards managed
     * by this admin role-management section.
     */
    private function ensureManagedRole(Role $role): void
    {
        abort_unless(
            in_array(
                $role->guard_name,
                [
                    self::ADMIN_GUARD,
                    self::WEB_GUARD,
                ],
                true
            ),
            404
        );
    }


    /**
     * Normalize requested guard.
     *
     * Invalid values fall back to admin.
     */
    private function normalizeGuard(string $guard): string
    {
        return in_array(
            $guard,
            [
                self::ADMIN_GUARD,
                self::WEB_GUARD,
            ],
            true
        )
            ? $guard
            : self::ADMIN_GUARD;
    }
}