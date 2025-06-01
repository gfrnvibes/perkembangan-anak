<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AspekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('aspeks')->insert(
        [
            [
                'id' => 1,
                'kode_aspek' => 'NAM',
                'nama_aspek' => 'Perkembangan Nilai Agama dan Moral'
            ],
            [
                'id' => 2, 
                'kode_aspek' => 'Motorik',
                'nama_aspek' => 'Perkembangan Fisik Motorik'
            ], 
            [
                'id' => 3, 
                'kode_aspek' => 'Kog',
                'nama_aspek' => 'Perkembangan Kognitif'
            ], 
            [
                'id' => 4, 
                'kode_aspek' => 'Bahasa',
                'nama_aspek' => 'Perkembangan Bahasa'
            ], 
            [
                'id' => 5, 
                'kode_aspek' => 'SosEm',
                'nama_aspek' => 'Perkembangan Sosial Emosional'
            ],
            [
                'id' => 6,
                'kode_aspek' => 'Seni',
                'nama_aspek' => 'Perkembangan Seni'
            ]
        ]);
    }
}
