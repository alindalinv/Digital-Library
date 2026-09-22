<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.update')->only(['edit', 'update', 'editRoles', 'updateRoles']);
        $this->middleware('permission:users.delete')->only(['destroy']);
    }

    /* ------------------------------------------------------------------ */
    /* CRUD                                                                */
    /* ------------------------------------------------------------------ */

    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status', 'all')->toString();

        if (! in_array($status, ['all', 'active', 'inactive'], true)) {
            $status = 'all';
        }

        $users = User::with('roles')
            ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            }))
            ->when($status !== 'all', fn ($query) => $query->where('status', $status === 'active'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.users._results', compact('users'))->render(),
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'next_page_url' => $users->nextPageUrl(),
                'prev_page_url' => $users->previousPageUrl(),
            ]);
        }

        return view('admin.users.index', compact('users', 'search', 'status') + ['title' => 'Users']);
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status'   => 'boolean',
            'roles'    => 'array',
            'roles.*'  => 'exists:roles,name',
        ]);

        $status = $request->boolean('status') ? 1 : 0;
        $data['password'] = Hash::make($data['password']);

        $user = User::create(collect($data)->except('status')->all());
        $user->forceFill(['status' => $status])->save();

        if (! empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->getKey(),
            'password' => 'nullable|string|min:8|confirmed',
            'status'   => 'boolean',
            'roles'    => 'array',
            'roles.*'  => 'exists:roles,name',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $user->forceFill([
            ...collect($data)->except('status')->all(),
            'status' => $request->boolean('status') ? 1 : 0,
        ])->save();

        if ($request->has('roles')) {
            $user->syncRoles($roles);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->getKey() === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /* ------------------------------------------------------------------ */
    /* Role assignment                                                     */
    /* ------------------------------------------------------------------ */

    public function editRoles(User $user)
    {
        $roles = Role::with('permissions')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.roles', compact('user', 'roles', 'userRoles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles'   => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'Roles updated for ' . $user->name);
    }
}