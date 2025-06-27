<?php

namespace Database\Seeders;

use App\Models\Nilai;
use Illuminate\Database\Seeder;
use SebastianBergmann\Template\Template;

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
            AspekSeeder::class,
            IndikatorSeeder::class,
            TemplateCatatanSeeder::class,
            // AnakSeeder::class,
            // NilaiSeeder::class,
        ]);
    }
}
