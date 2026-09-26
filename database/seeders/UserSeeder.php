<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Seed application users.
     *
     * Default password for all seeded users: "password"
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $defaultPassword = Hash::make('password');

        $users = [
            [
                'email'      => 'superadmin@example.com',
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'name'       => 'Super Admin',
                'phone'      => '+1 555 0100',
                'role'       => 'Super Admin',
                'guard'      => 'admin',
            ],
            [
                'email'      => 'admin@example.com',
                'first_name' => 'System',
                'last_name'  => 'Admin',
                'name'       => 'System Admin',
                'phone'      => '+1 555 0101',
                'role'       => 'Admin',
                'guard'      => 'admin',
            ],
            [
                'email'      => 'librarian@example.com',
                'first_name' => 'Library',
                'last_name'  => 'Staff',
                'name'       => 'Library Staff',
                'phone'      => '+1 555 0102',
                'role'       => 'Librarian',
                'guard'      => 'admin',
            ],
            [
                'email'      => 'editor@example.com',
                'first_name' => 'Content',
                'last_name'  => 'Editor',
                'name'       => 'Content Editor',
                'phone'      => '+1 555 0103',
                'role'       => 'Editor',
                'guard'      => 'admin',
            ],
            [
                'email'      => 'member@example.com',
                'first_name' => 'Library',
                'last_name'  => 'Member',
                'name'       => 'Library Member',
                'phone'      => '+1 555 0104',
                'role'       => 'Member',
                'guard'      => 'web',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'first_name'        => $data['first_name'],
                    'last_name'         => $data['last_name'],
                    'name'              => $data['name'],
                    'phone'             => $data['phone'],
                    'password'          => $defaultPassword,
                    'status'            => 1,
                    'email_verified_at' => now(),
                ]
            );

            $role = Role::query()
                ->where('name', $data['role'])
                ->where('guard_name', $data['guard'])
                ->firstOrFail();

            /*
             * Only synchronize the role for the correct guard.
             *
             * The User model is shared by both web and admin guards,
             * so Spatie's syncRoles() is intentionally avoided here.
             */
            $roleExists = \DB::table('model_has_roles')
                ->where('role_id', $role->id)
                ->where('model_type', $user->getMorphClass())
                ->where('model_id', $user->getKey())
                ->exists();

            if (! $roleExists) {
                \DB::table('model_has_roles')->insert([
                    'role_id'    => $role->id,
                    'model_type' => $user->getMorphClass(),
                    'model_id'   => $user->getKey(),
                ]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}