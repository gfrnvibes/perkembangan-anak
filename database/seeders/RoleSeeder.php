<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Get all permissions
        $permissions = Permission::all();

        // Assign all permissions to admin role
        $adminRole->syncPermissions($permissions);

        // Assign limited permissions to user role
        $userRole->syncPermissions(
            Permission::where('name', 'like', 'view%')->get()
        );
    }
}
