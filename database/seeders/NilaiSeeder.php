<?php

namespace Database\Seeders;

use App\Models\Anak;
use App\Models\Nilai;
use App\Models\Aspek;
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

        // Ambil semua anak, aspek, dan indikator
        $anaks = Anak::all();
        $aspeks = Aspek::with('indikators')->get();

        $this->command->info('Jumlah anak: ' . $anaks->count());
        $this->command->info('Jumlah aspek: ' . $aspeks->count());
        $this->command->info('Total indikator: ' . $aspeks->sum(fn($a) => $a->indikators->count()));

        // Cek apakah data ada
        if ($anaks->count() == 0) {
            $this->command->error('Tidak ada data anak. Jalankan seeder Anak terlebih dahulu.');
            return;
        }

        if ($aspeks->count() == 0) {
            $this->command->error('Tidak ada data aspek. Jalankan seeder Aspek terlebih dahulu.');
            return;
        }

        // Array nilai yang mungkin (1=BB, 2=MB, 3=BSH, 4=BSB)
        $nilaiOptions = [1, 2, 3, 4];
        $tahun = 2025;

        $totalData = 0;

        DB::beginTransaction();
        
        try {
            foreach ($anaks->take(10) as $anakIndex => $anak) {
                $this->command->info("Memproses anak: {$anak->nama_lengkap}");
                
                // Cek apakah sudah ada data untuk anak ini di tahun ini
                $existingNilai = Nilai::where('anak_id', $anak->id)
                    ->where('tahun', $tahun)
                    ->first();

                if ($existingNilai) {
                    $this->command->info("Data sudah ada untuk {$anak->nama_lengkap}, skip...");
                    continue;
                }

                // Inisialisasi struktur data
                $nilaiData = [
                    'semester_ganjil' => [],
                    'semester_genap' => []
                ];
                
                $catatanData = [
                    'semester_ganjil' => [],
                    'semester_genap' => []
                ];

                // Generate data untuk setiap aspek dan indikator
                foreach ($aspeks as $aspekIndex => $aspek) {
                    $aspekKey = "aspek_{$aspek->id}";
                    
                    // Inisialisasi aspek di kedua semester
                    $nilaiData['semester_ganjil'][$aspekKey] = [];
                    $nilaiData['semester_genap'][$aspekKey] = [];
                    $catatanData['semester_ganjil'][$aspekKey] = [];
                    $catatanData['semester_genap'][$aspekKey] = [];

                    foreach ($aspek->indikators as $indikatorIndex => $indikator) {
                        $indikatorKey = "indikator_{$indikator->id}";
                        
                        // Inisialisasi indikator di kedua semester
                        $nilaiData['semester_ganjil'][$aspekKey][$indikatorKey] = [];
                        $nilaiData['semester_genap'][$aspekKey][$indikatorKey] = [];
                        $catatanData['semester_ganjil'][$aspekKey][$indikatorKey] = [];
                        $catatanData['semester_genap'][$aspekKey][$indikatorKey] = [];

                        // Generate data untuk semester ganjil (Juli-Desember: bulan 7-12)
                        for ($bulan = 7; $bulan <= 12; $bulan++) {
                            $bulanKey = "bulan_{$bulan}";
                            $nilaiData['semester_ganjil'][$aspekKey][$indikatorKey][$bulanKey] = [];
                            $catatanData['semester_ganjil'][$aspekKey][$indikatorKey][$bulanKey] = [];

                            // Generate data untuk 4 minggu
                            for ($minggu = 1; $minggu <= 4; $minggu++) {
                                $mingguKey = "minggu_{$minggu}";
                                
                                // Buat pola nilai yang berbeda untuk setiap anak
                                $nilaiIndex = ($anakIndex + $aspekIndex + $indikatorIndex + $bulan + $minggu) % 4;
                                $nilai = $nilaiOptions[$nilaiIndex];
                                
                                $nilaiData['semester_ganjil'][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] = $nilai;
                                $catatanData['semester_ganjil'][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] = $this->generateCatatan($nilai, $anak->nama_lengkap, $indikator->nama_indikator);
                            }
                        }

                        // Generate data untuk semester genap (Januari-Juni: bulan 1-6)
                        for ($bulan = 1; $bulan <= 6; $bulan++) {
                            $bulanKey = "bulan_{$bulan}";
                            $nilaiData['semester_genap'][$aspekKey][$indikatorKey][$bulanKey] = [];
                            $catatanData['semester_genap'][$aspekKey][$indikatorKey][$bulanKey] = [];

                            // Generate data untuk 4 minggu
                            for ($minggu = 1; $minggu <= 4; $minggu++) {
                                $mingguKey = "minggu_{$minggu}";
                                
                                // Buat pola nilai yang berbeda untuk setiap anak (semester genap cenderung lebih baik)
                                $nilaiIndex = ($anakIndex + $aspekIndex + $indikatorIndex + $bulan + $minggu + 1) % 4;
                                $nilai = $nilaiOptions[$nilaiIndex];
                                // Semester genap cenderung nilai lebih tinggi (simulasi perkembangan)
                                if ($nilai < 4) $nilai = min(4, $nilai + 1);
                                
                                $nilaiData['semester_genap'][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] = $nilai;
                                $catatanData['semester_genap'][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] = $this->generateCatatan($nilai, $anak->nama_lengkap, $indikator->nama_indikator);
                            }
                        }
                    }
                }

                // Simpan ke database
                Nilai::create([
                    'anak_id' => $anak->id,
                    'tahun' => $tahun,
                    'nilai_data' => $nilaiData,
                    'catatan_data' => $catatanData
                ]);

                $totalData++;
                $this->command->info("✓ Data berhasil dibuat untuk {$anak->nama_lengkap}");
            }

            DB::commit();
            $this->command->info("🎉 Berhasil membuat {$totalData} record nilai ultra optimized!");
            $this->command->info("📊 Struktur: 1 record per anak per tahun dengan nested JSON");
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('❌ Error: ' . $e->getMessage());
            $this->command->error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Generate catatan berdasarkan nilai
     */
    private function generateCatatan($nilai, $namaAnak, $namaIndikator): string
    {
        $templates = [
            1 => [ // BB - Belum Berkembang
                "{$namaAnak} masih memerlukan bimbingan lebih dalam {$namaIndikator}.",
                "{$namaAnak} belum menunjukkan kemampuan {$namaIndikator} secara konsisten.",
                "Perlu pendampingan khusus untuk {$namaAnak} dalam aspek {$namaIndikator}.",
                "{$namaAnak} masih dalam tahap awal pengembangan {$namaIndikator}."
            ],
            2 => [ // MB - Mulai Berkembang
                "{$namaAnak} mulai menunjukkan perkembangan dalam {$namaIndikator}.",
                "{$namaAnak} sudah mulai memahami konsep {$namaIndikator} dengan bantuan.",
                "Terlihat kemajuan pada {$namaAnak} untuk kemampuan {$namaIndikator}.",
                "{$namaAnak} menunjukkan minat dan usaha dalam {$namaIndikator}."
            ],
            3 => [ // BSH - Berkembang Sesuai Harapan
                "{$namaAnak} menunjukkan kemampuan {$namaIndikator} sesuai dengan usianya.",
                "{$namaAnak} dapat melakukan {$namaIndikator} dengan baik dan konsisten.",
                "Perkembangan {$namaAnak} dalam {$namaIndikator} sudah sesuai target.",
                "{$namaAnak} menguasai {$namaIndikator} dengan baik."
            ],
            4 => [ // BSB - Berkembang Sangat Baik
                "{$namaAnak} menunjukkan kemampuan {$namaIndikator} yang sangat baik.",
                "{$namaAnak} unggul dalam {$namaIndikator} dan dapat membantu teman.",
                "Prestasi {$namaAnak} dalam {$namaIndikator} sangat memuaskan.",
                "{$namaAnak} menguasai {$namaIndikator} dengan sangat baik dan kreatif."
            ]
        ];

        $catatanArray = $templates[$nilai] ?? $templates[2];
        return $catatanArray[array_rand($catatanArray)];
    }
}
