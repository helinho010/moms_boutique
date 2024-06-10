<?php

namespace App\Livewire;

use App\Models\Sucursal;
use App\Models\UserSucursal;
use App\Models\UsuarioEvento;
use App\Models\Venta;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;

class DetalleVenta extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $sucursales; // Array de sucursales donde el ususario tiene acceso 
    public $eventos;    // Array de eventos que el usuario tiene acceso
    public $eventosOSucursales; // Este maneja los dos anteriores
    
    // #[Validate('required')]
    public $idSelector;    // Maneja el identificador del selector ya sea para Sucursales o Eventos

    public $titleLabel; // Titulo del label del select 
    
    public $mensajeError;

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

            $this->eventos = UsuarioEvento::selectRaw('
                                                user_evento.id as id_user_evento,
                                                user_evento.estado as estado_user_evento,
                                                user_evento.created_at as created_at_user_evento,
                                                user_evento.updated_at as updated_at_user_evento,
                                                eventos.id as id,
                                                eventos.nombre as nombre,
                                                eventos.fecha_evento as fecha,
                                                eventos.estado as estado
                                                ')
                                    ->join('eventos', 'eventos.id', 'user_evento.id_evento')
                                    ->where('user_evento.estado',1)
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
                                                        users.name as nombre_usuario,
                                                        users.usertype_id as tipo_usuario')
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

    public function x($id)
    {
        $ventas = "";

        $columnas = 'venta.id as id_venta,
        venta.descuento as descuento_venta,
        venta.total_venta,
        venta.efectivo_recibido,
        venta.cambio,
        venta.envio as envio_venta,
        venta.referencia as referencia_venta,
        venta.observacion as observacion_venta,
        venta.estado as estado_venta,
        venta.created_at as created_at_venta,
        venta.updated_at as updated_at_venta,
        tipo_pagos.id as id_tipo_pagos,
        tipo_pagos.tipo as tipo_pagos,
        tipo_pagos.estado as estado_tipo_pagos,
        users.id as id_users,
        users.name as nombre_users,
        users.estado as estado_users,
        users.usertype_id as id_tipo_users,';

        if (strtolower($this->titleLabel)  == strtolower('Sucursal')) 
        {
           $columnas = $columnas . 'sucursals.id as id_sucursals,
                                    sucursals.nit as nit_sucursals,
                                    sucursals.razon_social as razon_social_sucursals,
                                    sucursals.direccion as dircceion_sucursals,
                                    sucursals.ciudad as ciudad_sucursals,
                                    sucursals.almacen_central,
                                    sucursals.activo as estado_sucursals';
        } else {
            
            $columnas = $columnas . 'eventos.id as id_eventos,
                                     eventos.nombre as nombre_eventos,
                                     eventos.fecha_evento as fecha_eventos,
                                     eventos.estado as estado_eventos';
        }
        
        $ventas = Venta::selectRaw($columnas)
                             ->join('tipo_pagos', 'tipo_pagos.id', 'venta.id_tipo_pago')
                             ->join('users', 'users.id', 'venta.id_usuario');
        
        if ( strtolower($this->titleLabel) == strtolower('Sucursal') ) 
        {
            $ventas = $ventas->join('sucursals', 'sucursals.id', 'venta.id_sucursal')
                                         ->where('sucursals.id',intval($id))
                                         ->paginate(10);
        } else {
            $ventas = $ventas->join('eventos', 'eventos.id', 'venta.id_evento')
                                         ->where('eventos.id',intval($id))
                                         ->paginate(10);
        }

        return $ventas;
    }

    public function mount()
    {
        $this->sucursalesEventosUsuario(auth()->user()->usertype_id, auth()->user()->id);
        $this->titleLabel = "Sucursal";
        $this->idSelector = "seleccionado";
        $this->eventosOSucursales = $this->sucursales;
        $this->mensajeError = "";
        // $this->ventas = Venta::where("created_at","0000-00-00 00:00")
        //                     //->where("estado",1)
        //                       ->paginate(10);
    }


    public function buscarRegistrosDeVentas()
    {
        $this->sucursalesEventosUsuario(auth()->user()->usertype_id, auth()->user()->id);

        if(intval($this->idSelector) > 0)
        {
            $this->mensajeError = "";
        }else{
            $this->idSelector = "seleccionado";
            $this->mensajeError = "(*) Debe seleccionar una opcion";
        }

        if (strtolower($this->titleLabel) == strtolower("Sucursal")) 
        {
            $this->eventosOSucursales = $this->sucursales;
        } else {
            $this->eventosOSucursales = $this->eventos;
        }
        
    }

    public function updatingSucursalSeleccionada()
    {
        $this->resetPage();
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
    
    public function render()
    {
                
        return view('livewire.detalle-venta', [
            // 'eventosOSucursales' => $this->eventosOSucursales,
            'ventas' => $this->x($this->idSelector) ? $this->x($this->idSelector) : collect() ,
        ]);   
    }
}
