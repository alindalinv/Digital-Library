<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * The guard used by the admin panel.
     */
    private const GUARD = 'admin';

    /**
     * Display a listing of admin roles.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $roles = Role::query()
            ->where('guard_name', self::GUARD)
            ->withCount(['users', 'permissions'])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
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
     * Show the form for creating a new admin role.
     */
    public function create(): View
    {
        $permissions = $this->adminPermissions();

        return view('admin.roles.create', [
            'title' => 'Create Role',
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created admin role.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')
                    ->where(fn (Builder $query) =>
                        $query->where('guard_name', self::GUARD)
                    ),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')
                    ->where(fn (Builder $query) =>
                        $query->where('guard_name', self::GUARD)
                    ),
            ],
        ]);

        $roleName = Str::slug($validated['name']);

        $role = Role::create([
            'name' => $roleName,
            'guard_name' => self::GUARD,
        ]);

        $this->syncAdminPermissions(
            $role,
            $validated['permissions'] ?? []
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-created')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified admin role.
     */
    public function show(Role $role): View
    {
        $this->ensureAdminRole($role);

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
     * Show the form for editing the specified admin role.
     */
    public function edit(Role $role): View
    {
        $this->ensureAdminRole($role);

        $permissions = $this->adminPermissions();

        $rolePermissions = $role->permissions
            ->where('guard_name', self::GUARD)
            ->pluck('name')
            ->values()
            ->toArray();

        return view('admin.roles.edit', [
            'title' => "Edit Role: {$role->name}",
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Update the specified admin role.
     */
    public function update(
        Request $request,
        Role $role
    ): RedirectResponse {
        $this->ensureAdminRole($role);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')
                    ->ignore($role->id)
                    ->where(fn (Builder $query) =>
                        $query->where('guard_name', self::GUARD)
                    ),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')
                    ->where(fn (Builder $query) =>
                        $query->where('guard_name', self::GUARD)
                    ),
            ],
        ]);

        $newName = Str::slug($validated['name']);

        // Protected admin roles cannot be renamed.
        $protectedRoles = [
            'admin',
            'super-admin',
        ];

        if (
            in_array($role->name, $protectedRoles, true)
            && $newName !== $role->name
        ) {
            return back()
                ->withInput()
                ->with('error', "The '{$role->name}' role name cannot be changed.");
        }

        $role->update([
            'name' => $newName,
        ]);

        $this->syncAdminPermissions(
            $role,
            $validated['permissions'] ?? []
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-updated')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified admin role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $this->ensureAdminRole($role);

        // Protected admin roles cannot be deleted.
        $protectedRoles = [
            'admin',
            'super-admin',
        ];

        if (in_array($role->name, $protectedRoles, true)) {
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
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Get permissions belonging to the admin guard.
     */
    private function adminPermissions()
    {
        return Permission::query()
            ->where('guard_name', self::GUARD)
            ->orderBy('name')
            ->get()
            ->groupBy(
                fn (Permission $permission) =>
                    Str::before($permission->name, '.')
            );
    }

    /**
     * Sync only admin-guard permissions to the role.
     */
    private function syncAdminPermissions(
        Role $role,
        array $permissionNames
    ): void {
        if (empty($permissionNames)) {
            $role->syncPermissions([]);

            return;
        }

        $permissions = Permission::query()
            ->where('guard_name', self::GUARD)
            ->whereIn('name', $permissionNames)
            ->get();

        $role->syncPermissions($permissions);
    }

    /**
     * Ensure the route-bound role belongs to the admin guard.
     */
    private function ensureAdminRole(Role $role): void
    {
        abort_unless(
            $role->guard_name === self::GUARD,
            404
        );
    }
}