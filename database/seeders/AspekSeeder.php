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
            ['id' => 1, 'nama_aspek' => 'Nilai Agama dan Moral'],
            ['id' => 2, 'nama_aspek' => 'Fisik Motorik'], 
            ['id' => 3, 'nama_aspek' => 'Kognitif'], 
            ['id' => 4, 'nama_aspek' => 'Bahasa'], 
            ['id' => 5, 'nama_aspek' => 'Sosial Emosional'],
            ['id' => 6, 'nama_aspek' => 'Seni']]
        );
    }
}
