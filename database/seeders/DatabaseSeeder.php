<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class, // Run this first to create permissions
            RoleSeeder::class,       // Run this second to create roles and assign permissions
            UserSeeder::class,       // Run this last to create users and assign roles
        ]);
    }
}
