<?php

namespace App\Livewire\Admin\Anak;

use App\Models\Anak;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Tambah Anak Baru')]
#[Layout('layouts.master')]

class CreateAnak extends Component
{
    // Form fields
    public $user_id;
    public $nama_lengkap;
    public $nama_panggilan;
    public $nomor_induk;
    public $nisn;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $ayah;
    public $ibu;
    public $alamat_lengkap;
    
    // For dropdown of parents
    public $orangTuaList = [];

    public function mount()
    {
        // Get list of users with 'user' role (parents)
        $this->orangTuaList = User::role('user')->get();
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255|unique:anaks,nomor_induk',
            'nisn' => 'nullable|string|max:255|unique:anaks,nisn',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'ayah' => 'nullable|string|max:255',
            'ibu' => 'nullable|string|max:255',
            'alamat_lengkap' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Orang tua harus dipilih',
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nama_panggilan.required' => 'Nama panggilan harus diisi',
            'nomor_induk.required' => 'Nomor induk harus diisi',
            'nomor_induk.unique' => 'Nomor induk sudah terdaftar',
            'nisn.unique' => 'NISN sudah terdaftar',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'alamat_lengkap.required' => 'Alamat lengkap harus diisi',
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();
        
        try {
            Anak::create($validatedData);
            
            // Reset form fields
            $this->reset([
                'nama_lengkap', 'nama_panggilan', 'nomor_induk', 'nisn', 
                'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 
                'ayah', 'ibu', 'alamat_lengkap'
            ]);
            
            session()->flash('message', 'Data anak berhasil ditambahkan!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.anak.create-anak');
    }
}
