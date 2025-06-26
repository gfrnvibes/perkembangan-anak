<?php

namespace App\Livewire\Admin;

use App\Models\Anak;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Daftar Orang Tua')]
#[Layout('layouts.master')]
class OrangTua extends Component
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

        return view('livewire.admin.orang-tua', [
            'anak' => $anak
        ]);
    }
}
