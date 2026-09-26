<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    private const ADMIN_GUARD = 'admin';
    private const WEB_GUARD = 'web';

    private const MEMBER_ROLE = 'Member';

    public function __construct()
    {
        $this->middleware('permission:users.view,admin')
            ->only(['index', 'show']);

        $this->middleware('permission:users.create,admin')
            ->only(['create', 'store']);

        $this->middleware('permission:users.update,admin')
            ->only([
                'edit',
                'update',
                'editRoles',
                'updateRoles',
            ]);

        $this->middleware('permission:users.delete,admin')
            ->only(['destroy']);
    }

    /**
     * Display users.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = trim((string) $request->input('search', ''));

        $status = $request->input('status', 'all');

        $query = User::query()
            ->with([
                'roles:id,name,guard_name',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array($status, ['active', 'inactive'], true),
                function ($query) use ($status) {
                    $query->where(
                        'status',
                        $status === 'active'
                    );
                }
            )
            ->latest();

        $users = $query
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'admin.users._results',
                    compact('users')
                )->render(),

                'pagination' => $users->links()->render(),
            ]);
        }

        return view('admin.users.index', compact(
            'users',
            'search',
            'status'
        ));
    }

    /**
     * Show create user form.
     */
    public function create(): View
    {
        /*
         * Member is the default frontend role.
         *
         * IMPORTANT:
         * Member belongs to guard_name = web.
         * It is NOT an admin role.
         */
        $memberRoleIds = $this->memberRoleIds();

        return view('admin.users.create', [
            'adminRoles' => $this->adminRoles(),

            'frontendRoles' => $this->frontendRoles(),

            'adminRoleIds' => [],

            'frontendRoleIds' => $memberRoleIds,

            'title' => 'Create User',
        ]);
    }

    /**
     * Store a new user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => trim(
                trim((string) $request->input('first_name')) .
                ' ' .
                trim((string) $request->input('last_name'))
            ),
        ]);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            /*
             * ADMIN ROLES
             */
            'admin_roles' => [
                'nullable',
                'array',
            ],

            'admin_roles.*' => [
                'integer',
                Rule::exists('roles', 'id')
                    ->where(
                        fn ($query) => $query->where(
                            'guard_name',
                            self::ADMIN_GUARD
                        )
                    ),
            ],

            /*
             * FRONTEND ROLES
             *
             * Currently only Member is allowed.
             */
            'frontend_roles' => [
                'nullable',
                'array',
            ],

            'frontend_roles.*' => [
                'integer',
                Rule::exists('roles', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where(
                                'guard_name',
                                self::WEB_GUARD
                            )
                            ->where(
                                'name',
                                self::MEMBER_ROLE
                            )
                    ),
            ],
        ]);

        $adminRoleIds = $this->normalizeAdminRoleIds(
            $data['admin_roles'] ?? []
        );

        $frontendRoleIds = $this->normalizeFrontendRoleIds(
            $data['frontend_roles'] ?? []
        );

        /*
         * DEFAULT ROLE LOGIC
         *
         * If no admin role is selected,
         * automatically use Member under the WEB guard.
         *
         * This does NOT create Member under admin.
         */
        if (empty($adminRoleIds)) {
            $frontendRoleIds = $this->memberRoleIds();
        }

        $user = DB::transaction(function () use (
            $data,
            $adminRoleIds,
            $frontendRoleIds
        ) {
            $user = User::create([
                'name' => $data['name'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => (bool) $data['status'],
            ]);

            /*
             * Sync admin roles separately.
             */
            $this->syncAdminRoles(
                $user,
                $adminRoleIds
            );

            /*
             * Sync frontend roles separately.
             */
            $this->syncFrontendRoles(
                $user,
                $frontendRoleIds
            );

            return $user;
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "User {$user->name} created successfully."
            );
    }

    /**
     * Display user.
     */
    public function show(User $user): View
    {
        $user->load([
            'roles:id,name,guard_name',
        ]);

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user): View
    {
        $user->load([
            'roles:id,name,guard_name',
        ]);

        /*
         * Only roles belonging to the ADMIN guard.
         */
        $adminRoleIds = $user->roles
            ->where('guard_name', self::ADMIN_GUARD)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        /*
         * Only Member belonging to WEB guard.
         */
        $frontendRoleIds = $user->roles
            ->where('guard_name', self::WEB_GUARD)
            ->where('name', self::MEMBER_ROLE)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        /*
         * If the user has no admin role,
         * Member/web is the default.
         */
        if (empty($adminRoleIds)) {
            $frontendRoleIds = $this->memberRoleIds();
        }

        return view('admin.users.edit', [
            'user' => $user,

            'adminRoles' => $this->adminRoles(),

            'frontendRoles' => $this->frontendRoles(),

            'adminRoleIds' => $adminRoleIds,

            'frontendRoleIds' => $frontendRoleIds,

            'title' => 'Edit User',
        ]);
    }

    /**
     * Update user.
     */
    public function update(
        Request $request,
        User $user
    ): RedirectResponse {
        $request->merge([
            'name' => trim(
                trim((string) $request->input('first_name')) .
                ' ' .
                trim((string) $request->input('last_name'))
            ),
        ]);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            /*
             * ADMIN ROLES
             */
            'admin_roles' => [
                'nullable',
                'array',
            ],

            'admin_roles.*' => [
                'integer',
                Rule::exists('roles', 'id')
                    ->where(
                        fn ($query) => $query->where(
                            'guard_name',
                            self::ADMIN_GUARD
                        )
                    ),
            ],

            /*
             * FRONTEND ROLES
             */
            'frontend_roles' => [
                'nullable',
                'array',
            ],

            'frontend_roles.*' => [
                'integer',
                Rule::exists('roles', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where(
                                'guard_name',
                                self::WEB_GUARD
                            )
                            ->where(
                                'name',
                                self::MEMBER_ROLE
                            )
                    ),
            ],
        ]);

        $adminRoleIds = $this->normalizeAdminRoleIds(
            $data['admin_roles'] ?? []
        );

        $frontendRoleIds = $this->normalizeFrontendRoleIds(
            $data['frontend_roles'] ?? []
        );

        /*
         * If no ADMIN role is selected,
         * the user becomes a normal frontend Member.
         */
        if (empty($adminRoleIds)) {
            $frontendRoleIds = $this->memberRoleIds();
        }

        DB::transaction(function () use (
            $user,
            $data,
            $adminRoleIds,
            $frontendRoleIds
        ) {
            $user->name = $data['name'];
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'] ?? null;
            $user->email = $data['email'];
            $user->status = (bool) $data['status'];

            if (
                isset($data['password']) &&
                $data['password'] !== ''
            ) {
                $user->password = Hash::make(
                    $data['password']
                );
            }

            $user->save();

            /*
             * Admin roles only.
             */
            $this->syncAdminRoles(
                $user,
                $adminRoleIds
            );

            /*
             * Frontend Member only.
             */
            $this->syncFrontendRoles(
                $user,
                $frontendRoleIds
            );
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "User {$user->name} updated successfully."
            );
    }

    /**
     * Delete user.
     */
    public function destroy(User $user): RedirectResponse
    {
        /*
         * Do not allow the currently logged-in admin
         * to delete their own account.
         */
        if (
            $user->getKey() ===
            Auth::guard(self::ADMIN_GUARD)->id()
        ) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        DB::transaction(function () use ($user) {
            $user->delete();
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with(
            'success',
            'User deleted successfully.'
        );
    }

    /**
     * Show role management page.
     */
    public function editRoles(User $user): View
    {
        $user->load([
            'roles:id,name,guard_name',
        ]);

        $adminRoleIds = $user->roles
            ->where('guard_name', self::ADMIN_GUARD)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $frontendRoleIds = $user->roles
            ->where('guard_name', self::WEB_GUARD)
            ->where('name', self::MEMBER_ROLE)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        if (empty($adminRoleIds)) {
            $frontendRoleIds = $this->memberRoleIds();
        }

        return view('admin.users.roles', [
            'user' => $user,

            'adminRoles' => $this->adminRoles(),

            'frontendRoles' => $this->frontendRoles(),

            'adminRoleIds' => $adminRoleIds,

            'frontendRoleIds' => $frontendRoleIds,
        ]);
    }

    /**
     * Update user roles.
     */
    public function updateRoles(
        Request $request,
        User $user
    ): RedirectResponse {
        $data = $request->validate([
            /*
             * ADMIN ROLES
             */
            'admin_roles' => [
                'nullable',
                'array',
            ],

            'admin_roles.*' => [
                'integer',
                Rule::exists('roles', 'id')
                    ->where(
                        fn ($query) => $query->where(
                            'guard_name',
                            self::ADMIN_GUARD
                        )
                    ),
            ],

            /*
             * FRONTEND ROLES
             */
            'frontend_roles' => [
                'nullable',
                'array',
            ],

            'frontend_roles.*' => [
                'integer',
                Rule::exists('roles', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where(
                                'guard_name',
                                self::WEB_GUARD
                            )
                            ->where(
                                'name',
                                self::MEMBER_ROLE
                            )
                    ),
            ],
        ]);

        $adminRoleIds = $this->normalizeAdminRoleIds(
            $data['admin_roles'] ?? []
        );

        $frontendRoleIds = $this->normalizeFrontendRoleIds(
            $data['frontend_roles'] ?? []
        );

        /*
         * No admin role = Member/web.
         */
        if (empty($adminRoleIds)) {
            $frontendRoleIds = $this->memberRoleIds();
        }

        DB::transaction(function () use (
            $user,
            $adminRoleIds,
            $frontendRoleIds
        ) {
            $this->syncAdminRoles(
                $user,
                $adminRoleIds
            );

            $this->syncFrontendRoles(
                $user,
                $frontendRoleIds
            );
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with(
            'success',
            'User roles updated successfully.'
        );
    }

    /**
     * Get available admin roles.
     *
     * IMPORTANT:
     * These roles MUST use guard_name = admin.
     */
    private function adminRoles()
    {
        return Role::query()
            ->where(
                'guard_name',
                self::ADMIN_GUARD
            )
            ->with('permissions')
            ->orderByRaw(
                "CASE
                    WHEN name = 'Super Admin' THEN 1
                    WHEN name = 'Admin' THEN 2
                    WHEN name = 'Librarian' THEN 3
                    WHEN name = 'Editor' THEN 4
                    ELSE 5
                END"
            )
            ->orderBy('name')
            ->get();
    }

    /**
     * Get frontend roles.
     *
     * At the moment Member is the only frontend role
     * exposed in admin user management.
     *
     * IMPORTANT:
     * Member MUST use guard_name = web.
     */
    private function frontendRoles()
    {
        return Role::query()
            ->where(
                'guard_name',
                self::WEB_GUARD
            )
            ->where(
                'name',
                self::MEMBER_ROLE
            )
            ->orderBy('name')
            ->get();
    }

    /**
     * Get the Member role ID from the WEB guard.
     */
    private function memberRoleIds(): array
    {
        $memberRole = Role::query()
            ->where(
                'name',
                self::MEMBER_ROLE
            )
            ->where(
                'guard_name',
                self::WEB_GUARD
            )
            ->first();

        if (! $memberRole) {
            throw new \RuntimeException(
                'Member role with guard_name=web was not found.'
            );
        }

        return [
            (int) $memberRole->id,
        ];
    }

    /**
     * Normalize admin role IDs.
     *
     * IMPORTANT:
     * No Admin fallback here.
     *
     * An empty array means:
     * "this user has no admin role".
     */
    private function normalizeAdminRoleIds(
        array $selectedRoleIds
    ): array {
        $selectedRoleIds = array_values(
            array_unique(
                array_filter(
                    $selectedRoleIds,
                    fn ($roleId) =>
                        is_numeric($roleId) &&
                        (int) $roleId > 0
                )
            )
        );

        return array_map(
            fn ($roleId) => (int) $roleId,
            $selectedRoleIds
        );
    }

    /**
     * Normalize frontend role IDs.
     */
    private function normalizeFrontendRoleIds(
        array $selectedRoleIds
    ): array {
        $selectedRoleIds = array_values(
            array_unique(
                array_filter(
                    $selectedRoleIds,
                    fn ($roleId) =>
                        is_numeric($roleId) &&
                        (int) $roleId > 0
                )
            )
        );

        return array_map(
            fn ($roleId) => (int) $roleId,
            $selectedRoleIds
        );
    }

    /**
     * Sync ONLY admin roles.
     *
     * This intentionally does not touch web roles.
     */
    private function syncAdminRoles(
        User $user,
        array $selectedRoleIds
    ): void {
        $selectedRoleIds = array_values(
            array_unique(
                array_map(
                    'intval',
                    $selectedRoleIds
                )
            )
        );

        /*
         * Make absolutely sure every selected role
         * belongs to the admin guard.
         */
        if (! empty($selectedRoleIds)) {
            $validRoleIds = Role::query()
                ->whereIn('id', $selectedRoleIds)
                ->where(
                    'guard_name',
                    self::ADMIN_GUARD
                )
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            if (
                count($validRoleIds) !==
                count($selectedRoleIds)
            ) {
                throw new \RuntimeException(
                    'One or more selected admin roles are invalid.'
                );
            }
        }

        /*
         * Get only the current ADMIN role assignments.
         *
         * Web/Member assignments are intentionally excluded.
         */
        $currentAdminRoleIds = DB::table('model_has_roles')
            ->join(
                'roles',
                'roles.id',
                '=',
                'model_has_roles.role_id'
            )
            ->where(
                'model_has_roles.model_type',
                $user->getMorphClass()
            )
            ->where(
                'model_has_roles.model_id',
                $user->getKey()
            )
            ->where(
                'roles.guard_name',
                self::ADMIN_GUARD
            )
            ->pluck('model_has_roles.role_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        /*
         * Remove existing admin roles only.
         */
        if (! empty($currentAdminRoleIds)) {
            DB::table('model_has_roles')
                ->where(
                    'model_type',
                    $user->getMorphClass()
                )
                ->where(
                    'model_id',
                    $user->getKey()
                )
                ->whereIn(
                    'role_id',
                    $currentAdminRoleIds
                )
                ->delete();
        }

        /*
         * Add selected admin roles.
         */
        if (! empty($selectedRoleIds)) {
            $now = now();

            $rows = array_map(
                fn ($roleId) => [
                    'role_id' => $roleId,
                    'model_type' => $user->getMorphClass(),
                    'model_id' => $user->getKey(),
                ],
                $selectedRoleIds
            );

            DB::table('model_has_roles')
                ->insertOrIgnore($rows);
        }

        $user->unsetRelation('roles');
    }

    /**
     * Sync ONLY frontend/web roles.
     *
     * This intentionally does not touch admin roles.
     */
    private function syncFrontendRoles(
        User $user,
        array $selectedRoleIds
    ): void {
        $selectedRoleIds = array_values(
            array_unique(
                array_map(
                    'intval',
                    $selectedRoleIds
                )
            )
        );

        /*
         * Validate frontend roles.
         */
        if (! empty($selectedRoleIds)) {
            $validRoleIds = Role::query()
                ->whereIn('id', $selectedRoleIds)
                ->where(
                    'guard_name',
                    self::WEB_GUARD
                )
                ->where(
                    'name',
                    self::MEMBER_ROLE
                )
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            if (
                count($validRoleIds) !==
                count($selectedRoleIds)
            ) {
                throw new \RuntimeException(
                    'One or more selected frontend roles are invalid.'
                );
            }
        }

        /*
         * Get ONLY web roles currently assigned.
         */
        $currentFrontendRoleIds = DB::table('model_has_roles')
            ->join(
                'roles',
                'roles.id',
                '=',
                'model_has_roles.role_id'
            )
            ->where(
                'model_has_roles.model_type',
                $user->getMorphClass()
            )
            ->where(
                'model_has_roles.model_id',
                $user->getKey()
            )
            ->where(
                'roles.guard_name',
                self::WEB_GUARD
            )
            ->pluck('model_has_roles.role_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        /*
         * Remove current web roles only.
         */
        if (! empty($currentFrontendRoleIds)) {
            DB::table('model_has_roles')
                ->where(
                    'model_type',
                    $user->getMorphClass()
                )
                ->where(
                    'model_id',
                    $user->getKey()
                )
                ->whereIn(
                    'role_id',
                    $currentFrontendRoleIds
                )
                ->delete();
        }

        /*
         * Add selected web roles.
         */
        if (! empty($selectedRoleIds)) {
            $rows = array_map(
                fn ($roleId) => [
                    'role_id' => $roleId,
                    'model_type' => $user->getMorphClass(),
                    'model_id' => $user->getKey(),
                ],
                $selectedRoleIds
            );

            DB::table('model_has_roles')
                ->insertOrIgnore($rows);
        }

        $user->unsetRelation('roles');
    }
}