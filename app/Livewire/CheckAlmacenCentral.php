<?php

namespace App\Livewire;

use App\Models\Sucursal;
use Livewire\Component;
use Ramsey\Uuid\Type\Integer;
use Livewire\Attributes\On; 

class CheckAlmacenCentral extends Component
{
    public int $id_sucursal;
    public bool $almacen_central;
    public string $estado;
    public string $mensaje;

    private $idSucursalCentralBD;

    public function mount()
    {
        $almacenCentral=Sucursal::where('almacen_central',true)
                                       ->get();

        if ($almacenCentral->count() > 0) 
        {
            $this->idSucursalCentralBD = $almacenCentral[0]->id;
            $this->estado = "disabled";
            $this->mensaje = "Ya existe un Almacen Central (Bloqueado!)";

        }else{
            $this->idSucursalCentralBD = 0;
            $this->estado = "";
            $this->mensaje = "";
        }

        $this->almacen_central = false;

    }

    public function comprobarAlmacenCentral()
    {
        $almacenCentral = Sucursal::where('almacen_central', true)
            ->get();

        if ($almacenCentral->count() > 0) {
            $this->idSucursalCentralBD = $almacenCentral[0]->id;
            $this->estado = "disabled";
            $this->mensaje = "Ya existe un Almacen Central (Bloqueado!)";
            $this->almacen_central = false;
        } else {
            $this->idSucursalCentralBD = 0;
            $this->estado = "";
            $this->mensaje = "";
        }

    }

    #[On('cambiar_id_sucursal')]
    public function alterIdSucursal( $id_sucursal_pased)
    {

        $almacenCentral=Sucursal::where('almacen_central',true)
                                       ->get();

        $almacenCentral->count() > 0 ?  $this->idSucursalCentralBD = $almacenCentral[0]->id : $this->idSucursalCentralBD = 0 ;

        if( intval($id_sucursal_pased) == intval($this->idSucursalCentralBD)  )
        {
            $this->almacen_central = true;
            $this->estado = '';

        }else if( $almacenCentral->count() > 0 ){
            $this->almacen_central = false;   
            $this->estado = 'disabled';
        }else{
            $this->almacen_central = false;   
            $this->estado = '';
        }

    }

    public function render()
    {
        return view('livewire.check-almacen-central');
    }
}
