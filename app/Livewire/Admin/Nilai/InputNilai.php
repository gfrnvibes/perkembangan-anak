<?php

namespace App\Livewire\Admin\Nilai;

use App\Models\Anak;
use App\Models\Aspek;
use App\Models\Indikator;
use App\Models\Nilai;
use App\Models\TemplateCatatan;
use App\Models\CatatanAnak;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Input Nilai Anak')]
#[Layout('layouts.master')]

class InputNilai extends Component
{
    public $selectedAnak;
    public $selectedMinggu = 1;
    public $selectedBulan;
    public $selectedTahun;
    public $nilai = [];
    public $catatan = [];
    public $anakList = [];
    public $aspekList = [];
    
    // Mapping nilai
    public $nilaiMapping = [
        1 => 'BB (Belum Berkembang)',
        2 => 'MB (Mulai Berkembang)', 
        3 => 'BSH (Berkembang Sesuai Harapan)',
        4 => 'BSB (Berkembang Sangat Baik)'
    ];

    public function mount()
    {
        $this->anakList = Anak::all();
        $this->aspekList = Aspek::with('indikators')->get();
        $this->selectedBulan = date('n');
        $this->selectedTahun = date('Y');
        
        // Initialize nilai dan catatan array
        foreach ($this->aspekList as $aspek) {
            foreach ($aspek->indikators as $indikator) {
                $this->nilai[$indikator->id] = '';
                $this->catatan[$indikator->id] = '';
            }
        }
    }

    public function updatedSelectedAnak()
    {
        $this->resetForm();
        $this->loadExistingData();
    }

    public function updatedSelectedMinggu()
    {
        if ($this->selectedAnak) {
            $this->loadExistingData();
        }
    }

    public function updatedSelectedBulan()
    {
        if ($this->selectedAnak) {
            $this->loadExistingData();
        }
    }

    public function updatedSelectedTahun()
    {
        if ($this->selectedAnak) {
            $this->loadExistingData();
        }
    }

    public function resetForm()
    {
        foreach ($this->aspekList as $aspek) {
            foreach ($aspek->indikators as $indikator) {
                $this->nilai[$indikator->id] = '';
                $this->catatan[$indikator->id] = '';
            }
        }
    }

    public function loadExistingData()
    {
        if (!$this->selectedAnak || !$this->selectedMinggu || !$this->selectedBulan || !$this->selectedTahun) {
            return;
        }

        $existingNilai = Nilai::with('catatanAnak')
            ->where('anak_id', $this->selectedAnak)
            ->where('minggu', $this->selectedMinggu)
            ->where('bulan', $this->selectedBulan)
            ->where('tahun', $this->selectedTahun)
            ->get()
            ->keyBy('indikator_id');

        foreach ($this->aspekList as $aspek) {
            foreach ($aspek->indikators as $indikator) {
                if (isset($existingNilai[$indikator->id])) {
                    $nilai = $existingNilai[$indikator->id];
                    $this->nilai[$indikator->id] = $nilai->nilai_numerik;
                    
                    // Load catatan jika ada
                    if ($nilai->catatanAnak) {
                        $this->catatan[$indikator->id] = $nilai->catatanAnak->isi_catatan;
                    }
                } else {
                    $this->nilai[$indikator->id] = '';
                    $this->catatan[$indikator->id] = '';
                }
            }
        }
    }

    public function updatedNilai($value, $indikatorId)
    {
        // Auto-generate catatan dari template saat nilai berubah
        if (!empty($value) && empty($this->catatan[$indikatorId])) {
            $template = $this->getTemplateCatatan($indikatorId, $value);
            if ($template) {
                $this->catatan[$indikatorId] = $template->isi_template;
            }
        }
    }

    private function getTemplateCatatan($indikatorId, $nilaiNumerik)
    {
        $nilaiMapping = [1 => 'BB', 2 => 'MB', 3 => 'BSH', 4 => 'BSB'];
        $nilaiKode = $nilaiMapping[$nilaiNumerik] ?? null;
        
        if (!$nilaiKode) return null;
        
        return TemplateCatatan::where('indikator_id', $indikatorId)
            ->where('nilai', $nilaiKode)
            ->first();
    }

    public function simpanNilai()
    {
        // Validasi
        $this->validate([
            'selectedAnak' => 'required',
            'selectedMinggu' => 'required|integer|min:1|max:4',
            'selectedBulan' => 'required|integer|min:1|max:12',
            'selectedTahun' => 'required|integer',
            'nilai.*' => 'required|integer|min:1|max:4',
            'catatan.*' => 'required|string|min:10'
        ], [
            'selectedAnak.required' => 'Pilih anak terlebih dahulu',
            'selectedMinggu.required' => 'Pilih minggu',
            'selectedBulan.required' => 'Pilih bulan',
            'selectedTahun.required' => 'Pilih tahun',
            'nilai.*.required' => 'Semua nilai harus diisi',
            'nilai.*.integer' => 'Nilai harus berupa angka',
            'nilai.*.min' => 'Nilai minimal 1',
            'nilai.*.max' => 'Nilai maksimal 4',
            'catatan.*.required' => 'Semua catatan harus diisi',
            'catatan.*.min' => 'Catatan minimal 10 karakter'
        ]);

        try {
            // Hapus nilai existing untuk anak, minggu, bulan, tahun yang sama
            $existingNilai = Nilai::where('anak_id', $this->selectedAnak)
                ->where('minggu', $this->selectedMinggu)
                ->where('bulan', $this->selectedBulan)
                ->where('tahun', $this->selectedTahun)
                ->get();

            // Hapus catatan terkait
            foreach ($existingNilai as $nilai) {
                if ($nilai->catatanAnak) {
                    $nilai->catatanAnak->delete();
                }
            }

            // Hapus nilai
            Nilai::where('anak_id', $this->selectedAnak)
                ->where('minggu', $this->selectedMinggu)
                ->where('bulan', $this->selectedBulan)
                ->where('tahun', $this->selectedTahun)
                ->delete();

            // Simpan nilai dan catatan baru
            foreach ($this->nilai as $indikatorId => $nilaiNumerik) {
                if (!empty($nilaiNumerik) && !empty($this->catatan[$indikatorId])) {
                    // Simpan nilai
                    $nilai = Nilai::create([
                        'anak_id' => $this->selectedAnak,
                        'indikator_id' => $indikatorId,
                        'nilai_numerik' => $nilaiNumerik,
                        'minggu' => $this->selectedMinggu,
                        'bulan' => $this->selectedBulan,
                        'tahun' => $this->selectedTahun
                    ]);

                    // Cek apakah catatan dari template atau custom
                    $template = $this->getTemplateCatatan($indikatorId, $nilaiNumerik);
                    $isCustom = !$template || $this->catatan[$indikatorId] !== $template->isi_template;

                    // Simpan catatan
                    CatatanAnak::create([
                        'nilai_id' => $nilai->id,
                        'template_catatan_id' => $isCustom ? null : $template->id,
                        'isi_catatan' => $this->catatan[$indikatorId],
                        'is_custom' => $isCustom
                    ]);
                }
            }

            session()->flash('success', 'Nilai dan catatan berhasil disimpan!');
            
            // Reset form
            $this->reset(['selectedAnak', 'nilai', 'catatan']);
            $this->selectedMinggu = 1;
            $this->selectedBulan = date('n');
            $this->selectedTahun = date('Y');
            
            // Reinitialize arrays
            foreach ($this->aspekList as $aspek) {
                foreach ($aspek->indikators as $indikator) {
                    $this->nilai[$indikator->id] = '';
                    $this->catatan[$indikator->id] = '';
                }
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    public function render()
    {    
        return view('livewire.admin.nilai.input-nilai');
    }
}
