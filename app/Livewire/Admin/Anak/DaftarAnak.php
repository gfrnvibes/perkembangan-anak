<?php

namespace App\Livewire\Admin\Anak;

use App\Models\Anak;
use Livewire\Component;
use App\Exports\ExportAnak;
use App\Imports\AnakImport;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Daftar Anak')]
#[Layout('layouts.master')]
class DaftarAnak extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Tambahkan protected $paginationTheme untuk Bootstrap
    protected $paginationTheme = 'bootstrap';

    public $importExcel;
    public $search = '';
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';
    public $filterJenisKelamin = '';
    public $perPage = 10;

    // Form properties
    public $anakId;
    public $nama_lengkap;
    public $nomor_induk;
    public $nisn;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $ayah;
    public $ibu;
    public $alamat_lengkap;
    public $nama_panggilan;
    public $wali;
    public $user_id;

    // Tambahkan listeners untuk event
    protected $listeners = ['refreshData' => '$refresh'];

    // Tambahkan queryString untuk menjaga state saat pagination
    protected function queryString()
    {
        return [
            'search' => ['except' => ''],
            'filterJenisKelamin' => ['except' => ''],
            'sortField' => ['except' => 'nama_lengkap'],
            'sortDirection' => ['except' => 'asc'],
            'perPage' => ['except' => 10],
        ];
    }

    // Tambahkan method untuk reset pagination saat search atau filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterJenisKelamin()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'nomor_induk' => 'required|string|max:18',
        'nisn' => 'nullable|string|max:10',
        'jenis_kelamin' => 'required|string',
        'tempat_lahir' => 'required|string|max:100',
        'tanggal_lahir' => 'required|string',
        'ayah' => 'required|string|max:255',
        'ibu' => 'required|string|max:255',
        'alamat_lengkap' => 'required|string',
        'nama_panggilan' => 'nullable|string|max:50',
        'user_id' => 'nullable|exists:users,id',
    ];

    public function render()
    {
        $anak = Anak::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_induk', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJenisKelamin, function ($query) {
                $query->where('jenis_kelamin', $this->filterJenisKelamin);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.anak.daftar-anak', [
            'anak' => $anak,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function showAnak($id)
    {
        $this->resetForm();
        $this->anakId = $id;
        $anak = Anak::findOrFail($id);

        $this->nama_lengkap = $anak->nama_lengkap;
        $this->nomor_induk = $anak->nomor_induk;
        $this->nisn = $anak->nisn;
        $this->jenis_kelamin = $anak->jenis_kelamin;
        $this->tempat_lahir = $anak->tempat_lahir;
        $this->tanggal_lahir = $anak->tanggal_lahir;
        $this->ayah = $anak->ayah;
        $this->ibu = $anak->ibu;
        $this->alamat_lengkap = $anak->alamat_lengkap;
        $this->nama_panggilan = $anak->nama_panggilan;
        $this->wali = $anak->wali;

        $this->dispatch('showViewModal');
    }

    public function deleteAnak($id)
    {
        $anak = Anak::findOrFail($id);
        $anak->delete();

        session()->flash('message', 'Data anak berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->anakId = null;
        $this->nama_lengkap = '';
        $this->nomor_induk = '';
        $this->nisn = '';
        $this->jenis_kelamin = '';
        $this->tempat_lahir = '';
        $this->tanggal_lahir = '';
        $this->ayah = '';
        $this->ibu = '';
        $this->alamat_lengkap = '';
        $this->nama_panggilan = '';
        $this->user_id = null;

        $this->resetValidation();
    }

    public function importExcelFile()
    {
        try {
            $this->validate([
                'importExcel' => 'required|file|mimes:xlsx,xls',
            ]);

            $path = $this->importExcel->store('temp');
            $fullPath = storage_path('app/' . $path);

            if (!file_exists($fullPath)) {
                session()->flash('error', 'File tidak ditemukan setelah upload: ' . $fullPath);
                return;
            }

            Log::info('Importing Excel file: ' . $fullPath);

            // Baca file excel manual, lalu proses satu per satu
            $rows = Excel::toArray(new AnakImport(), $fullPath)[0];

            // Lewati baris header, mulai dari baris data
            foreach ($rows as $index => $row) {
                // if ($index === 0) {
                //     continue;
                // }

                $email = $row['email'] ?? null;
                $nama_lengkap = $row['nama_lengkap'] ?? null;

                if (!$email) {
                    session()->flash('error', 'Email tidak ditemukan pada baris ke-' . ($index + 2));
                    return;
                }

                // Buat user jika belum ada
                $user = \App\Models\User::firstOrCreate(['email' => $email], ['name' => $nama_lengkap, 'password' => bcrypt('password')]);

                // Buat anak langsung, pakai user_id yang valid
                Anak::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $row['nama_lengkap'],
                    'nama_panggilan' => $row['nama_panggilan'],
                    'nomor_induk' => $row['nomor_induk'],
                    'nisn' => $row['nisn'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'ayah' => $row['ayah'],
                    'ibu' => $row['ibu'],
                    'wali' => $row['wali'],
                    'phone_number' => $row['phone_number'],
                    'alamat_lengkap' => $row['alamat_lengkap'],
                ]);
            }

            // Setelah user dipastikan ada, lakukan import seperti biasa
            try {
                Excel::import(new AnakImport(), $fullPath);
            } catch (\Exception $e) {
                Log::error('Excel import error: ' . $e->getMessage());
                session()->flash('error', 'Gagal mengimpor file: ' . $e->getMessage());
                return;
            }

            $this->importExcel = null;
            session()->flash('message', 'Data anak dan user berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Import process error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        return Excel::download(new ExportAnak($this->search, $this->filterJenisKelamin, $this->sortField, $this->sortDirection), 'daftar-anak.xlsx');
    }
}
