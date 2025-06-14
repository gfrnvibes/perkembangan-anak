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
    
    public $anakList = [];
    public $aspekList = [];
    
    public function mount()
    {
        $this->selectedTahun = date('Y');
        
        // Ambil anak dari user yang sedang login
        $this->anakList = Anak::where('user_id', Auth::id())->get()->toArray();
        
        // Auto select anak pertama jika ada
        if (count($this->anakList) > 0) {
            $this->selectedAnak = $this->anakList[0]['id'];
        }
        
        $this->aspekList = Aspek::orderBy('id')->get()->toArray();
    }

    public function updatedSelectedAspek()
    {
        $this->dispatch('chart-update');
    }
    
    public function updatedSelectedSemester()
    {
        $this->dispatch('chart-update');
    }
    
    public function updatedSelectedBulan()
    {
        $this->dispatch('chart-update');
    }
    
    public function updatedSelectedTahun()
    {
        $this->dispatch('chart-update');
    }
    
    public function updatedSelectedAnak()
    {
        $this->dispatch('chart-update');
    }
    
    public function getChartData()
    {
        if (!$this->selectedAnak) {
            return [
                'labels' => [],
                'datasets' => []
            ];
        }
        
        $nilai = Nilai::where('anak_id', $this->selectedAnak)
            ->where('tahun', $this->selectedTahun)
            ->first();
            
        if (!$nilai || !$nilai->nilai_data) {
            return [
                'labels' => [],
                'datasets' => []
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
        $bulanRange = $this->selectedSemester == 'ganjil' 
            ? [7, 8, 9, 10, 11, 12] 
            : [1, 2, 3, 4, 5, 6];
            
        // Jika ada filter bulan spesifik
        if ($this->selectedBulan) {
            $bulanRange = [(int)$this->selectedBulan];
        }
        
        foreach ($indikators as $indikator) {
            $labels[] = $indikator->nama_indikator;
            
            $totalNilai = 0;
            $countNilai = 0;
            
            foreach ($bulanRange as $bulan) {
                $nilaiData = is_array($nilai->nilai_data) ? $nilai->nilai_data : [];
                $semesterKey = "semester_{$this->selectedSemester}";
                $aspekKey = "aspek_{$this->selectedAspek}";
                $indikatorKey = "indikator_{$indikator->id}";
                $bulanKey = "bulan_{$bulan}";
                
                $bulanData = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey] ?? [];
                
                if (is_array($bulanData) && !empty($bulanData)) {
                    foreach ($bulanData as $mingguData) {
                        if (is_numeric($mingguData)) {
                            // Konversi nilai string ke numeric
                            $numericValue = $this->convertNilaiToNumeric($mingguData);
                            if ($numericValue > 0) {
                                $totalNilai += $numericValue;
                                $countNilai++;
                            }
                        }
                    }
                }
            }
            
            $data[] = $countNilai > 0 ? round($totalNilai / $countNilai, 1) : 0;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $aspek->nama_aspek,
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }
    
    private function getAllAspekChart($nilai)
    {
        $aspeks = Aspek::orderBy('id')->get();
        $labels = [];
        $data = [];
        
        // Tentukan bulan berdasarkan semester
        $bulanRange = $this->selectedSemester == 'ganjil' 
            ? [7, 8, 9, 10, 11, 12] 
            : [1, 2, 3, 4, 5, 6];
            
        // Jika ada filter bulan spesifik
        if ($this->selectedBulan) {
            $bulanRange = [(int)$this->selectedBulan];
        }
        
        foreach ($aspeks as $aspek) {
            $labels[] = $aspek->nama_aspek;
            $indikators = Indikator::where('aspek_id', $aspek->id)->get();
            
            $totalAspek = 0;
            $countAspek = 0;
            
            foreach ($indikators as $indikator) {
                $totalIndikator = 0;
                $countIndikator = 0;
                
                foreach ($bulanRange as $bulan) {
                    $nilaiData = is_array($nilai->nilai_data) ? $nilai->nilai_data : [];
                    $semesterKey = "semester_{$this->selectedSemester}";
                    $aspekKey = "aspek_{$aspek->id}";
                    $indikatorKey = "indikator_{$indikator->id}";
                    $bulanKey = "bulan_{$bulan}";
                    
                    $bulanData = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey] ?? [];
                    
                    if (is_array($bulanData) && !empty($bulanData)) {
                        foreach ($bulanData as $mingguData) {
                            if (is_numeric($mingguData)) {
                                $numericValue = $this->convertNilaiToNumeric($mingguData);
                                if ($numericValue > 0) {
                                    $totalIndikator += $numericValue;
                                    $countIndikator++;
                                }
                            }
                        }
                    }
                }
                
                if ($countIndikator > 0) {
                    $totalAspek += $totalIndikator / $countIndikator;
                    $countAspek++;
                }
            }
            
            $data[] = $countAspek > 0 ? round($totalAspek / $countAspek, 1) : 0;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Rata-rata Perkembangan',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)'
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    'borderWidth' => 1
                ]
            ]
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
                return is_numeric($nilai) ? (float)$nilai : 0;
        }
    }
    
    public function render()
    {
        $chartData = $this->getChartData();
        
        return view('livewire.perkembangan', [
            'chartData' => $chartData
        ]);
    }
}
