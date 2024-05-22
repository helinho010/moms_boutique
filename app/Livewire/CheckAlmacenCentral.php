<?php

namespace App\Livewire;

use App\Models\Sucursal;
use Livewire\Component;

class CheckAlmacenCentral extends Component
{
    public $almacen_central;
    public $estado;

    public function mount()
    {
        $this->almacen_central="";
        $this->estado="";
    }

    public function actulizarCheckAlmacenCentral()
    {
        $existeAlmacenCentral = Sucursal::where('almacen_central',true)
                                        ->get();
        
        $this->estado = $existeAlmacenCentral->count() > 0 ? "disabled" : "";
        
        // $this->almacen_central = $existeAlmacenCentral[0]->almacen_central;  
    }

    public function render()
    {
        return view('livewire.check-almacen-central');
    }
}
