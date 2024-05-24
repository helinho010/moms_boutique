<?php

namespace App\Livewire;

use App\Models\Sucursal;
use Livewire\Component;

class CheckAlmacenCentral extends Component
{
    public $almacen_central;
    public $estado;
    public $mensaje;

    public function mount()
    {
        $almacenCentral=Sucursal::where('almacen_central',true)
                                       ->get();

        $this->almacen_central = false;

        $this->estado = $almacenCentral->count() > 0 ? "disabled" : "";

        $this->mensaje = $almacenCentral->count() > 0 ? "Ya existe un Almacen Central (Bloqueado!)" : "";
    }

    public function comprobarAlmacenCentral()
    {
        $almacenCentral=Sucursal::where('almacen_central',true)
                                       ->get();

        $this->almacen_central = false;

        $this->estado = $almacenCentral->count() > 0 ? "disabled" : "";

        $this->mensaje = $almacenCentral->count() > 0 ? "Ya existe un Almacen Central (Bloqueado!)" : "";
    }

    public function render()
    {
        return view('livewire.check-almacen-central');
    }
}
