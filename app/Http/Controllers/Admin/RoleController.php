<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
     * Display a listing of roles.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pages.roles.index', [
            'title'   => 'Roles',
            'roles'   => $roles,
            'search'  => $search,
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        $permissions = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(function ($permission) {
                // Group by prefix (e.g. "users.create" → "users")
                return Str::before($permission->name, '.');
            });

        return view('admin.pages.roles.create', [
            'title'       => 'Create Role',
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create([
            'name'       => Str::slug($validated['name']),
            'guard_name' => 'web',
        ]);

        if (! empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-created')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role): View
    {
        $role->load(['permissions', 'users']);

        return view('admin.pages.roles.show', [
            'title' => "Role: {$role->name}",
            'role'  => $role,
        ]);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        $permissions = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(function ($permission) {
                return Str::before($permission->name, '.');
            });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.pages.roles.edit', [
            'title'           => "Edit Role: {$role->name}",
            'role'            => $role,
            'permissions'     => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        // Prevent renaming core roles
        $protectedRoles = ['admin', 'super-admin'];

        if (in_array($role->name, $protectedRoles) && $validated['name'] !== $role->name) {
            return back()
                ->with('error', "The '{$role->name}' role name cannot be changed.");
        }

        $role->update([
            'name' => Str::slug($validated['name']),
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-updated')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        // Prevent deleting protected roles
        $protectedRoles = ['admin', 'super-admin', 'member'];

        if (in_array($role->name, $protectedRoles)) {
            return back()
                ->with('error', "The '{$role->name}' role cannot be deleted.");
        }

        // Prevent deleting roles with assigned users
        if ($role->users()->count() > 0) {
            return back()
                ->with('error', "Cannot delete '{$role->name}' — it has {$role->users()->count()} user(s) assigned.");
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-deleted')
            ->with('success', 'Role deleted successfully.');
    }
}