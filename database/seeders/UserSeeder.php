<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Users
        $admin = User::create([
            'name' => 'Guru',
            'email' => 'guru@gmail.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        // Create Regular User
        $user = User::create([
            'name' => 'Husni Moh Jaelani',
            'email' => 'husni@gmail.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
        ]);

        // Assign roles to users
        $admin->assignRole('admin');
        $user->assignRole('user');
    }
}
