<?php

namespace App\Livewire\Admin\Nilai;

use App\Models\Anak;
use App\Models\Nilai;
use App\Models\Aspek;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

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
        $today = Carbon::now();
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        return $startOfWeek->weekOfYear;
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
        if (!$this->selectedWeek || !$this->selectedYear) {
            return [];
        }

        $nilaiData = Nilai::with(['indikator.aspek', 'catatanAnak.templateCatatan'])
            ->where('anak_id', $this->selectedAnak)
            ->where('minggu', $this->selectedWeek)
            ->where('tahun', $this->selectedYear)
            ->orderBy('created_at')
            ->get();

        // Group by aspek
        $result = [];
        foreach ($nilaiData as $nilai) {
            $aspekNama = $nilai->indikator->aspek->nama_aspek;
            if (!isset($result[$aspekNama])) {
                $result[$aspekNama] = collect();
            }
            $result[$aspekNama]->push($nilai);
        }

        return $result;
    }

private function getNilaiBulanan()
{
    if (!$this->selectedMonth || !$this->selectedYear) {
        return [];
    }

    // Ambil nilai tertinggi per indikator per minggu dalam bulan tersebut
    $nilaiPerMinggu = [];

    // Cari semua nilai dalam bulan yang dipilih
    $nilaiData = Nilai::with(['indikator.aspek', 'catatanAnak.templateCatatan'])
        ->where('anak_id', $this->selectedAnak)
        ->where('bulan', $this->selectedMonth)
        ->where('tahun', $this->selectedYear)
        ->get();

    // Debug: tambahkan ini untuk melihat data yang ditemukan
    \Log::info('Data nilai ditemukan:', [
        'count' => $nilaiData->count(),
        'anak_id' => $this->selectedAnak,
        'bulan' => $this->selectedMonth,
        'tahun' => $this->selectedYear,
        'data' => $nilaiData->toArray()
    ]);

    // Group by minggu dan indikator, ambil nilai tertinggi
    foreach ($nilaiData as $nilai) {
        $indikatorId = $nilai->indikator_id;
        $minggu = $nilai->minggu; // Gunakan field minggu langsung dari database
        
        if (!isset($nilaiPerMinggu[$indikatorId])) {
            $nilaiPerMinggu[$indikatorId] = [];
        }
        
        // Pastikan minggu dalam range 1-4
        if ($minggu >= 1 && $minggu <= 4) {
            $mingguKey = "minggu_$minggu";
            
            // Ambil nilai tertinggi jika ada multiple nilai untuk minggu yang sama
            if (!isset($nilaiPerMinggu[$indikatorId][$mingguKey]) || 
                $nilai->nilai_numerik > $nilaiPerMinggu[$indikatorId][$mingguKey]) {
                $nilaiPerMinggu[$indikatorId][$mingguKey] = $nilai->nilai_numerik;
            }
        }
    }

    // Format data untuk tampilan
    $result = [];
    foreach ($nilaiPerMinggu as $indikatorId => $mingguData) {
        $indikator = \App\Models\Indikator::with('aspek')->find($indikatorId);
        if ($indikator) {
            $aspekNama = $indikator->aspek->nama_aspek;
            if (!isset($result[$aspekNama])) {
                $result[$aspekNama] = collect();
            }

            $nilaiObj = new \stdClass();
            $nilaiObj->indikator = $indikator;
            $nilaiObj->minggu_data = $mingguData;
            
            // Hitung capaian akhir bulan (nilai tertinggi dari semua minggu)
            $nilaiObj->capaian_akhir_bulan = !empty($mingguData) ? max($mingguData) : null;

            $result[$aspekNama]->push($nilaiObj);
        }
    }

    return $result;
}


    private function getNilaiSemesteran()
    {
        if (!$this->selectedSemester || !$this->selectedYear) {
            return [];
        }

        // Tentukan bulan berdasarkan semester
        if ($this->selectedSemester == 'ganjil') {
            $bulanSemester = [7, 8, 9, 10, 11, 12]; // Jul-Des
        } else {
            $bulanSemester = [1, 2, 3, 4, 5, 6]; // Jan-Jun
        }

        // Ambil nilai tertinggi per indikator per bulan
        $nilaiPerBulan = [];

        foreach ($bulanSemester as $bulan) {
            $nilaiBulanIni = Nilai::with(['indikator.aspek', 'catatanAnak.templateCatatan'])
                ->where('anak_id', $this->selectedAnak)
                ->where('bulan', $bulan)
                ->where('tahun', $this->selectedYear)
                ->select('indikator_id', DB::raw('MAX(nilai_numerik) as nilai_tertinggi'))
                ->groupBy('indikator_id')
                ->get();

            foreach ($nilaiBulanIni as $nilai) {
                $indikatorId = $nilai->indikator_id;
                if (!isset($nilaiPerBulan[$indikatorId])) {
                    $nilaiPerBulan[$indikatorId] = [];
                }
                $bulanName = Carbon::create($this->selectedYear, $bulan, 1)->format('M');
                $nilaiPerBulan[$indikatorId][$bulanName] = $nilai->nilai_tertinggi;
            }
        }

        // Format data untuk tampilan
        $result = [];
        foreach ($nilaiPerBulan as $indikatorId => $bulanData) {
            $indikator = \App\Models\Indikator::with('aspek')->find($indikatorId);
            if ($indikator) {
                $aspekNama = $indikator->aspek->nama_aspek;
                if (!isset($result[$aspekNama])) {
                    $result[$aspekNama] = collect();
                }

                $nilaiObj = new \stdClass();
                $nilaiObj->indikator = $indikator;
                $nilaiObj->bulan_data = $bulanData;

                // Hitung capaian akhir semester (nilai tertinggi dari semua bulan)
                $nilaiObj->capaian_akhir_semester = !empty($bulanData) ? max($bulanData) : null;

                $result[$aspekNama]->push($nilaiObj);
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
                // Generate data nilai untuk setiap anak secara langsung
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
        switch ($this->selectedPeriode) {
            case 'mingguan':
                return $this->getNilaiMingguanForAnak($anakId);
            case 'bulanan':
                return $this->getNilaiBulananForAnak($anakId);
            case 'semesteran':
                return $this->getNilaiSemesteranForAnak($anakId);
            default:
                return [];
        }
    }

    private function getNilaiMingguanForAnak($anakId)
    {
        if (!$this->selectedWeek || !$this->selectedYear) {
            return [];
        }

        $nilaiData = Nilai::with(['indikator.aspek', 'catatanAnak.templateCatatan'])
            ->where('anak_id', $anakId)
            ->where('minggu', $this->selectedWeek)
            ->where('tahun', $this->selectedYear)
            ->orderBy('created_at')
            ->get();

        // Group by aspek
        $result = [];
        foreach ($nilaiData as $nilai) {
            $aspekNama = $nilai->indikator->aspek->nama_aspek;
            if (!isset($result[$aspekNama])) {
                $result[$aspekNama] = collect();
            }
            $result[$aspekNama]->push($nilai);
        }

        return $result;
    }

private function getNilaiBulananForAnak($anakId)
{
    if (!$this->selectedMonth || !$this->selectedYear) {
        return [];
    }

    $nilaiPerMinggu = [];

    // Cari semua nilai dalam bulan yang dipilih
    $nilaiData = Nilai::with(['indikator.aspek', 'catatanAnak.templateCatatan'])
        ->where('anak_id', $anakId)
        ->where('bulan', $this->selectedMonth)
        ->where('tahun', $this->selectedYear)
        ->get();

    // Group by minggu dan indikator, ambil nilai tertinggi
    foreach ($nilaiData as $nilai) {
        $indikatorId = $nilai->indikator_id;
        $minggu = $nilai->minggu;
        
        if (!isset($nilaiPerMinggu[$indikatorId])) {
            $nilaiPerMinggu[$indikatorId] = [];
        }
        
        // Pastikan minggu dalam range 1-4
        if ($minggu >= 1 && $minggu <= 4) {
            $mingguKey = "minggu_$minggu";
            
            // Ambil nilai tertinggi jika ada multiple nilai untuk minggu yang sama
            if (!isset($nilaiPerMinggu[$indikatorId][$mingguKey]) || 
                $nilai->nilai_numerik > $nilaiPerMinggu[$indikatorId][$mingguKey]) {
                $nilaiPerMinggu[$indikatorId][$mingguKey] = $nilai->nilai_numerik;
            }
        }
    }

    $result = [];
    foreach ($nilaiPerMinggu as $indikatorId => $mingguData) {
        $indikator = \App\Models\Indikator::with('aspek')->find($indikatorId);
        if ($indikator) {
            $aspekNama = $indikator->aspek->nama_aspek;
            if (!isset($result[$aspekNama])) {
                $result[$aspekNama] = collect();
            }

            $nilaiObj = new \stdClass();
            $nilaiObj->indikator = $indikator;
            $nilaiObj->minggu_data = $mingguData;
            $nilaiObj->capaian_akhir_bulan = !empty($mingguData) ? max($mingguData) : null;

            $result[$aspekNama]->push($nilaiObj);
        }
    }

    return $result;
}


    private function getNilaiSemesteranForAnak($anakId)
    {
        if (!$this->selectedSemester || !$this->selectedYear) {
            return [];
        }

        // Tentukan bulan berdasarkan semester
        if ($this->selectedSemester == 'ganjil') {
            $bulanSemester = [7, 8, 9, 10, 11, 12]; // Jul-Des
        } else {
            $bulanSemester = [1, 2, 3, 4, 5, 6]; // Jan-Jun
        }

        $nilaiPerBulan = [];

        foreach ($bulanSemester as $bulan) {
            $nilaiBulanIni = Nilai::with(['indikator.aspek', 'catatanAnak.templateCatatan'])
                ->where('anak_id', $anakId)
                ->where('bulan', $bulan)
                ->where('tahun', $this->selectedYear)
                ->select('indikator_id', DB::raw('MAX(nilai_numerik) as nilai_tertinggi'))
                ->groupBy('indikator_id')
                ->get();

            foreach ($nilaiBulanIni as $nilai) {
                $indikatorId = $nilai->indikator_id;
                if (!isset($nilaiPerBulan[$indikatorId])) {
                    $nilaiPerBulan[$indikatorId] = [];
                }
                $bulanName = Carbon::create($this->selectedYear, $bulan, 1)->format('M');
                $nilaiPerBulan[$indikatorId][$bulanName] = $nilai->nilai_tertinggi;
            }
        }

        $result = [];
        foreach ($nilaiPerBulan as $indikatorId => $bulanData) {
            $indikator = \App\Models\Indikator::with('aspek')->find($indikatorId);
            if ($indikator) {
                $aspekNama = $indikator->aspek->nama_aspek;
                if (!isset($result[$aspekNama])) {
                    $result[$aspekNama] = collect();
                }

                $nilaiObj = new \stdClass();
                $nilaiObj->indikator = $indikator;
                $nilaiObj->bulan_data = $bulanData;
                $nilaiObj->capaian_akhir_semester = !empty($bulanData) ? max($bulanData) : null;

                $result[$aspekNama]->push($nilaiObj);
            }
        }

        return $result;
    }

    private function generateFilename($anak)
    {
        $periode = '';
        switch ($this->selectedPeriode) {
            case 'mingguan':
                $periode = 'Mingguan_Minggu' . $this->selectedWeek . '_' . $this->selectedYear;
                break;
            case 'bulanan':
                $periode = 'Bulanan_' . Carbon::create($this->selectedYear, $this->selectedMonth)->format('m-Y');
                break;
            case 'semesteran':
                $periode = 'Semester_' . ucfirst($this->selectedSemester) . '_' . $this->selectedYear;
                break;
        }

        return 'Laporan_Nilai_' . str_replace(' ', '_', $anak->nama_lengkap) . '_' . $periode . '.pdf';
    }

    public function getWeekOptions()
    {
        $weeks = [];
        $year = $this->selectedYear ?: date('Y');

        // Generate 52 weeks for the year
        for ($week = 1; $week <= 52; $week++) {
            $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $endOfWeek = $startOfWeek->copy()->endOfWeek();

            $weeks[$week] = "Minggu ke-$week ({$startOfWeek->format('d/m')} - {$endOfWeek->format('d/m')})";
        }

        return $weeks;
    }

    public function getMonthOptions()
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
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
