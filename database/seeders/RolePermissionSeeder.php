<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            // Admin access
            'admin.access',

            // Dashboard
            'dashboard.view',

            // Books
            'books.view',
            'books.create',
            'books.update',
            'books.delete',

            // Authors
            'authors.view',
            'authors.create',
            'authors.update',
            'authors.delete',

            // Categories
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            // Publishers
            'publishers.view',
            'publishers.create',
            'publishers.update',
            'publishers.delete',

            // E-book files
            'ebook-files.view',
            'ebook-files.create',
            'ebook-files.update',
            'ebook-files.delete',

            // Borrowings
            'borrowings.view',
            'borrowings.create',
            'borrowings.update',
            'borrowings.delete',
            'borrowings.approve',

            // Reviews
            'reviews.view',
            'reviews.update',
            'reviews.delete',

            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Permissions
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',

            // Reports
            'reports.view',

            // Audit logs
            'audit-logs.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $librarian = Role::firstOrCreate([
            'name' => 'Librarian',
            'guard_name' => 'web',
        ]);

        $editor = Role::firstOrCreate([
            'name' => 'Editor',
            'guard_name' => 'web',
        ]);

        $member = Role::firstOrCreate([
            'name' => 'Member',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions([
            'admin.access',
            'dashboard.view',

            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',

            'reports.view',
            'audit-logs.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Librarian
        |--------------------------------------------------------------------------
        */

        $librarian->syncPermissions([
            'admin.access',
            'dashboard.view',

            'books.view',
            'books.create',
            'books.update',
            'books.delete',

            'authors.view',
            'authors.create',
            'authors.update',
            'authors.delete',

            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            'publishers.view',
            'publishers.create',
            'publishers.update',
            'publishers.delete',

            'ebook-files.view',
            'ebook-files.create',
            'ebook-files.update',
            'ebook-files.delete',

            'borrowings.view',
            'borrowings.create',
            'borrowings.update',
            'borrowings.delete',
            'borrowings.approve',

            'reviews.view',
            'reviews.update',
            'reviews.delete',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Editor
        |--------------------------------------------------------------------------
        */

        $editor->syncPermissions([
            'admin.access',
            'dashboard.view',

            'books.view',
            'books.create',
            'books.update',

            'authors.view',
            'authors.create',
            'authors.update',

            'categories.view',
            'categories.create',
            'categories.update',

            'publishers.view',
            'publishers.create',
            'publishers.update',

            'ebook-files.view',
            'ebook-files.create',
            'ebook-files.update',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Member
        |--------------------------------------------------------------------------
        */

        $member->syncPermissions([
            'books.view',
        ]);
    }
}