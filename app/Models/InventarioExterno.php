<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioExterno extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_sucursal',
        'id_producto', 
        'id_evento',
        'id_usuario',
        'id_tipo_ingreso_salida',
        'cantidad',
        'activo'
    ];

    public static function buscar($buscar, $id_evento)
    {
        $inventario = self::selectRaw('
                                    inventario_externos.id as id_inventario_externo, 
                                    inventario_externos.cantidad as cantidad_inventario_externo,
                                    inventario_externos.activo as estado_inventario_externo,
                                    productos.id as id_producto,
                                    productos.nombre as nombre_producto,
                                    productos.costo as costo_producto,
                                    productos.precio as precio_producto,
                                    productos.talla as talla_producto,
                                    productos.descripcion as descripcion_producto,
                                    productos.estado as estado_producto,
                                    sucursals.id as id_sucursal,
                                    sucursals.razon_social as razon_social_sucursal,
                                    sucursals.direccion as direccion_sucursal,
                                    sucursals.telefonos as telefonos_sucursal,
                                    sucursals.ciudad as ciudad_sucursal,
                                    sucursals.almacen_central as es_almacen_central_sucursal,
                                    sucursals.activo as estado_sucursal,
                                    users.id as id_usuario,
                                    users.name as nombre_del_usuario,
                                    users.estado as estado_usuario,
                                    eventos.id as id_evento,
                                    eventos.nombre as nombre_evento,
                                    eventos.fecha_evento as fecha_evento,
                                    eventos.estado as estado_evento,
                                    tipo_ingreso_salidas.id as id_tipo_ingreso_salida,
                                    tipo_ingreso_salidas.tipo tipo_ingreso_salida,
                                    tipo_ingreso_salidas.estado as estado_tipo_ingreso_salida
                                     ')
            ->join('productos', 'productos.id', 'inventario_externos.id_producto')
            ->join('sucursals', 'sucursals.id', 'inventario_externos.id_sucursal')
            ->join('users', 'users.id', 'inventario_externos.id_usuario')
            ->join('eventos', 'eventos.id', 'inventario_externos.id_evento')
            ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_externos.id_tipo_ingreso_salida')
            ->where('inventario_externos.id_evento', $id_evento)
            ->whereRaw("
                        productos.nombre like '%".$buscar."%' 
                        or productos.precio like '%".$buscar."%' 
                        or productos.talla like '%".$buscar."%' 
                        or tipo_ingreso_salidas.tipo like '%".$buscar."%' 
                        or users.name like '%".$buscar."%'
                        or inventario_externos.cantidad like '%".$buscar."%'
                        ")
            ->where('inventario_externos.activo', 1)
            ->orderBy('productos.nombre','asc');

        return $inventario;
    }

    public static function inventarioXEvento($id_evento)
    {
        $inventario = self::selectRaw('
                                    inventario_externos.id as id_inventario_externo, 
                                    inventario_externos.cantidad as stock,
                                    inventario_externos.activo as estado_inventario_externo,
                                    productos.id as id_productos,
                                    productos.nombre as nombre_productos,
                                    productos.costo as costo_productos,
                                    productos.precio as precio_productos,
                                    productos.talla as talla_productos,
                                    productos.descripcion as descripcion_productos,
                                    productos.estado as estado_productos,
                                    sucursals.id as id_sucursal,
                                    sucursals.razon_social as razon_social_sucursal,
                                    sucursals.direccion as direccion_sucursal,
                                    sucursals.telefonos as telefonos_sucursal,
                                    sucursals.ciudad as ciudad_sucursal,
                                    sucursals.almacen_central as es_almacen_central_sucursal,
                                    sucursals.activo as estado_sucursal,
                                    users.id as id_usuario,
                                    users.name as nombre_del_usuario,
                                    users.estado as estado_usuario,
                                    eventos.id as id_evento,
                                    eventos.nombre as nombre_evento,
                                    eventos.fecha_evento as fecha_evento,
                                    eventos.estado as estado_evento,
                                    tipo_ingreso_salidas.id as id_tipo_ingreso_salida,
                                    tipo_ingreso_salidas.tipo tipo_ingreso_salida,
                                    tipo_ingreso_salidas.estado as estado_tipo_ingreso_salida
                                     ')
            ->join('productos', 'productos.id', 'inventario_externos.id_producto')
            ->join('sucursals', 'sucursals.id', 'inventario_externos.id_sucursal')
            ->join('users', 'users.id', 'inventario_externos.id_usuario')
            ->join('eventos', 'eventos.id', 'inventario_externos.id_evento')
            ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_externos.id_tipo_ingreso_salida')
            ->where('inventario_externos.id_evento', $id_evento)
            // ->where('inventario_externos.activo', 1)
            ->orderBy('productos.nombre','asc');

        return $inventario;
    }
}
