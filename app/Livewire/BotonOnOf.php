<?php

namespace App\Livewire;

use Livewire\Component;

class BotonOnOf extends Component
{
    public $estadoBotonOnOff;

    public function mount()
    {
        $this->estadoBotonOnOff = false;
    }

    public function cambioEstado()
    {
        $this->dispatch('boton-on-of', estadoBotonOnOff : $this->estadoBotonOnOff);
    }

    public function render()
    {
        return view('livewire.boton-on-of');
    }
}
