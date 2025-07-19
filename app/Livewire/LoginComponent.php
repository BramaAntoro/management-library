<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginComponent extends Component
{
    public $email, $password;

    public function render()
    {
        return view('livewire.login-component')->layout('components.layouts.login');
    }

    public function authenticate()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            return redirect()->route('home');
        }


        $this->addError('email', 'Email atau password salah');

    }

    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home');
    }
}
