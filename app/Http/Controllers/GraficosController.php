<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleVenta;
use App\Models\Venta;

class GraficosController extends Controller
{
    public function productosMasVendidos(Request $request)
    {
        $rankingProductos = DetalleVenta::selectRaw('
                                                    id_producto,
                                                    descripcion, 
                                                    sum(cantidad) as total_vendidos
                                                    ')
                                        ->groupByRaw('id_producto, descripcion')
                                        //->orderBy('3','desc')
                                        ->limit(10)
                                        ->get();
        
        $rankingUsuarios = Venta::selectRaw('
                                            venta.id_usuario,
                                            users.name,
                                            count(venta.id_usuario) as numero_ventas,
                                            sum(venta.total_venta) as total_vendido	 
                                            ')
                                ->join('users', 'users.id', 'venta.id_usuario')
                                ->groupByRaw('venta.id_usuario, users.name')
                                //->orderBy('4','desc')
                                ->limit(10)
                                ->get();
                                        
        return [
            'productos10MasVendidos'=>$rankingProductos,
            'usuarioConMayorVenta'=>$rankingUsuarios, //[['usuario'=>'hmejia', 'ventas'=>50],['usuario'=>'lramos', 'ventas'=>100]],
        ];
    }
}
