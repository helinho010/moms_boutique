<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class TrasporteProductos extends Model
{
    use HasFactory;

    public static function buscar($buscar)
    {
        return self::selectRaw('
                                trasporte_productos.id as id_trasporte_productos,
                                trasporte_productos.id_sucursal_origen,
	                            trasporte_productos.id_sucursal_destino,
                                trasporte_productos.cantidad as cantidad_trasporte_productos,
                                trasporte_productos.observaciones as observaciones_trasporte_productos,
                                trasporte_productos.estado as estado_trasporte_productos,
                                trasporte_productos.updated_at as updated_at_trasporte_productos,
                                sucursals.id as id_sucursal,
                                sucursals.razon_social as razon_social_sucursal,
                                sucursals.direccion as direccion_sucursal,
                                sucursals.ciudad as ciudad_sucursal,
                                sucursals.almacen_central as almacen_central_sucursal,
                                sucursals.activo as estado_sucursal,
                                productos.id as id_producto,
                                productos.nombre as nombre_producto,
                                productos.costo as costo_producto,
                                productos.precio as precio_venta_producto,
                                productos.talla as talla_producto,
                                productos.descripcion as descripcion_producto,
                                productos.estado as estado_producto,
                                tipo_ingreso_salidas.id as id_tipo_ingreso_salida,
                                tipo_ingreso_salidas.tipo as tipo_ingreso_salida,
                                tipo_ingreso_salidas.estado as estado_tipo_ingreso_salida,
                                users.id as id_usuario,
                                users.name as nombre_usuario,
                                users.estado as estado_usuario
                                ')
                    ->join('sucursals', 'trasporte_productos.id_sucursal_origen', 'sucursals.id')
                    ->join('productos', 'trasporte_productos.id_producto', 'productos.id')
                    ->join('tipo_ingreso_salidas', 'trasporte_productos.id_tipo_ingreso_salida', 'tipo_ingreso_salidas.id')
                    ->join('users', 'trasporte_productos.id_usuario', 'users.id')
                    ->where(function ($query) use ($buscar) {
                        $query->where('trasporte_productos.cantidad', 'like', "%{$buscar}%")
                              ->orWhere('trasporte_productos.observaciones', 'like', "%{$buscar}%")
                              ->orWhere('trasporte_productos.updated_at', 'like', "%{$buscar}%")
                              ->orWhere('sucursals.razon_social', 'like', "%{$buscar}%")
                              ->orWhere('sucursals.direccion', 'like', "%{$buscar}%")
                              ->orWhere('sucursals.ciudad', 'like', "%{$buscar}%")
                              ->orWhere('productos.nombre', 'like', "%{$buscar}%")
                              ->orWhere('productos.talla', 'like', "%{$buscar}%")
                              ->orWhere('productos.descripcion', 'like', "%{$buscar}%")
                              ->orWhere('tipo_ingreso_salidas.tipo', 'like', "%{$buscar}%")
                              ->orWhere('users.name', 'like', "%{$buscar}%");
                    })
                    ->orderBy('trasporte_productos.updated_at', 'desc');
    }


    public static function todosTraspaso()
    {

        return self::selectRaw('
                                trasporte_productos.id as id_trasporte_productos,
                                trasporte_productos.id_sucursal_origen,
	                            trasporte_productos.id_sucursal_destino,
                                trasporte_productos.cantidad as cantidad_trasporte_productos,
                                trasporte_productos.observaciones as observaciones_trasporte_productos,
                                trasporte_productos.estado as estado_trasporte_productos,
                                trasporte_productos.updated_at as updated_at_trasporte_productos,
                                sucursals.id as id_sucursal,
                                sucursals.razon_social as razon_social_sucursal,
                                sucursals.direccion as direccion_sucursal,
                                sucursals.ciudad as ciudad_sucursal,
                                sucursals.almacen_central as almacen_central_sucursal,
                                sucursals.activo as estado_sucursal,
                                productos.id as id_producto,
                                productos.nombre as nombre_producto,
                                productos.costo as costo_producto,
                                productos.precio as precio_venta_producto,
                                productos.talla as talla_producto,
                                productos.descripcion as descripcion_producto,
                                productos.estado as estado_producto,
                                tipo_ingreso_salidas.id as id_tipo_ingreso_salida,
                                tipo_ingreso_salidas.tipo as tipo_ingreso_salida,
                                tipo_ingreso_salidas.estado as estado_tipo_ingreso_salida,
                                users.id as id_usuario,
                                users.name as nombre_usuario,
                                users.estado as estado_usuario
                                ')
                    ->join('sucursals', 'trasporte_productos.id_sucursal_origen', 'sucursals.id')
                    ->join('productos', 'trasporte_productos.id_producto', 'productos.id')
                    ->join('tipo_ingreso_salidas', 'trasporte_productos.id_tipo_ingreso_salida', 'tipo_ingreso_salidas.id')
                    ->join('users', 'trasporte_productos.id_usuario', 'users.id')
                    ->orderBy('trasporte_productos.updated_at', 'desc');
    }

    public static function traspasoXSucursal($id_sucursal)
    {
        return self::selectRaw('
                                trasporte_productos.id as id_trasporte_productos,
                                trasporte_productos.id_sucursal_origen,
                                trasporte_productos.id_sucursal_destino,
                                trasporte_productos.cantidad as cantidad_trasporte_productos,
                                trasporte_productos.observaciones as observaciones_trasporte_productos,
                                trasporte_productos.estado as estado_trasporte_productos,
                                trasporte_productos.updated_at as updated_at_trasporte_productos,
                                sucursals.id as id_sucursal,
                                sucursals.razon_social as razon_social_sucursal,
                                sucursals.direccion as direccion_sucursal,
                                sucursals.ciudad as ciudad_sucursal,
                                sucursals.almacen_central as almacen_central_sucursal,
                                sucursals.activo as estado_sucursal,
                                productos.id as id_producto,
                                productos.nombre as nombre_producto,
                                productos.costo as costo_producto,
                                productos.precio as precio_venta_producto,
                                productos.talla as talla_producto,
                                productos.descripcion as descripcion_producto,
                                productos.estado as estado_producto,
                                tipo_ingreso_salidas.id as id_tipo_ingreso_salida,
                                tipo_ingreso_salidas.tipo as tipo_ingreso_salida,
                                tipo_ingreso_salidas.estado as estado_tipo_ingreso_salida,
                                users.id as id_usuario,
                                users.name as nombre_usuario,
                                users.estado as estado_usuario
                                ')
                    ->join('sucursals', 'trasporte_productos.id_sucursal_origen', 'sucursals.id')
                    ->join('productos', 'trasporte_productos.id_producto', 'productos.id')
                    ->join('tipo_ingreso_salidas', 'trasporte_productos.id_tipo_ingreso_salida', 'tipo_ingreso_salidas.id')
                    ->join('users', 'trasporte_productos.id_usuario', 'users.id')
                    ->where('trasporte_productos.id_sucursal_origen', $id_sucursal)
                    ->orderBy('trasporte_productos.updated_at', 'desc');
    }
}
