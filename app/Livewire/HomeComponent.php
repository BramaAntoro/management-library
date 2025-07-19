<?php

namespace App\Livewire;

use Livewire\Component;

class HomeComponent extends Component
{
    public function render()
    {
        $title['title'] = 'Home library';
        return view('livewire.home-component')->layoutData($title);
    }
}
