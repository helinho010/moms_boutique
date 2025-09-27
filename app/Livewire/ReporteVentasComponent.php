<?php

namespace App\Livewire;

use App\Models\Evento;
use App\Models\Sucursal;
use App\Models\UserSucursal;
use App\Models\UsuarioEvento;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Exports\VentaReporteExcelExport;
use Maatwebsite\Excel\Facades\Excel;


class ReporteVentasComponent extends Component
{
    public $sucursales;
    public $eventos;
    public $eventosOSucursales;

    public $titleLabel;
    public $idSelector;
    public $fechaInicial;
    public $fechaFinal;
    
    public function mount()
    {
        $this->sucursalesEventosUsuario(auth()->user()->usertype_id, auth()->user()->id);
        $this->eventosOSucursales = $this->sucursales;
        $this->titleLabel = "Sucursal";
        $this->idSelector = "seleccionado";
        $this->fechaFinal = $this->fechaInicial = date("Y-m-d");
    }

    public function sucursalesEventosUsuario($tipoUsuario = 1, $idUsuario = 1)
    {
        if ( $tipoUsuario == 1 ) 
        {
            $this->sucursales = Sucursal::selectRaw(' 
                                                    sucursals.id as id,
                                                    sucursals.id as id_sucursal_user_sucursal,
                                                    sucursals.razon_social as nombre,
                                                    sucursals.direccion as direccion,
                                                    sucursals.ciudad as ciudad,
                                                    sucursals.activo as estado')
                                    ->where('sucursals.activo',1)
                                    ->get();

            $this->eventos = Evento::selectRaw('
                                                        eventos.id as id,
                                                        eventos.nombre as nombre,
                                                        eventos.fecha_evento as fecha,
                                                        eventos.estado as estado
                                                    ')
                                    ->where('eventos.estado',1)
                                    ->get();
        }else{
            $this->sucursales = UserSucursal::selectRaw('
                                                        user_sucursals.id as id_user_sucursal,
                                                        user_sucursals.id_usuario as id_usuario_user_sucursal,
                                                        user_sucursals.id_sucursal as id_sucursal_user_sucursal,
                                                        user_sucursals.estado as estado_user_sucursal,
                                                        sucursals.id as id,
                                                        sucursals.razon_social as nombre,
                                                        sucursals.direccion as direccion,
                                                        sucursals.ciudad as ciudad,
                                                        sucursals.activo as estado,
                                                        users.name as nombre_usuario
                                                        ')
                                       ->join('sucursals','sucursals.id','user_sucursals.id_sucursal')
                                       ->join('users', 'users.id','user_sucursals.id_usuario')
                                       ->where('user_sucursals.id_usuario',intval($idUsuario))
                                       ->where('sucursals.activo',1)
                                       ->get();

            $this->eventos = UsuarioEvento::selectRaw('
                                                        user_evento.id as id_user_evento,
                                                        user_evento.estado as estado_user_evento,
                                                        user_evento.created_at as created_at_user_evento,
                                                        user_evento.updated_at as updated_at_user_evento,
                                                        eventos.id as id,
                                                        eventos.nombre as nombre,
                                                        eventos.fecha_evento as fecha,
                                                        eventos.estado as estado')
                                    ->join('eventos', 'eventos.id', 'user_evento.id_evento')
                                    ->where('user_evento.id_usuario', intval($idUsuario))
                                    ->where('user_evento.estado',1)
                                    ->get();
        }
    }

    #[On('boton-on-of')]
    public function ValoresEntreComponentes($estadoBotonOnOff)
    {
        $this->sucursalesEventosUsuario(auth()->user()->usertype_id, auth()->user()->id);
        
        if (!$estadoBotonOnOff) 
        {
           $this->titleLabel = "Sucursal";
           $this->eventosOSucursales = $this->sucursales;
        } else {
            $this->titleLabel = "Evento";
            $this->eventosOSucursales = $this->eventos;
        }

        $this->idSelector = "seleccionado";
    }

    public function obtenerReporte()
    {   
        $this->sucursalesEventosUsuario(auth()->user()->usertype_id, auth()->user()->id);
        
        if (strtolower($this->titleLabel) == strtolower('Sucursal')) 
        {
           $this->titleLabel = "Sucursal";
           $this->eventosOSucursales = $this->sucursales;
        } else {
            $this->titleLabel = "Evento";
            $this->eventosOSucursales = $this->eventos;
        }

        if($this->fechaFinal >= $this->fechaInicial)
        {
            return Excel::download( 
                new VentaReporteExcelExport(
                    $this->idSelector, 
                    $this->titleLabel,
                    $this->fechaInicial." 00:00:00", 
                    $this->fechaFinal." 23:59:59"
                ), 
                
                "detalleventa".$this->fechaInicial."_".$this->fechaFinal.".xlsx");
        }
        else{
            
        }
    }

    public function render()
    {
        return view('livewire.reporte-ventas-component',[
            'eventosOSucursales' => $this->eventosOSucursales
        ]);
    }
}
