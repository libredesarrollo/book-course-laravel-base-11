<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'Test 1']);
        Permission::create(['name' => 'Test 2']);
        Permission::create(['name' => 'Test 3']);
        Permission::create(['name' => 'Test 4']);
        Permission::create(['name' => 'Test 5']);
    }
}
