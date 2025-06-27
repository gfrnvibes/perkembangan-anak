<?php

namespace App\Livewire;

use App\Models\Anak;
use App\Models\Aspek;
use App\Models\Nilai;
use Livewire\Component;
use App\Models\Indikator;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

#[Title('Perkembangan Anak')]
class Perkembangan extends Component
{
    public $selectedSemester = 'ganjil';
    public $selectedBulan = null;
    public $selectedTahun;
    public $selectedAspek = null;
    public $selectedAnak = null;

    public $selectedPeriode = 'mingguan';
    public $selectedWeek;
    public $selectedMonth;
    public $nilaiData = [];

    public $anakList = [];
    public $aspekList = [];

    // Mapping nilai
    public $nilaiMapping = [
        1 => 'BB',
        2 => 'MB',
        3 => 'BSH',
        4 => 'BSB',
    ];

    public function mount()
    {
        $this->selectedTahun = date('Y');
        $this->selectedMonth = date('n');
        $this->selectedWeek = $this->getCurrentWeek();
        $this->selectedSemester = $this->getCurrentSemester();

        $this->loadNilaiData();

        // Ambil anak dari user yang sedang login
        $this->anakList = Anak::where('user_id', Auth::id())->get()->toArray();

        // Auto select anak pertama jika ada
        if (count($this->anakList) > 0) {
            $this->selectedAnak = $this->anakList[0]['id'];
        }

        $this->aspekList = Aspek::orderBy('id')->get()->toArray();
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

    public function updatedSelectedPeriode()
    {
        $this->loadNilaiData();
    }

    public function updatedSelectedWeek()
    {
        $this->loadNilaiData();
    }

    public function updatedSelectedAspek()
    {
        $this->dispatch('chart-update');
    }

    public function updatedSelectedSemester()
    {
        $this->dispatch('chart-update');
    }

    // Update method updatedSelectedMonth yang sudah ada
    public function updatedSelectedMonth()
    {
        $this->loadNilaiData();
    }

    // Update method updatedSelectedTahun yang sudah ada
    public function updatedSelectedTahun()
    {
        $this->dispatch('chart-update');
        $this->loadNilaiData();
    }

    // Update method updatedSelectedAnak yang sudah ada
    public function updatedSelectedAnak()
    {
        $this->dispatch('chart-update');
        $this->loadNilaiData();
    }

    public function getChartData()
    {
        if (!$this->selectedAnak) {
            return [
                'labels' => [],
                'datasets' => [],
            ];
        }

        $nilai = Nilai::where('anak_id', $this->selectedAnak)->where('tahun', $this->selectedTahun)->first();

        if (!$nilai || !$nilai->nilai_data) {
            return [
                'labels' => [],
                'datasets' => [],
            ];
        }

        // Jika ada filter aspek spesifik
        if ($this->selectedAspek) {
            return $this->getAspekChart($nilai);
        }

        // Default: tampilkan semua aspek
        return $this->getAllAspekChart($nilai);
    }

    private function getAspekChart($nilai)
    {
        $aspek = Aspek::find($this->selectedAspek);
        if (!$aspek) {
            return ['labels' => [], 'datasets' => []];
        }

        $indikators = Indikator::where('aspek_id', $this->selectedAspek)->orderBy('id')->get();

        $labels = [];
        $data = [];

        // Tentukan bulan berdasarkan semester
        $bulanRange = $this->selectedSemester == 'ganjil' ? [7, 8, 9, 10, 11, 12] : [1, 2, 3, 4, 5, 6];

        // Jika ada filter bulan spesifik
        if ($this->selectedBulan) {
            $bulanRange = [(int) $this->selectedBulan];
        }

        foreach ($indikators as $indikator) {
            $labels[] = $indikator->nama_indikator;

            $nilaiTertinggi = 0;

            foreach ($bulanRange as $bulan) {
                $nilaiData = is_array($nilai->nilai_data) ? $nilai->nilai_data : [];
                $semesterKey = "semester_{$this->selectedSemester}";
                $aspekKey = "aspek_{$this->selectedAspek}";
                $indikatorKey = "indikator_{$indikator->id}";
                $bulanKey = "bulan_{$bulan}";

                $bulanData = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey] ?? [];

                if (is_array($bulanData) && !empty($bulanData)) {
                    // Konversi semua nilai minggu ke angka
                    $mingguNilai = [];
                    foreach ($bulanData as $mingguData) {
                        $numericValue = $this->convertNilaiToNumeric($mingguData);
                        if ($numericValue > 0) {
                            $mingguNilai[] = $numericValue;
                        }
                    }
                    if (!empty($mingguNilai)) {
                        $nilaiTertinggi = max($nilaiTertinggi, max($mingguNilai));
                    }
                }
            }

            $data[] = $nilaiTertinggi;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $aspek->nama_aspek,
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    private function getAllAspekChart($nilai)
    {
        $aspeks = Aspek::orderBy('id')->get();
        $labels = [];
        $data = [];

        $bulanRange = $this->selectedSemester == 'ganjil' ? [7, 8, 9, 10, 11, 12] : [1, 2, 3, 4, 5, 6];

        if ($this->selectedBulan) {
            $bulanRange = [(int) $this->selectedBulan];
        }

        foreach ($aspeks as $aspek) {
            $labels[] = $aspek->nama_aspek;
            $indikators = Indikator::where('aspek_id', $aspek->id)->get();

            $nilaiTertinggiAspek = 0;

            foreach ($indikators as $indikator) {
                $nilaiTertinggiIndikator = 0;

                foreach ($bulanRange as $bulan) {
                    $nilaiData = is_array($nilai->nilai_data) ? $nilai->nilai_data : [];
                    $semesterKey = "semester_{$this->selectedSemester}";
                    $aspekKey = "aspek_{$aspek->id}";
                    $indikatorKey = "indikator_{$indikator->id}";
                    $bulanKey = "bulan_{$bulan}";

                    $bulanData = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey] ?? [];

                    if (is_array($bulanData) && !empty($bulanData)) {
                        $mingguNilai = [];
                        foreach ($bulanData as $mingguData) {
                            $numericValue = $this->convertNilaiToNumeric($mingguData);
                            if ($numericValue > 0) {
                                $mingguNilai[] = $numericValue;
                            }
                        }
                        if (!empty($mingguNilai)) {
                            $nilaiTertinggiIndikator = max($nilaiTertinggiIndikator, max($mingguNilai));
                        }
                    }
                }

                $nilaiTertinggiAspek = max($nilaiTertinggiAspek, $nilaiTertinggiIndikator);
            }

            $data[] = $nilaiTertinggiAspek;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nilai Tertinggi Perkembangan',
                    'data' => $data,
                    'backgroundColor' => ['rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)', 'rgba(255, 205, 86, 0.8)', 'rgba(75, 192, 192, 0.8)', 'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)'],
                    'borderColor' => ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    private function convertNilaiToNumeric($nilai)
    {
        // Konversi nilai string ke numeric
        switch (strtoupper($nilai)) {
            case 'BB':
                return 1;
            case 'MB':
                return 2;
            case 'BSH':
                return 3;
            case 'BSB':
                return 4;
            default:
                return is_numeric($nilai) ? (float) $nilai : 0;
        }
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
        if (!$this->selectedWeek || !$this->selectedTahun || !$this->selectedMonth) {
            return [];
        }

        $nilaiRecord = Nilai::where('anak_id', $this->selectedAnak)->where('tahun', $this->selectedTahun)->first();

        if (!$nilaiRecord) {
            return [];
        }

        $nilaiData = $nilaiRecord->nilai_data ?? [];
        $catatanData = $nilaiRecord->catatan_data ?? [];

        $semesterKey = $this->selectedMonth >= 7 && $this->selectedMonth <= 12 ? 'semester_ganjil' : 'semester_genap';
        $bulanKey = "bulan_{$this->selectedMonth}";
        $mingguKey = "minggu_{$this->selectedWeek}";

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

                $nilai = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] ?? null;
                $catatan = $catatanData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] ?? null;

                if ($nilai) {
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
        if (!$this->selectedMonth || !$this->selectedTahun) {
            return [];
        }

        $nilaiRecord = Nilai::where('anak_id', $this->selectedAnak)->where('tahun', $this->selectedTahun)->first();

        if (!$nilaiRecord) {
            return [];
        }

        $nilaiData = $nilaiRecord->nilai_data ?? [];

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
        if (!$this->selectedSemester || !$this->selectedTahun) {
            return [];
        }

        $nilaiRecord = Nilai::where('anak_id', $this->selectedAnak)->where('tahun', $this->selectedTahun)->first();

        if (!$nilaiRecord) {
            return [];
        }

        $nilaiData = $nilaiRecord->nilai_data ?? [];
        $semesterKey = "semester_{$this->selectedSemester}";

        $bulanSemester = $this->selectedSemester == 'ganjil' ? [7, 8, 9, 10, 11, 12] : [1, 2, 3, 4, 5, 6];

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
                    $bulanName = \Carbon\Carbon::create($this->selectedTahun, $bulan, 1)->format('M');

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
        $chartData = $this->getChartData();

        return view('livewire.perkembangan', [
            'chartData' => $chartData,
            'weekOptions' => $this->getWeekOptions(),
            'monthOptions' => $this->getMonthOptions(),
        ]);
    }
}
