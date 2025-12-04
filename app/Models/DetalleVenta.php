<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    public static function ventaDetalleItems($id_venta)
    {
        $items = self::selectRaw('
                                    detalle_ventas.id as id_detalle_ventas,
                                    detalle_ventas.cantidad as cantidad_detalle_ventas,
                                    detalle_ventas.descuento_item as decuento_item_detalle_ventas,
                                    detalle_ventas.descripcion as descripcion_detalle_ventas,
                                    detalle_ventas.precio_unitario as precio_unitario_detalle_ventas,
                                    detalle_ventas.subtotal as subtotal_detalle_ventas,
                                    venta.id as id_venta,
                                    venta.id_sucursal as id_sucursal_venta,
                                    venta.id_evento as id_evento_venta,
                                    venta.id_tipo_pago,
                                    venta.id_usuario,
                                    venta.id_cliente,
                                    venta.descuento as desuento_total_venta,
                                    venta.total_venta,
                                    venta.efectivo_recibido,
                                    venta.cambio,
                                    venta.numero_factura,
                                    venta.envio,
                                    venta.referencia,
                                    venta.observacion,
                                    venta.estado,
                                    venta.nombre_pdf,
                                    venta.created_at,
                                    venta.updated_at
                                ')
                     ->join('venta', 'venta.id', 'detalle_ventas.id_venta')
                     ->where("venta.id", $id_venta);
        return $items;
    }
}
