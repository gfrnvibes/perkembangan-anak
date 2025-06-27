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
    public $email;
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
    public $pas_foto;
    public $wali;
    public $phone_number;

    // For dropdown of parents
    // public $orangTuaList = [];

    public function mount()
    {
        // Get list of users with 'user' role (parents)
        // $this->orangTuaList = User::role('user')->get();
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'nomor_induk' => 'required|string|digits:17|unique:anaks,nomor_induk',
            'nisn' => 'nullable|string|digits:10|unique:anaks,nisn',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'ayah' => 'nullable|string|max:255',
            'ibu' => 'nullable|string|max:255',
            'wali' => 'nullable|string|max:255', 
            'alamat_lengkap' => 'required|string',
            'phone_number' => 'nullable|string|max:15', 
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email orang tua harus diisi',
            'email.exists' => 'Email orang tua tidak ditemukan',
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nama_panggilan.required' => 'Nama panggilan harus diisi',
            'nomor_induk.required' => 'Nomor induk harus diisi',
            'nomor_induk.unique' => 'Nomor induk sudah terdaftar',
            'nisn.unique' => 'NISN sudah terdaftar',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'alamat_lengkap.required' => 'Alamat lengkap harus diisi',
            'pas_foto.image' => 'File harus berupa gambar',
            'pas_foto.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'pas_foto.max' => 'Ukuran gambar maksimal 2MB',
            'wali.max' => 'Nama wali maksimal 255 karakter',
            'phone_number.max' => 'Nomor telepon maksimal 15 karakter',
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();

        // Cari user berdasarkan email, jika tidak ada buat user baru
        $user = User::where('email', $this->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $this->nama_lengkap,
                'email' => $this->email,
                'password' => bcrypt('password'), // Password default, sebaiknya ganti atau kirim email reset
            ]);
            // Jika pakai spatie/laravel-permission, tambahkan role user:
            $user->assignRole('user');
        }
        $validatedData['user_id'] = $user->id;
        unset($validatedData['email']);

        // Simpan foto jika ada
        if ($this->pas_foto) {
            $validatedData['pas_foto'] = $this->pas_foto->store('pas_foto_anak', 'public');
        }

        try {
            Anak::create($validatedData);

            // Reset form fields
            $this->reset(['email', 'nama_panggilan', 'nomor_induk', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'ayah', 'ibu', 'alamat_lengkap']);

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
