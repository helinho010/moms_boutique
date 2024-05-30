<?php

namespace App\Livewire;

use App\Models\Evento;
use App\Models\InventarioExterno;
use App\Models\TipoPago;
use Livewire\Component;

class RealizarVenta extends Component
{
    public $evento;
    public $tipoPagos;
    public $productosEvento;


    public function mount()
    {
        $this->evento = Evento::where('id',session('eventoSeleccionadoParaVenta'))->get();

        $this->tipoPagos = TipoPago::where('estado',1)->get();

        $this->productosEvento = InventarioExterno::selectRaw(' inventario_externos.id as id_inventario_externos,
                                                            inventario_externos.cantidad as cantidad_inventario_externos,
                                                            inventario_externos.activo as estado_inventario_externos,
                                                            inventario_externos.created_at as created_at_inventario_externos,
                                                            inventario_externos.updated_at as updated_at_inventario_externos,
                                                            productos.id as id_productos,
                                                            productos.nombre as nombre_productos,
                                                            productos.costo as costo_productos,
                                                            productos.talla as talla_productos,
                                                            productos.estado as estado_productos,
                                                            sucursals.id as id_sucursals,
                                                            sucursals.razon_social as razon_social_sucursals,
                                                            sucursals.direccion as direccion_sucursals,
                                                            sucursals.ciudad as ciudad_sucursals,
                                                            sucursals.activo as estado_sucursals,
                                                            users.id as id_users,
                                                            users.name as name_users,
                                                            users.estado as estado_users,
                                                            eventos.id as id_eventos,
                                                            eventos.nombre as nombre_eventos,
                                                            eventos.estado as estado_eventos,
                                                            tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                                            tipo_ingreso_salidas.tipo as tipo_tipo_ingreso_salidas,
                                                            tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas')
                                                ->join('productos', 'productos.id','inventario_externos.id_producto')
                                                ->join('sucursals', 'sucursals.id', 'inventario_externos.id_sucursal')
                                                ->join('users', 'users.id', 'inventario_externos.id_usuario')
                                                ->join('eventos', 'eventos.id', 'inventario_externos.id_evento')
                                                ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_externos.id_tipo_ingreso_salida')
                                                ->where('eventos.id', session('eventoSeleccionadoParaVenta'))
                                                ->get();
    }

    public function render()
    {
        return view('livewire.realizar-venta');
    }
}
