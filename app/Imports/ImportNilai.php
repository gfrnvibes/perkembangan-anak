<?php

namespace App\Imports;

use App\Models\Anak;
use App\Models\Indikator;
use App\Models\Nilai;
use App\Models\TemplateCatatan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportNilai implements ToCollection, WithHeadingRow
{
    protected $minggu;
    protected $bulan;
    protected $tahun;
    protected $semester;

    public function __construct($minggu, $bulan, $tahun, $semester)
    {
        $this->minggu = $minggu;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->semester = $semester;
    }

    public function collection(Collection $collection)
    {
        $indikators = Indikator::with('aspek')->get();

        foreach ($collection as $row) {
            // Cari anak berdasarkan nomor induk atau nama
            $anak = Anak::where
            // ('nomor_induk', $row['nomor_induk'])->orWhere
            ('nama_lengkap', $row['nama_anak'])->first();

            if (!$anak) {
                continue; // Skip jika anak tidak ditemukan
            }

            // Ambil atau buat record nilai
            $nilaiRecord = Nilai::firstOrCreate(
                [
                    'anak_id' => $anak->id,
                    'tahun' => $this->tahun,
                ],
                [
                    'nilai_data' => [],
                    'catatan_data' => [],
                ],
            );

            $nilaiData = $nilaiRecord->nilai_data ?? [];
            $catatanData = $nilaiRecord->catatan_data ?? [];
            $semesterKey = "semester_{$this->semester}";
            $bulanKey = "bulan_{$this->bulan}";
            $mingguKey = "minggu_{$this->minggu}";

            // Process setiap indikator
            foreach ($indikators as $indikator) {
                $columnKey = 'indikator_' . $indikator->id;
                $catatanColumnKey = 'catatan_indikator_' . $indikator->id;

                if (isset($row[$columnKey]) && !empty($row[$columnKey])) {
                    $nilaiValue = (int) $row[$columnKey];

                    // Validasi nilai (1-4)
                    if ($nilaiValue >= 1 && $nilaiValue <= 4) {
                        $aspekKey = "aspek_{$indikator->aspek_id}";
                        $indikatorKey = "indikator_{$indikator->id}";

                        // Set nilai
                        $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] = $nilaiValue;

                        // Set catatan - prioritaskan template dari database
                        $catatan = $this->generateCatatanFromTemplate($indikator, $nilaiValue, $row[$catatanColumnKey] ?? null);

                        $catatanData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] = $catatan;
                    }
                }
            }

            // Update record
            $nilaiRecord->update([
                'nilai_data' => $nilaiData,
                'catatan_data' => $catatanData,
            ]);
        }
    }

    /**
     * Generate catatan dari template database atau fallback ke template manual
     */
    private function generateCatatanFromTemplate($indikator, $nilaiNumerik, $catatanExcel = null)
    {
        // Jika ada catatan dari Excel dan tidak kosong, gunakan itu
        if (!empty($catatanExcel)) {
            return $catatanExcel;
        }

        // Coba ambil dari database template_catatan
        $template = $this->getTemplateCatatan($indikator->id, $nilaiNumerik);
        if ($template) {
            return $template->isi_template;
        }

        // Fallback: generate manual dengan format yang sama seperti seeder
        return $this->generateManualTemplate($indikator, $nilaiNumerik);
    }

    /**
     * Generate template manual dengan format yang sama seperti TemplateCatatanSeeder
     */
    private function generateManualTemplate($indikator, $nilaiNumerik)
    {
        $nilaiMapping = [1 => 'BB', 2 => 'MB', 3 => 'BSH', 4 => 'BSB'];
        $nilaiKode = $nilaiMapping[$nilaiNumerik] ?? 'BB';

        // Template yang sama dengan TemplateCatatanSeeder.php
        $templateCatatan = [
            'BB' => [
                'belum menunjukkan kemampuan dalam {indikator} dan masih memerlukan bimbingan intensif.',
                'masih dalam tahap pengenalan {indikator} dan perlu dukungan lebih.',
                'belum mampu {indikator} secara mandiri dan membutuhkan bantuan penuh.',
            ],
            'MB' => [
                'masih membutuhkan bimbingan dalam {indikator} namun menunjukkan minat untuk belajar.',
                'sudah mulai menunjukkan kemampuan {indikator} dengan bantuan guru.',
                'dalam proses mengembangkan kemampuan {indikator} dan perlu pendampingan.',
            ],
            'BSH' => [
                'mulai berkembang dalam {indikator} dan dapat melakukan dengan sedikit bantuan.',
                'menunjukkan perkembangan yang baik dalam {indikator}.',
                'sudah mampu {indikator} dengan bimbingan minimal.',
            ],
            'BSB' => [
                'sudah sangat baik dalam {indikator} dan dapat melakukan secara mandiri.',
                'menguasai {indikator} dengan sangat baik dan dapat membantu teman.',
                'berkembang sangat baik dalam {indikator} dan menjadi contoh bagi teman-teman.',
            ],
        ];

        // Pilih template pertama dari array (atau bisa random)
        $selectedTemplate = $templateCatatan[$nilaiKode][0];
        
        // Replace placeholder dengan nama indikator
        $isiTemplate = 'Ananda ' . str_replace(
            '{indikator}', 
            strtolower($indikator->nama_indikator), 
            $selectedTemplate
        );

        return $isiTemplate;
    }

    /**
     * Ambil template catatan dari database
     */
    private function getTemplateCatatan($indikatorId, $nilaiNumerik)
    {
        $nilaiMapping = [1 => 'BB', 2 => 'MB', 3 => 'BSH', 4 => 'BSB'];
        $nilaiKode = $nilaiMapping[$nilaiNumerik] ?? null;

        if (!$nilaiKode) {
            return null;
        }

        return TemplateCatatan::where('indikator_id', $indikatorId)
                             ->where('nilai', $nilaiKode)
                             ->first();
    }
}
