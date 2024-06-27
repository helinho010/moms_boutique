<?php

namespace App\Livewire;

use Livewire\Component;

class BotonInvintPdf extends Component
{

    public function exportarInvIntPdf()
    {
        dd("Prueba de boton");
    }

    public function render()
    {
        return view('livewire.boton-invint-pdf');
    }
}
