<?php

namespace App\Livewire\Admin\Anak;

use App\Models\Anak;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Edit Data Anak')]
#[Layout('layouts.master')]
class UpdateAnak extends Component
{
    use WithFileUploads;

    public $anakId;
    public $anak;

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
    public $wali;
    public $phone_number;
    public $alamat_lengkap;
    public $pas_foto;
    public $existing_foto;

    public function mount($nama_lengkap)
    {
        // Find anak by ID or nama_lengkap
        $this->anak = Anak::where('id', $nama_lengkap)->orWhere('nama_lengkap', $nama_lengkap)->firstOrFail();

        $this->anakId = $this->anak->id;

        // Populate form fields
        $this->email = $this->anak->orangTua?->email ?? '';
        $this->user_id = $this->anak->user_id;
        $this->nama_lengkap = $this->anak->nama_lengkap;
        $this->nama_panggilan = $this->anak->nama_panggilan;
        $this->nomor_induk = $this->anak->nomor_induk;
        $this->nisn = $this->anak->nisn;
        $this->jenis_kelamin = $this->anak->jenis_kelamin;
        $this->tempat_lahir = $this->anak->tempat_lahir;
        $this->tanggal_lahir = $this->anak->tanggal_lahir;
        $this->ayah = $this->anak->ayah;
        $this->ibu = $this->anak->ibu;
        $this->wali = $this->anak->wali;
        $this->phone_number = $this->anak->phone_number;
        $this->alamat_lengkap = $this->anak->alamat_lengkap;
        $this->existing_foto = $this->anak->pas_foto;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255|unique:anaks,nomor_induk,' . $this->anakId,
            'nisn' => 'nullable|string|max:255|unique:anaks,nisn,' . $this->anakId,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'ayah' => 'nullable|string|max:255',
            'ibu' => 'nullable|string|max:255',
            'alamat_lengkap' => 'required|string',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'wali' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email orang tua harus diisi',
            'email.email' => 'Format email tidak valid',
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
            'phone_number' => 'nullable|string|max:15',
        ];
    }

    public function update()
    {
        $validatedData = $this->validate();

        try {
            // Cari atau buat user berdasarkan email
            $user = User::where('email', $this->email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $this->nama_lengkap,
                    'email' => $this->email,
                    'password' => bcrypt('password'),
                ]);
                $user->assignRole('user');
            } else {
                // Update nama user jika berbeda
                $user->update(['name' => $this->nama_lengkap]);
            }

            $validatedData['user_id'] = $user->id;
            unset($validatedData['email']);

            // Handle foto upload
            if ($this->pas_foto) {
                // Hapus foto lama jika ada
                if ($this->existing_foto) {
                    Storage::disk('public')->delete($this->existing_foto);
                }
                $validatedData['pas_foto'] = $this->pas_foto->store('pas_foto_anak', 'public');
            } else {
                // Pertahankan foto yang sudah ada
                $validatedData['pas_foto'] = $this->existing_foto;
            }

            // Update data anak
            $this->anak->update($validatedData);

            session()->flash('message', 'Data anak berhasil diperbarui!');

            // Redirect ke halaman daftar anak
            return redirect()->route('daftar-anak');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.anak.update-anak');
    }
}
