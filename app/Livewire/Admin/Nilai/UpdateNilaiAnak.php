<?php

namespace App\Livewire\Admin\Nilai;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Update Nilai Anak')]
#[Layout('layouts.master')]
class UpdateNilaiAnak extends Component
{
    public function render()
    {
        return view('livewire.admin.nilai.update-nilai-anak');
    }
}
