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
        // Template catatan yang lebih detail dan bervariasi
        $templateCatatan = [
            'BB' => [
                'prefix' => 'Ananda',
                'templates' => [
                    'belum menunjukkan kemampuan dalam {indikator} dan masih memerlukan bimbingan intensif.',
                    'masih dalam tahap pengenalan {indikator} dan perlu dukungan lebih.',
                    'belum mampu {indikator} secara mandiri dan membutuhkan bantuan penuh.',
                ]
            ],
            'MB' => [
                'prefix' => 'Ananda',
                'templates' => [
                    'masih membutuhkan bimbingan dalam {indikator} namun menunjukkan minat untuk belajar.',
                    'sudah mulai menunjukkan kemampuan {indikator} dengan bantuan guru.',
                    'dalam proses mengembangkan kemampuan {indikator} dan perlu pendampingan.',
                ]
            ],
            'BSH' => [
                'prefix' => 'Ananda',
                'templates' => [
                    'mulai berkembang dalam {indikator} dan dapat melakukan dengan sedikit bantuan.',
                    'menunjukkan perkembangan yang baik dalam {indikator}.',
                    'sudah mampu {indikator} dengan bimbingan minimal.',
                ]
            ],
            'BSB' => [
                'prefix' => 'Ananda',
                'templates' => [
                    'sudah sangat baik dalam {indikator} dan dapat melakukan secara mandiri.',
                    'menguasai {indikator} dengan sangat baik dan dapat membantu teman.',
                    'berkembang sangat baik dalam {indikator} dan menjadi contoh bagi teman-teman.',
                ]
            ],
        ];

        $indikators = DB::table('indikators')->get();

        foreach ($indikators as $indikator) {
            foreach ($templateCatatan as $kode => $data) {
                // Pilih template secara random atau gunakan yang pertama
                $template = $data['templates'][0]; // atau array_rand untuk random
                
                $isiTemplate = $data['prefix'] . ' ' . str_replace(
                    '{indikator}', 
                    strtolower($indikator->nama_indikator), 
                    $template
                );

                DB::table('template_catatans')->insert([
                    'indikator_id' => $indikator->id,
                    'nilai' => $kode,
                    'isi_template' => $isiTemplate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
