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
        | ADMIN PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $adminPermissions = [
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
            'ebook-files.download',

            'borrowings.view',
            'borrowings.create',
            'borrowings.update',
            'borrowings.delete',
            'borrowings.approve',

            'reviews.view',
            'reviews.update',
            'reviews.delete',

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
        ];

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | WEB / FRONTEND PERMISSIONS
        |--------------------------------------------------------------------------
        |
        | These are separate from admin permissions.
        |
        */

        $webPermissions = [
            'books.view',

            'authors.view',

            'categories.view',

            'publishers.view',

            'ebook-files.view',
            'ebook-files.download',

            'borrowings.view',
            'borrowings.create',

            'reviews.view',
            'reviews.create',
        ];

        foreach ($webPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN ROLES
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'admin',
        ]);

        $librarian = Role::firstOrCreate([
            'name' => 'Librarian',
            'guard_name' => 'admin',
        ]);

        $editor = Role::firstOrCreate([
            'name' => 'Editor',
            'guard_name' => 'admin',
        ]);


        /*
        |--------------------------------------------------------------------------
        | FRONTEND ROLE
        |--------------------------------------------------------------------------
        */

        $member = Role::firstOrCreate([
            'name' => 'Member',
            'guard_name' => 'web',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(
            Permission::query()
                ->where('guard_name', 'admin')
                ->get()
        );


        /*
        |--------------------------------------------------------------------------
        | ADMIN
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
        | LIBRARIAN
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
            'ebook-files.download',

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
        | EDITOR
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
        | MEMBER
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | These permissions are WEB permissions.
        |
        */

        $member->syncPermissions(
            Permission::query()
                ->where('guard_name', 'web')
                ->whereIn('name', $webPermissions)
                ->get()
        );
    }
}