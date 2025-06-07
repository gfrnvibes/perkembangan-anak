<?php

namespace App\Livewire\Admin\Nilai;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Anak;
use App\Models\Nilai;
use App\Models\Aspek;
use Livewire\Component;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Title('Daftar Nilai')]
#[Layout('layouts.master')]
class DaftarNilai extends Component
{
    public $selectedAnak;
    public $selectedPeriode = 'mingguan';
    public $selectedWeek;
    public $selectedMonth;
    public $selectedSemester;
    public $selectedYear;

    public $anakList = [];
    public $nilaiData = [];

    // Mapping nilai
    public $nilaiMapping = [
        1 => 'BB',
        2 => 'MB',
        3 => 'BSH',
        4 => 'BSB',
    ];

    public function mount()
    {
        $this->anakList = Anak::all();
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('n');
        $this->selectedWeek = $this->getCurrentWeek();
        $this->selectedSemester = $this->getCurrentSemester();
    }

    public function getCurrentWeek()
    {
        return 1; // Default minggu 1
    }

    public function getCurrentSemester()
    {
        $month = date('n');
        return $month >= 7 && $month <= 12 ? 'ganjil' : 'genap';
    }

    public function updatedSelectedAnak()
    {
        $this->loadNilaiData();
    }

    public function updatedSelectedPeriode()
    {
        $this->loadNilaiData();
    }

    public function updatedSelectedWeek()
    {
        $this->loadNilaiData();
    }

    public function updatedSelectedMonth()
    {
        $this->loadNilaiData();
    }

    public function updatedSelectedSemester()
    {
        $this->loadNilaiData();
    }

    public function updatedSelectedYear()
    {
        $this->loadNilaiData();
    }

    public function loadNilaiData()
    {
        if (!$this->selectedAnak) {
            $this->nilaiData = [];
            return;
        }

        switch ($this->selectedPeriode) {
            case 'mingguan':
                $this->nilaiData = $this->getNilaiMingguan();
                break;
            case 'bulanan':
                $this->nilaiData = $this->getNilaiBulanan();
                break;
            case 'semesteran':
                $this->nilaiData = $this->getNilaiSemesteran();
                break;
        }
    }

    private function getNilaiMingguan()
    {
        if (!$this->selectedWeek || !$this->selectedYear || !$this->selectedMonth) {
            return [];
        }

        // Ambil data nilai dari JSON structure
        $nilaiRecord = Nilai::where('anak_id', $this->selectedAnak)
            ->where('tahun', $this->selectedYear)
            ->first();

        if (!$nilaiRecord) {
            return [];
        }

        $nilaiData = $nilaiRecord->nilai_data ?? [];
        $catatanData = $nilaiRecord->catatan_data ?? [];
        
        // Tentukan semester berdasarkan bulan
        $semesterKey = $this->selectedMonth >= 7 && $this->selectedMonth <= 12 ? 'semester_ganjil' : 'semester_genap';
        $bulanKey = "bulan_{$this->selectedMonth}";
        $mingguKey = "minggu_{$this->selectedWeek}";

        // Ambil semua aspek dengan indikator
        $aspeks = Aspek::with('indikators')->get();
        $result = [];

        foreach ($aspeks as $aspek) {
            $aspekKey = "aspek_{$aspek->id}";
            $aspekNama = $aspek->nama_aspek;
            
            if (!isset($result[$aspekNama])) {
                $result[$aspekNama] = collect();
            }

            foreach ($aspek->indikators as $indikator) {
                $indikatorKey = "indikator_{$indikator->id}";
                
                // Ambil nilai dari JSON structure
                $nilai = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] ?? null;
                $catatan = $catatanData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] ?? null;

                if ($nilai) {
                    // Buat object nilai
                    $nilaiObj = new \stdClass();
                    $nilaiObj->indikator = $indikator;
                    $nilaiObj->nilai_numerik = $nilai;
                    $nilaiObj->catatan = $catatan;

                    $result[$aspekNama]->push($nilaiObj);
                }
            }
        }

        return $result;
    }

    private function getNilaiBulanan()
    {
        if (!$this->selectedMonth || !$this->selectedYear) {
            return [];
        }

        $nilaiRecord = Nilai::where('anak_id', $this->selectedAnak)
            ->where('tahun', $this->selectedYear)
            ->first();

        if (!$nilaiRecord) {
            return [];
        }

        $nilaiData = $nilaiRecord->nilai_data ?? [];
        
        // Tentukan semester berdasarkan bulan
        $semesterKey = $this->selectedMonth >= 7 && $this->selectedMonth <= 12 ? 'semester_ganjil' : 'semester_genap';
        $bulanKey = "bulan_{$this->selectedMonth}";

        $aspeks = Aspek::with('indikators')->get();
        $result = [];

        foreach ($aspeks as $aspek) {
            $aspekKey = "aspek_{$aspek->id}";
            $aspekNama = $aspek->nama_aspek;
            
            if (!isset($result[$aspekNama])) {
                $result[$aspekNama] = collect();
            }

            foreach ($aspek->indikators as $indikator) {
                $indikatorKey = "indikator_{$indikator->id}";
                
                $mingguData = [];
                $nilaiTertinggi = 0;

                // Ambil data untuk 4 minggu
                for ($minggu = 1; $minggu <= 4; $minggu++) {
                    $mingguKey = "minggu_{$minggu}";
                    $nilai = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] ?? null;
                    
                    if ($nilai) {
                        $mingguData[$mingguKey] = $nilai;
                        $nilaiTertinggi = max($nilaiTertinggi, $nilai);
                    }
                }

                if (!empty($mingguData)) {
                    $nilaiObj = new \stdClass();
                    $nilaiObj->indikator = $indikator;
                    $nilaiObj->minggu_data = $mingguData;
                    $nilaiObj->capaian_akhir_bulan = $nilaiTertinggi ?: null;

                    $result[$aspekNama]->push($nilaiObj);
                }
            }
        }

        return $result;
    }

    private function getNilaiSemesteran()
    {
        if (!$this->selectedSemester || !$this->selectedYear) {
            return [];
        }

        $nilaiRecord = Nilai::where('anak_id', $this->selectedAnak)
            ->where('tahun', $this->selectedYear)
            ->first();

        if (!$nilaiRecord) {
            return [];
        }

        $nilaiData = $nilaiRecord->nilai_data ?? [];
        $semesterKey = "semester_{$this->selectedSemester}";

        // Tentukan bulan berdasarkan semester
        $bulanSemester = $this->selectedSemester == 'ganjil' 
            ? [7, 8, 9, 10, 11, 12] 
            : [1, 2, 3, 4, 5, 6];

        $aspeks = Aspek::with('indikators')->get();
        $result = [];

        foreach ($aspeks as $aspek) {
            $aspekKey = "aspek_{$aspek->id}";
            $aspekNama = $aspek->nama_aspek;
            
            if (!isset($result[$aspekNama])) {
                $result[$aspekNama] = collect();
            }

            foreach ($aspek->indikators as $indikator) {
                $indikatorKey = "indikator_{$indikator->id}";
                
                $bulanData = [];
                $nilaiTertinggi = 0;

                foreach ($bulanSemester as $bulan) {
                    $bulanKey = "bulan_{$bulan}";
                    $bulanName = Carbon::create($this->selectedYear, $bulan, 1)->format('M');
                    
                    // Cari nilai tertinggi dalam bulan ini
                    $nilaiTertinggiBulan = 0;
                    for ($minggu = 1; $minggu <= 4; $minggu++) {
                        $mingguKey = "minggu_{$minggu}";
                        $nilai = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] ?? 0;
                        $nilaiTertinggiBulan = max($nilaiTertinggiBulan, $nilai);
                    }
                    
                    if ($nilaiTertinggiBulan > 0) {
                        $bulanData[$bulanName] = $nilaiTertinggiBulan;
                        $nilaiTertinggi = max($nilaiTertinggi, $nilaiTertinggiBulan);
                    }
                }

                if (!empty($bulanData)) {
                    $nilaiObj = new \stdClass();
                    $nilaiObj->indikator = $indikator;
                    $nilaiObj->bulan_data = $bulanData;
                    $nilaiObj->capaian_akhir_semester = $nilaiTertinggi ?: null;

                    $result[$aspekNama]->push($nilaiObj);
                }
            }
        }

        return $result;
    }

    public function downloadPDF()
    {
        if (!$this->selectedAnak || empty($this->nilaiData)) {
            session()->flash('error', 'Pilih anak dan pastikan ada data nilai untuk diunduh');
            return;
        }

        $anak = Anak::find($this->selectedAnak);
        $data = [
            'anak' => $anak,
            'nilaiData' => $this->nilaiData,
            'periode' => $this->selectedPeriode,
            'nilaiMapping' => $this->nilaiMapping,
            'selectedWeek' => $this->selectedWeek,
            'selectedMonth' => $this->selectedMonth,
            'selectedYear' => $this->selectedYear,
            'selectedSemester' => $this->selectedSemester,
        ];

        $pdf = PDF::loadView('livewire.admin.nilai.laporan-nilai', $data);
        $filename = $this->generateFilename($anak);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function downloadAllPDF()
    {
        $anakList = Anak::all();

        if ($anakList->isEmpty()) {
            session()->flash('error', 'Tidak ada data anak untuk diunduh');
            return;
        }

        $zip = new ZipArchive();
        $zipFileName = 'Laporan_Semua_Anak_' . $this->selectedPeriode . '_' . date('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($anakList as $anak) {
                $nilaiData = $this->generateNilaiDataForAnak($anak->id);

                if (!empty($nilaiData)) {
                    $data = [
                        'anak' => $anak,
                        'nilaiData' => $nilaiData,
                        'periode' => $this->selectedPeriode,
                        'nilaiMapping' => $this->nilaiMapping,
                        'selectedWeek' => $this->selectedWeek,
                        'selectedMonth' => $this->selectedMonth,
                        'selectedYear' => $this->selectedYear,
                        'selectedSemester' => $this->selectedSemester,
                    ];

                    $pdf = PDF::loadView('livewire.admin.nilai.laporan-nilai', $data);
                    $pdfContent = $pdf->output();

                    $filename = $this->generateFilename($anak);
                    $zip->addFromString($filename, $pdfContent);
                }
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        session()->flash('error', 'Gagal membuat file ZIP');
    }

    private function generateNilaiDataForAnak($anakId)
    {
        $originalSelectedAnak = $this->selectedAnak;
        $this->selectedAnak = $anakId;
        
        $result = match($this->selectedPeriode) {
            'mingguan' => $this->getNilaiMingguan(),
            'bulanan' => $this->getNilaiBulanan(),
            'semesteran' => $this->getNilaiSemesteran(),
            default => []
        };
        
        $this->selectedAnak = $originalSelectedAnak;
        return $result;
    }

    private function generateFilename($anak)
    {
        $periode = match($this->selectedPeriode) {
            'mingguan' => 'Mingguan_Minggu' . $this->selectedWeek . '_' . Carbon::create($this->selectedYear, $this->selectedMonth)->format('m-Y'),
            'bulanan' => 'Bulanan_' . Carbon::create($this->selectedYear, $this->selectedMonth)->format('m-Y'),
            'semesteran' => 'Semester_' . ucfirst($this->selectedSemester) . '_' . $this->selectedYear,
            default => 'Unknown'
        };

        return 'Laporan_Nilai_' . str_replace(' ', '_', $anak->nama_lengkap) . '_' . $periode . '.pdf';
    }

    public function getWeekOptions()
    {
        $weeks = [];
        for ($week = 1; $week <= 4; $week++) {
            $weeks[$week] = "Minggu ke-$week";
        }
        return $weeks;
    }

    public function getMonthOptions()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }

    public function render()
    {
        return view('livewire.admin.nilai.daftar-nilai', [
            'weekOptions' => $this->getWeekOptions(),
            'monthOptions' => $this->getMonthOptions(),
        ]);
    }
}
