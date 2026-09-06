<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    public static function reporteCompras($idSucursal, $fechaInicio, $fechaFin)
    {
        return self::selectRaw(
                                'detalle_compras.id as id_detalle_compras,
                                detalle_compras.descripcion as descripcion,
                                detalle_compras.cantidad as cantidad,
                                detalle_compras.precio_unitario,
                                detalle_compras.iva,
                                detalle_compras.sub_total,
                                detalle_compras.created_at as created_at_detalle_compras,
                                detalle_compras.updated_at as updated_at_detalle_compras,
                                compras.id as id_compras,
                                compras.id_sucursal_destino,
                                compras.codigo_compra,
                                compras.fecha_compra,
                                compras.total_compra,
                                compras.total_iva,
                                compras.estado_aprobacion,
                                compras.fecha_creacion_compra,
                                compras.fecha_aprobacion,
                                compras.fecha_envio_productos_bd,
                                compras.estado as estado_compras,
                                compras.observaciones,
                                compras.con_iva,
                                compras.created_at as created_at_compras,
                                compras.updated_at as updated_at_compras,
                                usuario_creador.name as nombre_usuario_creador,
                                usuario_creador.username as username_usuario_creador,
                                usuario_aprobador.name as nombre_usuario_aprobador,
                                usuario_aprobador.username as username_usuario_aprobador,
                                usuario_rechazador.name as nombre_usuario_rechazador,
                                usuario_rechazador.username as username_usuario_rechazador,
                                usuario_enviobd.name as nombre_usuario_enviobd,
                                usuario_enviobd.username as username_usuario_enviobd'
                               )
                ->Join('compras', 'detalle_compras.id_compra', '=', 'compras.id')
                ->leftJoin('users as usuario_creador', 'usuario_creador.id', '=', 'compras.id_usuario')
                ->leftJoin('users as usuario_aprobador', 'usuario_aprobador.id', '=', 'compras.id_usuario_aprobador')
                ->leftJoin('users as usuario_rechazador', 'usuario_rechazador.id', '=', 'compras.id_usuario_rechazador')
                ->leftJoin('users as usuario_enviobd', 'usuario_enviobd.id', '=', 'compras.id_usuario_envio_bd')
                ->where('compras.id_sucursal_destino', $idSucursal)
                ->whereBetween('compras.fecha_compra', [$fechaInicio, $fechaFin])
                ->whereIn('compras.estado_aprobacion',[1,3])
                ->orderBy('compras.updated_at', 'desc')
                ->get();
    }
}
