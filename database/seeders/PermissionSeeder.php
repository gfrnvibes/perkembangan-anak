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
        // Define permissions
        $permissions = [
            // Dashboard permissions
            'view dashboard',
            
            // User management permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Perkembangan anak permissions
            'view perkembangan',
            'create perkembangan',
            'edit perkembangan',
            'delete perkembangan',
            
            // Add more permissions as needed for your application
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
