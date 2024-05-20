<?php

namespace App\Livewire;

use App\Models\InventarioInterno;
use App\Models\Sucursal;
use App\Models\UserSucursal;
use Livewire\Component;

class SelectoFilterProductos extends Component
{
    public $id_sucursal_seleccionado;
    public $sucursales,$productosPorSucursal;

    public function mount()
    {
        $this->id_sucursal_seleccionado="seleccionado";
    }
    
    public function render()
    {

        if(auth()->user()->id == 1)
        {
            $this->sucursales = Sucursal::selectRaw('
                                                        sucursals.id as id_sucursal,
                                                        sucursals.razon_social as razon_social_sucursal,
                                                        sucursals.direccion as direccion_sucursal,
                                                        sucursals.ciudad as ciudad_sucursal,
                                                        sucursals.activo as estado_sucursal
                                                    ')
                                        ->where('sucursals.activo',1)
                                        ->get();
        }else{
            $this->sucursales = UserSucursal::selectRaw('
                                                        user_sucursals.id as id_user_sucursals,
                                                        user_sucursals.estado as estado_user_sucursals,
                                                        user_sucursals.created_at as created_at_user_sucursals,
                                                        user_sucursals.updated_at as updated_at_user_sucursals,
                                                        users.id as id_usuario,
                                                        users.name as nombre_usuario,
                                                        users.username as  nombre_login_usuario,
                                                        users.estado as estado_usuario,
                                                        sucursals.id as id_sucursal,
                                                        sucursals.razon_social as razon_social_sucursal,
                                                        sucursals.direccion as direccion_sucursal,
                                                        sucursals.ciudad as ciudad_sucursal,
                                                        sucursals.activo as estado_sucursal
                                                        ')
                                            ->join('users', 'users.id', 'user_sucursals.id_usuario')
                                            ->join('sucursals', 'sucursals.id', 'user_sucursals.id_sucursal')
                                            ->where('users.id',auth()->user()->id)
                                            ->where('sucursals.activo',1)
                                            ->get();

        }

        $this->productosPorSucursal = InventarioInterno::selectRaw('
                                                                    inventario_internos.id_sucursal,
                                                                    inventario_internos.cantidad_ingreso,
                                                                    inventario_internos.stock, 
                                                                    inventario_internos.estado,
                                                                    productos.id as id_producto,
                                                                    productos.nombre,
                                                                    productos.talla,
                                                                    productos.precio,
                                                                    productos.estado
                                                                  ')
                                                        ->join('productos', 'productos.id', 'inventario_internos.id_producto')
                                                        ->where('inventario_internos.id_sucursal',$this->id_sucursal_seleccionado)
                                                        ->orderBy('productos.nombre','asc')
                                                        ->get();

        return view('livewire.selecto-filter-productos',[
            'sucursales'=>$this->sucursales,
            'productos' =>$this->productosPorSucursal,
        ]);
    }
}
