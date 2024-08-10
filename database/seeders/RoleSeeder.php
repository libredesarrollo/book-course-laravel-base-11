<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Test 1']);
        Role::create(['name' => 'Test 2']);
        Role::create(['name' => 'Test 3']);
        Role::create(['name' => 'Test 4']);
        Role::create(['name' => 'Test 5']);
    }
}
