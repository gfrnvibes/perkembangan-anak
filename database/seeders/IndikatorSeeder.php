<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IndikatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('indikators')->insert([
            ['id' => 1,  
            // 'kode_indikator' => '1.1',
            'aspek_id' => 1, 'nama_indikator' => 'Mengenal Tuhan melalui ciptaan-Nya'],
            ['id' => 2,  
            // 'kode_indikator' => '1.2',
            'aspek_id' => 1, 'nama_indikator' => 'Berdoa sebelum dan sesudah melakukan kegiatan'],
            ['id' => 3,  
            // 'kode_indikator' => '2.1',
            'aspek_id' => 2, 'nama_indikator' => 'Melakukan gerakan motorik kasar'],
            ['id' => 4,  
            // 'kode_indikator' => '2.2',
            'aspek_id' => 2, 'nama_indikator' => 'Melakukan gerakan motorik halus'],
            ['id' => 5,  
            // 'kode_indikator' => '3.1',
            'aspek_id' => 3, 'nama_indikator' => 'Mengenal konsep angka'],
            ['id' => 6,  
            // 'kode_indikator' => '3.2',
            'aspek_id' => 3, 'nama_indikator' => 'Memecahkan masalah sederhana'],
            ['id' => 7,  
            // 'kode_indikator' => '4.1',
            'aspek_id' => 4, 'nama_indikator' => 'Mengungkapkan pikiran secara lisan'],
            ['id' => 8,  
            // 'kode_indikator' => '4.2',
            'aspek_id' => 4, 'nama_indikator' => 'Mendengarkan cerita'],
            ['id' => 9,  
            // 'kode_indikator' => '5.1',
            'aspek_id' => 5, 'nama_indikator' => 'Berinteraksi dengan teman'],
            ['id' => 10, 
            // 'kode_indikator' => '5.2',
             'aspek_id' => 5, 'nama_indikator' => 'Mengendalikan emosi'],
            ['id' => 11, 
            // 'kode_indikator' => '6.1',
             'aspek_id' => 6, 'nama_indikator' => 'Mengekspresikan diri melalui gambar'],
            ['id' => 12, 
            // 'kode_indikator' => '6.2',
             'aspek_id' => 6, 'nama_indikator' => 'Mengekspresikan diri melalui gerak dan lagu'],
        ]);
    }
}
