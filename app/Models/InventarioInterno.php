<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioInterno extends Model
{
    use HasFactory;
    protected $fillable = [ 'id_sucursal',
                            'id_producto',
                            'id_usuario',
                            'id_tipo_ingreso_salida',
                            'cantidad_ingreso',
                            'stock',
                            'activo',
                          ];

     protected $casts = [
        'id_sucursal' => 'integer',
        'id_producto' => 'integer',
        'id_usuario' => 'integer',
        'id_tipo_ingreso_salida' => 'integer',
        'cantidad_ingreso' => 'integer',
        'stock' => 'integer',
     ];

    public static function inventarioXSucurusal($idSucursal)
    {
      $inventario = self::selectRaw('
                                    inventario_internos.id as id_inventario_internos,
                                    inventario_internos.stock as stock_inventario_internos,
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
                                    tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas
                                   ')
                        ->join('productos', 'productos.id', 'inventario_internos.id_producto')
                        ->join('sucursals', 'sucursals.id', 'inventario_internos.id_sucursal')
                        ->join('users', 'users.id', 'inventario_internos.id_usuario')
                        ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_internos.id_tipo_ingreso_salida');
      if($idSucursal != 999 )
      {
        $inventario = $inventario->where('sucursals.id', $idSucursal)
                                 ->orderBy('productos.nombre', 'asc');
      }
                        
      return $inventario;
    }

    public static function buscar($idSucursal, $buscar, $paginate=10)
    {
       $inventario = self::selectRaw('inventario_internos.id as id_inventario_internos,
                                      inventario_internos.stock as stock_inventario_internos,
                                      inventario_internos.cantidad_ingreso as cantidad_ingreso_inventario_internos,
                                      inventario_internos.estado as estado_inventario_internos,
                                      inventario_internos.created_at as created_at_inventario_internos, 
                                      inventario_internos.updated_at as updated_at_inventario_internos, 
                                      productos.id as id_productos,
                                      productos.codigo_producto as codigo_producto_productos,
                                      productos.nombre as nombre_productos,
                                      productos.costo as costo_productos,
                                      productos.precio as precio_productos,
                                      productos.talla as talla_productos,
                                      productos.estado as estado_productos,
                                      sucursals.id as id_sucursals,
                                      sucursals.razon_social as razon_social_sucursals,
                                      sucursals.ciudad as ciudad_sucursals,
                                      sucursals.activo as estado_sucursals,
                                      users.id as id_users,
                                      users.name as name_users,
                                      tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                      tipo_ingreso_salidas.tipo as tipo_tipo_ingreso_salidas,
                                      tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas')
                      ->join('productos','productos.id','inventario_internos.id_producto')
                      ->join('sucursals','sucursals.id','inventario_internos.id_sucursal')
                      ->join('users','users.id','inventario_internos.id_usuario')
                      ->join('tipo_ingreso_salidas','tipo_ingreso_salidas.id','inventario_internos.id_tipo_ingreso_salida')
                      ->where('sucursals.id',$idSucursal)
                      ->whereRaw("productos.nombre like '%".$buscar."%' or productos.precio like '%".$buscar."%' or productos.talla like '%".$buscar."%' or tipo_ingreso_salidas.tipo like '%".$buscar."%' or users.name like '%".$buscar."%'")
                      ->orderBy('updated_at_inventario_internos','desc')
                      ->paginate($paginate);

        return $inventario;
    }

    public static function filterProductos($texto)
    {
        return self::selectRaw('inventario_internos.id as id_inventario_internos,
                                    inventario_internos.stock as stock_inventario_internos,
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
                                    tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas
                                   ')
                        ->join('productos', 'productos.id', 'inventario_internos.id_producto')
                        ->join('sucursals', 'sucursals.id', 'inventario_internos.id_sucursal')
                        ->join('users', 'users.id', 'inventario_internos.id_usuario')
                        ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_internos.id_tipo_ingreso_salida')
                        ->where('productos.nombre', 'like', "%{$texto}%")
                        // ->orWhere('productos.codigo_producto', 'like', "%{$texto}%")
                        ->orWhere('productos.talla', 'like', "%{$texto}%")
                        ->orWhere('productos.precio', 'like', "%{$texto}%")
                        ->orderBy('productos.nombre', 'asc');

    }
}
