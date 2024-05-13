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
        // $role1 = Role::create(['name' => 'Admin']);
        // $role2 = Role::create(['name' => 'Editor']);

        // Permission::create(['name' => 'editor.post.index']);
        // Permission::create(['name' => 'editor.post.create']);
        // Permission::create(['name' => 'editor.post.update']);
        // Permission::create(['name' => 'editor.post.destroy']);

        // Permission::create(['name' => 'editor.category.index']);
        // Permission::create(['name' => 'editor.category.create']);
        // Permission::create(['name' => 'editor.category.update']);
        // Permission::create(['name' => 'editor.category.destroy']);

        // Permission::find(1)->assignRole(Role::find(1));
        // Permission::find(1)->assignRole(Role::find(2));

        $role2 = Role::find(2);

        // Permission::find(1)->assignRole($role2);
        // Permission::find(2)->assignRole($role2);
        // Permission::find(3)->assignRole($role2);
        // Permission::find(4)->assignRole($role2);
        // Permission::find(5)->assignRole($role2);
        // Permission::find(6)->assignRole($role2);
        // Permission::find(7)->assignRole($role2);
        // Permission::find(8)->assignRole($role2);

        $permission1 = Permission::find(1);
        $permission2 = Permission::find(2);
        $permission3 = Permission::find(3);
        $permission4 = Permission::find(4);
        $permission5 = Permission::find(5);
        $permission6 = Permission::find(6);
        $permission7 = Permission::find(7);
        $permission8 = Permission::find(8);

        $role2->givePermissionTo($permission1, $permission2, $permission3, $permission4, $permission5, $permission5, $permission6, $permission7, $permission8);

    }
}
