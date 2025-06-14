<?php

namespace App\Livewire;

use App\Models\Anak;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProfilAnanda extends Component
{
    public $selectedAnakId;
    public $anak;
    public $anakList;

    public function mount()
    {
        // Ambil semua anak dari user yang login
        $this->anakList = Auth::user()->anak;
        
        // Jika ada anak, pilih yang pertama sebagai default
        if ($this->anakList->count() > 0) {
            $this->selectedAnakId = $this->anakList->first()->id;
            $this->loadAnak();
        }
    }

    public function updatedSelectedAnakId()
    {
        $this->loadAnak();
    }

    public function loadAnak()
    {
        if ($this->selectedAnakId) {
            $this->anak = Anak::with('nilais')
                ->where('id', $this->selectedAnakId)
                ->where('user_id', Auth::id())
                ->first();
        } else {
            $this->anak = null;
        }
    }

    public function render()
    {
        return view('livewire.profil-ananda');
    }
}
