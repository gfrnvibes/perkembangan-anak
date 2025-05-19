<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Perkembangan Anak RA Al-Amin')]

class Home extends Component
{
    public function render()
    {
        return view('livewire.home');
    }
}
