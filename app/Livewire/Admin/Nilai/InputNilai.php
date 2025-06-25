<?php

namespace App\Livewire\Admin\Nilai;

use App\Models\Anak;
use App\Models\Aspek;
use App\Models\Nilai;
use Livewire\Component;
use App\Models\Indikator;
use App\Imports\ImportNilai;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\TemplateCatatan;
use Livewire\Attributes\Layout;

#[Title('Input Nilai Anak')]
#[Layout('layouts.master')]
class InputNilai extends Component
{
    use WithFileUploads;

    public $importFile;
    public $selectedAnak;
    public $selectedMinggu = 1;
    public $selectedBulan;
    public $selectedSemester;
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
        4 => 'BSB (Berkembang Sangat Baik)',
    ];

    public function mount()
    {
        $this->anakList = Anak::all();
        // Pastikan indikators ter-load dengan eager loading
        $this->aspekList = Aspek::with([
            'indikators' => function ($query) {
                $query->orderBy('id');
            },
        ])->get();

        $this->selectedBulan = date('n');
        $this->selectedTahun = date('Y');
        $this->selectedSemester = $this->selectedBulan >= 1 && $this->selectedBulan <= 6 ? 'genap' : 'ganjil';

        // Initialize nilai dan catatan array
        $this->initializeArrays();
    }

    private function initializeArrays()
    {
        // Perbaikan: cek array kosong, bukan Collection
        if (empty($this->aspekList) || count($this->aspekList) === 0) {
            session()->flash('error', 'Tidak ada data aspek. Pastikan data aspek dan indikator sudah ada.');
            return;
        }

        foreach ($this->aspekList as $aspek) {
            // Pastikan indikators ada dan tidak kosong
            if (!$aspek->indikators || $aspek->indikators->isEmpty()) {
                continue; // Skip aspek yang tidak punya indikator
            }

            foreach ($aspek->indikators as $indikator) {
                $key = "aspek_{$aspek->id}_indikator_{$indikator->id}";
                $this->nilai[$key] = '';
                $this->catatan[$key] = '';
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
        $this->selectedSemester = $this->selectedBulan >= 1 && $this->selectedBulan <= 6 ? 'genap' : 'ganjil';
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
                $key = "aspek_{$aspek->id}_indikator_{$indikator->id}";
                $this->nilai[$key] = '';
                $this->catatan[$key] = '';
            }
        }
    }

    public function loadExistingData()
    {
        if (!$this->selectedAnak || !$this->selectedMinggu || !$this->selectedBulan || !$this->selectedTahun) {
            return;
        }

        // Ambil data nilai untuk anak dan tahun tertentu
        $nilaiRecord = Nilai::where('anak_id', $this->selectedAnak)->where('tahun', $this->selectedTahun)->first();

        if (!$nilaiRecord) {
            $this->resetForm();
            return;
        }

        $nilaiData = $nilaiRecord->nilai_data ?? [];
        $catatanData = $nilaiRecord->catatan_data ?? [];
        $semesterKey = "semester_{$this->selectedSemester}";
        $bulanKey = "bulan_{$this->selectedBulan}";
        $mingguKey = "minggu_{$this->selectedMinggu}";

        foreach ($this->aspekList as $aspek) {
            foreach ($aspek->indikators as $indikator) {
                $key = "aspek_{$aspek->id}_indikator_{$indikator->id}";
                $aspekKey = "aspek_{$aspek->id}";
                $indikatorKey = "indikator_{$indikator->id}";

                // Load nilai
                $this->nilai[$key] = $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] ?? '';

                // Load catatan
                $this->catatan[$key] = $catatanData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] ?? '';
            }
        }
    }

    public function updatedNilai($value, $key)
    {
        // Auto-generate catatan dari template saat nilai berubah
        if (!empty($value) && empty($this->catatan[$key])) {
            // Extract aspek_id dan indikator_id dari key
            preg_match('/aspek_(\d+)_indikator_(\d+)/', $key, $matches);
            if (count($matches) >= 3) {
                $indikatorId = $matches[2];
                $template = $this->getTemplateCatatan($indikatorId, $value);
                if ($template) {
                    $this->catatan[$key] = $template->isi_template;
                }
            }
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

    public function simpanNilai()
    {
        // Validasi
        $this->validate(
            [
                'selectedAnak' => 'required',
                'selectedMinggu' => 'required|integer|min:1|max:4',
                'selectedBulan' => 'required|integer|min:1|max:12',
                'selectedTahun' => 'required|integer',
                'nilai.*' => 'required|integer|min:1|max:4',
                'catatan.*' => 'required|string|min:10',
            ],
            [
                'selectedAnak.required' => 'Pilih anak terlebih dahulu',
                'selectedMinggu.required' => 'Pilih minggu',
                'selectedBulan.required' => 'Pilih bulan',
                'selectedTahun.required' => 'Pilih tahun',
                'nilai.*.required' => 'Semua nilai harus diisi',
                'nilai.*.integer' => 'Nilai harus berupa angka',
                'nilai.*.min' => 'Nilai minimal 1',
                'nilai.*.max' => 'Nilai maksimal 4',
                'catatan.*.required' => 'Semua catatan harus diisi',
                'catatan.*.min' => 'Catatan minimal 10 karakter',
            ],
        );

        try {
            // Ambil atau buat record nilai untuk anak dan tahun ini
            $nilaiRecord = Nilai::firstOrCreate(
                [
                    'anak_id' => $this->selectedAnak,
                    'tahun' => $this->selectedTahun,
                ],
                [
                    'nilai_data' => [],
                    'catatan_data' => [],
                ],
            );

            $nilaiData = $nilaiRecord->nilai_data ?? [];
            $catatanData = $nilaiRecord->catatan_data ?? [];
            $semesterKey = "semester_{$this->selectedSemester}";
            $bulanKey = "bulan_{$this->selectedBulan}";
            $mingguKey = "minggu_{$this->selectedMinggu}";

            // Update nilai dan catatan untuk minggu yang dipilih
            foreach ($this->nilai as $key => $nilaiValue) {
                if (!empty($nilaiValue) && !empty($this->catatan[$key])) {
                    // Extract aspek_id dan indikator_id dari key
                    preg_match('/aspek_(\d+)_indikator_(\d+)/', $key, $matches);
                    if (count($matches) >= 3) {
                        $aspekId = $matches[1];
                        $indikatorId = $matches[2];
                        $aspekKey = "aspek_{$aspekId}";
                        $indikatorKey = "indikator_{$indikatorId}";

                        // Set nilai
                        $nilaiData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] = (int) $nilaiValue;

                        // Set catatan
                        $catatanData[$semesterKey][$aspekKey][$indikatorKey][$bulanKey][$mingguKey] = $this->catatan[$key];
                    }
                }
            }

            // Update record
            $nilaiRecord->update([
                'nilai_data' => $nilaiData,
                'catatan_data' => $catatanData,
            ]);

            session()->flash('success', 'Nilai dan catatan berhasil disimpan!');

            // Reset form
            $this->reset(['selectedAnak', 'nilai', 'catatan']);
            $this->selectedMinggu = 1;
            $this->selectedBulan = date('n');
            $this->selectedTahun = date('Y');
            $this->selectedSemester = $this->selectedBulan >= 1 && $this->selectedBulan <= 6 ? 'genap' : 'ganjil';

            // Reinitialize arrays
            $this->initializeArrays();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    public function importFromExcel()
    {
        $this->validate(
            [
                'importFile' => 'required|mimes:xlsx,xls,csv|max:2048',
                'selectedMinggu' => 'required',
                'selectedBulan' => 'required',
                'selectedTahun' => 'required',
            ],
            [
                'importFile.required' => 'File Excel harus dipilih',
                'importFile.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV',
                'importFile.max' => 'Ukuran file maksimal 2MB',
            ],
        );

        try {
            Excel::import(new ImportNilai($this->selectedMinggu, $this->selectedBulan, $this->selectedTahun, $this->selectedSemester), $this->importFile->getRealPath());

            session()->flash('success', 'Data nilai berhasil diimport dari Excel!');
            $this->reset('importFile');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $indikators = Indikator::with('aspek')->get();
        $anakList = Anak::all();

        $headers = ['nomor_induk', 'nama_anak'];

        // Tambahkan kolom untuk setiap indikator
        foreach ($indikators as $indikator) {
            $headers[] = 'indikator_' . $indikator->id;
            $headers[] = 'catatan_indikator_' . $indikator->id;
        }

        $data = [];
        foreach ($anakList as $anak) {
            $row = [
                'nomor_induk' => $anak->nomor_induk,
                'nama_anak' => $anak->nama_lengkap,
            ];

            // Tambahkan kolom kosong untuk setiap indikator
            foreach ($indikators as $indikator) {
                $row['indikator_' . $indikator->id] = '';
                $row['catatan_indikator_' . $indikator->id] = '';
            }

            $data[] = $row;
        }

        return Excel::download(
            new class ($headers, $data) implements \Maatwebsite\Excel\Concerns\FromArray {
                protected $headers;
                protected $data;

                public function __construct($headers, $data)
                {
                    $this->headers = $headers;
                    $this->data = $data;
                }

                public function array(): array
                {
                    return array_merge([$this->headers], $this->data);
                }
            },
            'template_import_nilai.xlsx',
        );
    }

    public function render()
    {
        return view('livewire.admin.nilai.input-nilai');
    }
}
