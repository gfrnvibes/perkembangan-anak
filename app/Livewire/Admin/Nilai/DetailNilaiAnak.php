<?php

namespace App\Livewire\Admin\Nilai;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Detail Nilai Anak')]
#[Layout('layouts.master')]
class DetailNilaiAnak extends Component
{
    public function render()
    {
        return view('livewire.admin.nilai.detail-nilai-anak');
    }
}
