<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Perkembangan Ananda')]
class Perkembangan extends Component
{
    public function render()
    {
        return view('livewire.perkembangan');
    }
}
