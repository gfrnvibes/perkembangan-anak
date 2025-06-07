<?php

namespace Database\Seeders;

use App\Models\Anak;
use App\Models\Nilai;
use App\Models\Indikator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data nilai yang sudah ada (opsional)
        // Nilai::truncate();

        // Ambil semua anak dan indikator
        $anaks = Anak::all();
        $indikators = Indikator::with('aspek')->get();

        $this->command->info('Jumlah anak: ' . $anaks->count());
        $this->command->info('Jumlah indikator: ' . $indikators->count());
        $this->command->info('Jumlah aspek: ' . $indikators->pluck('aspek')->unique()->count());

        // Cek apakah data ada
        if ($anaks->count() == 0) {
            $this->command->error('Tidak ada data anak. Jalankan seeder Anak terlebih dahulu.');
            return;
        }

        if ($indikators->count() == 0) {
            $this->command->error('Tidak ada data indikator. Jalankan seeder Indikator terlebih dahulu.');
            return;
        }

        if ($indikators->aspek->unique()->count() == 0) {
            $this->command->error('Tidak ada data indikator. Jalankan seeder Indikator terlebih dahulu.');
            return;
        }



        // Array nilai yang mungkin (1=BB, 2=MB, 3=BSH, 4=BSB)
        $nilaiOptions = [1, 2, 3, 4];
        $mingguOptions = [1, 2, 3, 4]; // Minggu 1-4
        
        // Semester Ganjil: Juli-Desember (bulan 7-12)
        // Semester Genap: Januari-Juni (bulan 1-6)
        $semesterGanjil = [7, 8, 9, 10, 11, 12]; // Jul, Aug, Sep, Oct, Nov, Dec
        $semesterGenap = [1, 2, 3, 4, 5, 6];     // Jan, Feb, Mar, Apr, May, Jun
        
        $tahun = 2024;
        $totalData = 0;

        DB::beginTransaction();
        
        try {
            // Loop untuk setiap anak
            foreach ($anaks as $anakIndex => $anak) {
                $this->command->info('Memproses anak: ' . $anak->nama_lengkap . ' (' . ($anakIndex + 1) . '/' . $anaks->count() . ')');
                
                // Loop untuk setiap indikator
                foreach ($indikators as $indikatorIndex => $indikator) {
                    
                    // Loop untuk semester ganjil (Juli-Desember)
                    foreach ($semesterGanjil as $bulanIndex => $bulan) {
                        
                        // Loop untuk setiap minggu (1-4)
                        foreach ($mingguOptions as $minggu) {
                            
                            // Cek apakah data sudah ada
                            $existingNilai = Nilai::where('anak_id', $anak->id)
                                ->where('indikator_id', $indikator->id)
                                ->where('minggu', $minggu)
                                ->where('bulan', $bulan)
                                ->where('tahun', $tahun)
                                ->first();

                            if (!$existingNilai) {
                                // Buat variasi nilai berdasarkan kombinasi index
                                $nilaiIndex = ($anakIndex + $indikatorIndex + $bulanIndex + $minggu) % 4;
                                $nilai = $nilaiOptions[$nilaiIndex];

                                Nilai::create([
                                    'anak_id' => $anak->id,
                                    'indikator_id' => $indikator->id,
                                    'nilai_numerik' => $nilai,
                                    'minggu' => $minggu,
                                    'bulan' => $bulan,
                                    'tahun' => $tahun
                                ]);
                                $totalData++;
                            }
                        }
                    }
                    
                    // Loop untuk semester genap (Januari-Juni)
                    foreach ($semesterGenap as $bulanIndex => $bulan) {
                        
                        // Loop untuk setiap minggu (1-4)
                        foreach ($mingguOptions as $minggu) {
                            
                            // Cek apakah data sudah ada
                            $existingNilai = Nilai::where('anak_id', $anak->id)
                                ->where('indikator_id', $indikator->id)
                                ->where('minggu', $minggu)
                                ->where('bulan', $bulan)
                                ->where('tahun', $tahun)
                                ->first();

                            if (!$existingNilai) {
                                // Buat variasi nilai berdasarkan kombinasi index
                                $nilaiIndex = ($anakIndex + $indikatorIndex + $bulanIndex + $minggu + 6) % 4;
                                $nilai = $nilaiOptions[$nilaiIndex];

                                Nilai::create([
                                    'anak_id' => $anak->id,
                                    'indikator_id' => $indikator->id,
                                    'nilai_numerik' => $nilai,
                                    'minggu' => $minggu,
                                    'bulan' => $bulan,
                                    'tahun' => $tahun
                                ]);
                                $totalData++;
                            }
                        }
                    }
                }
                
                // Progress indicator setiap 5 anak
                if (($anakIndex + 1) % 5 == 0) {
                    $this->command->info('Progress: ' . ($anakIndex + 1) . '/' . $anaks->count() . ' anak selesai. Total data: ' . $totalData);
                }
            }

            DB::commit();
            
            $expectedTotal = $anaks->count() * $indikators->count() * 12 * 4; // anak × indikator × bulan × minggu
            $this->command->info('=== SELESAI ===');
            $this->command->info('Berhasil membuat ' . $totalData . ' data nilai baru.');
            $this->command->info('Expected total: ' . $expectedTotal . ' records');
            $this->command->info('Breakdown:');
            $this->command->info('- Jumlah anak: ' . $anaks->count());
            $this->command->info('- Jumlah indikator: ' . $indikators->count());
            $this->command->info('- Jumlah bulan: 12 (6 semester ganjil + 6 semester genap)');
            $this->command->info('- Jumlah minggu per bulan: 4');
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('Error: ' . $e->getMessage());
            $this->command->error('Line: ' . $e->getLine());
            $this->command->error('File: ' . $e->getFile());
        }
    }
}
