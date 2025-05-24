<?php

namespace App\Livewire\Admin\Nilai;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Daftar Nilai')]
#[Layout('layouts.master')]

class DaftarNilai extends Component
{
    public function render()
    {
        return view('livewire.admin.nilai.daftar-nilai');
    }
}
