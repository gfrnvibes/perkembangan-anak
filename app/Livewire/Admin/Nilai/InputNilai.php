<?php

namespace App\Livewire\Admin\Nilai;

use App\Models\Anak;
use App\Models\Aspek;
use App\Models\Indikator;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Input Nilai Anak')]
#[Layout('layouts.master')]

class InputNilai extends Component
{
    public $selectedAnak = '';
    public $selectedMinggu = '';
    public $nilai = [];
    
    public function render()
    {   
        // Get all children data
        $anakList = Anak::all();
        
        // Get all aspects with their indicators
        $aspekList = Aspek::with('indikator')->get();
        
        // Define available weeks
        $mingguList = [
            1 => 'Minggu 1',
            2 => 'Minggu 2',
            3 => 'Minggu 3',
            4 => 'Minggu 4',
        ];
        
        return view('livewire.admin.nilai.input-nilai', [
            'anakList' => $anakList,
            'aspekList' => $aspekList,
            'mingguList' => $mingguList,
        ]);
    }
    
    public function updatedSelectedAnak()
    {
        // Reset nilai when child selection changes
        $this->nilai = [];
    }
    
    public function updatedSelectedMinggu()
    {
        // Reset nilai when week selection changes
        $this->nilai = [];
    }
}
