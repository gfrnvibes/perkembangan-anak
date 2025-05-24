<?php

namespace App\Livewire\Admin\Anak;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Detail Anak')]
#[Layout('layouts.master')]
class DetailAnak extends Component
{
    public function render()
    {
        return view('livewire.admin.anak.detail-anak');
    }
}
