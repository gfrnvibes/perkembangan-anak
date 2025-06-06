<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Perkembangan')]

class Perkembangan extends Component
{
    public function chartData()
    {
        $data = [
            'labels' => ['Jan', 'Feb', 'Mar'],
            'values' => [10, 20, 30],
        ];
        return response()->json($data);
    }

    public function render()
    {
        return view('livewire.perkembangan',[
                'chartData' => [
                'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                'values' => [5, 10, 8, 6, 12]
            ]
        ]);
    }
}
