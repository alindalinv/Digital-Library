<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed application users.
     *
     * Default password for all seeded users: "password"
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        $users = [
            [
                'email'      => 'superadmin@example.com',
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'name'       => 'Super Admin',
                'phone'      => '+1 555 0100',
                'role'       => 'Super Admin',
            ],
            [
                'email'      => 'admin@example.com',
                'first_name' => 'System',
                'last_name'  => 'Admin',
                'name'       => 'System Admin',
                'phone'      => '+1 555 0101',
                'role'       => 'Admin',
            ],
            [
                'email'      => 'librarian@example.com',
                'first_name' => 'Library',
                'last_name'  => 'Staff',
                'name'       => 'Library Staff',
                'phone'      => '+1 555 0102',
                'role'       => 'Librarian',
            ],
            [
                'email'      => 'editor@example.com',
                'first_name' => 'Content',
                'last_name'  => 'Editor',
                'name'       => 'Content Editor',
                'phone'      => '+1 555 0103',
                'role'       => 'Editor',
            ],
            [
                'email'      => 'member@example.com',
                'first_name' => 'Library',
                'last_name'  => 'Member',
                'name'       => 'Library Member',
                'phone'      => '+1 555 0104',
                'role'       => 'Member',
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

            $user->syncRoles([$data['role']]);
        }
    }
}