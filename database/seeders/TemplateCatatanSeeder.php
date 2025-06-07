<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplateCatatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nilaiOps = [
            'BB' => 'belum menunjukkan kemampuan dalam',
            'MB' => 'masih membutuhkan bimbingan dalam',
            'BSH' => 'mulai berkembang dalam',
            'BSB' => 'sudah sangat baik dalam',
        ];

        $indikators = DB::table('indikators')->get();

        foreach ($indikators as $indikator) {
            foreach ($nilaiOps as $kode => $teks) {
                DB::table('template_catatans')->insert([
                    'indikator_id' => $indikator->id,
                    'nilai' => $kode,
                    'isi_template' => 'Ananda ' . $teks . ' ' . strtolower($indikator->nama_indikator) . '.',
                ]);
            }
        }
    }
}
