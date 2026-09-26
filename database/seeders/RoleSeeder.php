<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * ========================================
         * FRONTEND / WEB ROLES
         * ========================================
         */
        Role::firstOrCreate([
            'name' => 'Member',
            'guard_name' => 'web',
        ]);

        /*
         * ========================================
         * ADMIN ROLES
         * ========================================
         */
        $adminRoles = [
            'Super Admin',
            'Admin',
            'Librarian',
            'Editor',
        ];

        foreach ($adminRoles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'admin',
            ]);
        }
    }
}