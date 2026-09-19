<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed application users.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $superAdmin->syncRoles(['Super Admin']);

        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
            ]
        );

        $admin->syncRoles(['Admin']);

        // Librarian
        $librarian = User::updateOrCreate(
            ['email' => 'librarian@example.com'],
            [
                'name' => 'Library Staff',
                'password' => Hash::make('password'),
            ]
        );

        $librarian->syncRoles(['Librarian']);

        // Editor
        $editor = User::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('password'),
            ]
        );

        $editor->syncRoles(['Editor']);

        // Member
        $member = User::updateOrCreate(
            ['email' => 'member@example.com'],
            [
                'name' => 'Library Member',
                'password' => Hash::make('password'),
            ]
        );

        $member->syncRoles(['Member']);
    }
}