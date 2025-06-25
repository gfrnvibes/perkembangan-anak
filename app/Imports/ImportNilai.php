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
            $anak = Anak::where('nomor_induk', $row['nomor_induk'])->orWhere('nama_lengkap', $row['nama_anak'])->first();

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

                        // Set catatan dari Excel atau auto-generate dari template
                        $catatan = $row[$catatanColumnKey] ?? null;
                        if (empty($catatan)) {
                            $template = $this->getTemplateCatatan($indikator->id, $nilaiValue);
                            $catatan = $template ? $template->isi_template : "Catatan untuk nilai {$nilaiValue}";
                        }

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

    private function getTemplateCatatan($indikatorId, $nilaiNumerik)
    {
        $nilaiMapping = [1 => 'BB', 2 => 'MB', 3 => 'BSH', 4 => 'BSB'];
        $nilaiKode = $nilaiMapping[$nilaiNumerik] ?? null;

        if (!$nilaiKode) {
            return null;
        }

        return TemplateCatatan::where('indikator_id', $indikatorId)->where('nilai', $nilaiKode)->first();
    }
}
