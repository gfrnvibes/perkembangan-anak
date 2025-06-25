<?php

namespace App\Livewire\Admin\Anak;

use App\Models\Anak;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Detail Data Anak')]
#[Layout('layouts.master')]
class DetailAnak extends Component
{
    public $anak;

    // Ganti dari $id menjadi $nama_lengkap
    public function mount($nama_lengkap)
    {
        // Cari berdasarkan nama_lengkap atau ID (untuk backward compatibility)
        $this->anak = Anak::with('orangTua')
                          ->where('nama_lengkap', $nama_lengkap)
                          ->orWhere('id', $nama_lengkap)
                          ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.admin.anak.detail-anak');
    }
}
