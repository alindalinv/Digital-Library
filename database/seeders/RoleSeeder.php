<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'member']);
        Role::firstOrCreate(['name' => 'librarian']);
        Role::firstOrCreate(['name' => 'admin']);
    }
}