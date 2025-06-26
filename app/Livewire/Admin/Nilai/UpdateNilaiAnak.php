<?php

namespace App\Livewire\Admin\Nilai;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Update Nilai Anak')]
#[Layout('layouts.master')]
class UpdateNilaiAnak extends Component
{

    // Tidak ada properti atau metode yang diperlukan untuk komponen ini
    // Update Nilai Anak sudah ada di InputNilai.php

    public function render()
    {
        return view('livewire.admin.nilai.update-nilai-anak');
    }
}
