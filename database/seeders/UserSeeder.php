<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

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

        $faker = Faker::create();

        $names = ['AKHDAN LATIEF AZIZAN', 'BASIT ALDIANSYAH', 'FADHILAH RHOMADHONA', 'GILANG PUTRA IRAWAN', 'HARIS SAPUTRA', 'INGGIT RIYASA', 'KAMILA SYARFI SYABANI', 'M. RASYID SETIAWAN', 'MOCH YUSUF AL MALIK', 'MUHAMAD IKHSAN MAULANA', 'MUHAMAD RADITYA DENIS GUNAWAN', 'MUHAMMAD ALFATIH SOFYANA', 'MUHAMMAD HAFIZH MAHARDIKA', 'MUHAMMAD SYAHDAN AQWIA', 'NABILA SALWA AZAHRA', 'NAFISA AJWA', 'PUTRI AYU LESTARI', 'RAVI RIFAI RAUP', 'REISYA KAILA NURAJIJAH', 'SALSABILA AYU HANIFA', 'SILVA APRILIANI', 'UNZILA ILAIKA RAHIMA', 'ZAKI FAHRII HISSAM'];

        foreach ($names as $name) {
            $firstName = strtolower(strtok($name, ' ')); // ambil kata pertama dan ubah ke lowercase
            $words = explode(' ', strtolower($name));
            $email = $words[0] . '.' . ($words[1] ?? 'user') . '@gmail.com';


            $ortu = User::create([
                'name' => ucwords(strtolower($name)),
                'email' => $email,
                'phone' => $faker->phoneNumber,
                'password' => Hash::make('password'),
            ]);
            $ortu->assignRole('user');
        }

        // Assign roles to users
        $admin->assignRole('admin');
        $user->assignRole('user');
    }
}
