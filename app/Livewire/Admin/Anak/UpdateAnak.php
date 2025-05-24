<?php

namespace App\Livewire\Admin\Anak;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Edit Data Anak')]
#[Layout('layouts.master')]
class UpdateAnak extends Component
{
    public function render()
    {
        return view('livewire.admin.anak.update-anak');
    }
}
