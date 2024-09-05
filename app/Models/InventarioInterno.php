<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioInterno extends Model
{
    use HasFactory;
    protected $fillable = ['id_sucursal',
                            'id_producto','id_usuario',
                            'id_tipo_ingreso_salida',
                            'cantidad_ingreso',
                            'stock',
                          ];

    public static function inventarioXSucurusal($idSucursal)
    {
      $inventario = self::selectRaw('
                                    inventario_internos.id as id_inventario_internos,
                                    inventario_internos.cantidad_ingreso as cantidad_ingreso_inventario_internos,
                                    inventario_internos.stock as stock_inventario_internos,
                                    inventario_internos.estado as estado_inventario_internos,
                                    productos.id as id_productos,
                                    productos.nombre as nombre_productos,
                                    productos.costo as costo_productos,
                                    productos.precio as precio_productos,
                                    productos.talla as talla_productos,
                                    productos.estado as estado_productos,
                                    sucursals.id as id_sucursals,
                                    sucursals.nit as nit_sucursals,
                                    sucursals.razon_social as razon_social_sucursals,
                                    sucursals.direccion as direccion_sucursals,
                                    sucursals.telefonos as telefono_sucursals,
                                    sucursals.ciudad as ciudad_sucursals,
                                    sucursals.activo as estado_sucursals,
                                    users.id as id_users,
                                    users.name as name_users,
                                    users.estado as estado_users,
                                    tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                    tipo_ingreso_salidas.tipo as tipo_tipo_ingreso_salidas,
                                    tipo_ingreso_salidas.estado
                                   ')
                        ->join('productos', 'productos.id', 'inventario_internos.id_producto')
                        ->join('sucursals', 'sucursals.id', 'inventario_internos.id_sucursal')
                        ->join('users', 'users.id', 'inventario_internos.id_usuario')
                        ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_internos.id_tipo_ingreso_salida');
      if($idSucursal == 999 )
      {
        $inventario = $inventario->get();
      }else{
        $inventario = $inventario->where('sucursals.id', $idSucursal)
                                 ->get();
      }
                        
      return $inventario;
    }
}
