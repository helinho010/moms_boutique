<?php

namespace App\Livewire\Formulario;

use Livewire\Component;
use Illuminate\Support\Str;

class InputSelect extends Component
{
    public string $placeholder;
    public string $textoValue;
    public string $nombreInput;
    public string $identificadorInput;
    public $modelo;
    public string $oculto;
    public $items;

    public function mount()
    {
        $this->placeholder = "Introduzca un texto relacionado a un $this->textoValue" ;
        $this->textoValue = "";
        $this->oculto = "hidden";
        $this->items = collect();
    }

    public function inputSeleccionado()
    {
        
    }

    public function itemSeleccionado()
    {
        $this->textoValue = "***";
        $this->oculto = "hidden";
    }

    public function focoinput()
    {
        if ( Str::of($this->textoValue)->isNotEmpty() ) 
        {
            $this->oculto = '';
            $this->dispatch('selectText', identificadorInput:$this->identificadorInput);
        } 
    }

    public function render()
    {
        if( $this->textoValue != "")
        {
            $this->items = $this->modelo::filterProductos($this->textoValue);         
        }else{
            $this->items = $this->modelo::filterProductos("");
        }
        return view('livewire.formulario.input-select');
    }
}
