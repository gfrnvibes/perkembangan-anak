<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Title('Login')]
class Login extends Component
{   
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    public function login()
    {
        $this->validate();
    
        // Cek apakah email ada di database
        $user = \App\Models\User::where('email', $this->email)->first();
        
        if (!$user) {
            session()->flash('error', 'Email tidak terdaftar');
            return;
        }
        
        // Jika email ada, cek password
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();
            
            if ($user->hasRole('admin')) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('/');
            }
        } else {
            // Jika email benar tapi password salah
            session()->flash('error', 'Password yang Anda masukkan salah');
        }
    }
    

    public function render()
    {
        return view('livewire.auth.login');
    }
}
