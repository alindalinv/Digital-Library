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

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $permissions = Permission::query()
            ->withCount(['roles'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.permissions.index', [
            'title'       => 'Permissions',
            'permissions' => $permissions,
            'search'      => $search,
        ]);
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create(): View
    {
        $groups = Permission::query()
            ->orderBy('name')
            ->pluck('name')
            ->map(fn ($name) => Str::before($name, '.'))
            ->unique()
            ->values();

        return view('admin.pages.permissions.create', [
            'title'  => 'Create Permission',
            'groups' => $groups,
        ]);
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:100', 'unique:permissions,name'],
            'group'  => ['nullable', 'string', 'max:50'],
        ]);

        // Build the full permission name (e.g. "users.create")
        $name = $validated['group']
            ? Str::slug($validated['group']) . '.' . Str::slug($validated['name'])
            : Str::slug($validated['name']);

        Permission::create([
            'name'       => $name,
            'guard_name' => 'web',
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'permission-created')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission): View
    {
        $permission->load('roles');

        return view('admin.pages.permissions.show', [
            'title'      => "Permission: {$permission->name}",
            'permission' => $permission,
        ]);
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): View
    {
        $groups = Permission::query()
            ->orderBy('name')
            ->pluck('name')
            ->map(fn ($name) => Str::before($name, '.'))
            ->unique()
            ->values();

        return view('admin.pages.permissions.edit', [
            'title'      => "Edit Permission: {$permission->name}",
            'permission' => $permission,
            'groups'     => $groups,
        ]);
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => [
                'required',
                'string',
                'max:100',
                Rule::unique('permissions', 'name')->ignore($permission->id),
            ],
            'group' => ['nullable', 'string', 'max:50'],
        ]);

        // Build the full permission name
        $name = $validated['group']
            ? Str::slug($validated['group']) . '.' . Str::slug($validated['name'])
            : Str::slug($validated['name']);

        $permission->update([
            'name' => $name,
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'permission-updated')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        // Prevent deleting permissions that are assigned to roles
        if ($permission->roles()->count() > 0) {
            return back()
                ->with('error', "Cannot delete '{$permission->name}' — it's assigned to {$permission->roles()->count()} role(s).");
        }

        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'permission-deleted')
            ->with('success', 'Permission deleted successfully.');
    }
}