<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Luecano\NumeroALetras\NumeroALetras;
use App\Models\InventarioInterno;
use App\Models\Sucursal;
use App\Models\TipoPago;
use App\Models\UserSucursal;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

class DetalleVentaController extends Controller
{
    public function index()
    {
        //$sucursales = Sucursal::where('activo',1)->get();
        if (auth()->user()->usertype_id == 1) {
            $sucursales = Sucursal::selectRaw(' sucursals.id as id_sucursal,
                                                sucursals.id as id_sucursal_user_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.direccion as direccion_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal')
                                    ->where('sucursals.activo',1)
                                    ->get();
        } else {
            $sucursales = UserSucursal::selectRaw('user_sucursals.id as id_user_sucursal,
                                                user_sucursals.id_usuario as id_usuario_user_sucursal,
                                                user_sucursals.id_sucursal as id_sucursal_user_sucursal,
                                                user_sucursals.estado as estado_user_sucursal,
                                                sucursals.id as id_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.direccion as direccion_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal,
                                                users.name as nombre_usuario,
                                                users.usertype_id as tipo_usuario')
                                       ->join('sucursals','sucursals.id','user_sucursals.id_sucursal')
                                       ->join('users', 'users.id','user_sucursals.id_usuario')
                                       ->where('user_sucursals.id_usuario',auth()->user()->id)
                                       ->where('sucursals.activo',1)
                                       ->get();
        }
        return view('Venta.homeVenta',[
            'sucursales'=>$sucursales,
        ]);
    }

    public function seleccionSucursalVenta(Request $request)
    {
        $sucursal = Sucursal::where('id',$request->id_sucursal)->get();
        $tipoPagos = TipoPago::where('estado',1)->get();
        $productosSucursal = InventarioInterno::selectRaw(' inventario_internos.id as id_inventario_interno,
                                                            inventario_internos.stock,
                                                            inventario_internos.estado as estado_inventario_interno,
                                                            inventario_internos.created_at as created_at_inventario_interno, 
                                                            inventario_internos.updated_at as updated_at_inventario_interno, 
                                                            productos.id as id_producto,
                                                            productos.codigo_producto,
                                                            productos.nombre as nombre_producto,
                                                            productos.precio,
                                                            productos.talla,
                                                            productos.estado as estado_producto,
                                                            sucursals.id as id_sucursal,
                                                            sucursals.razon_social as razon_social_sucursal,
                                                            sucursals.ciudad as ciudad_sucursal,
                                                            sucursals.activo as estado_sucursal,
                                                            users.name as nombre_usuario,
                                                            users.id as id_usuario,
                                                            tipo_ingreso_salidas.id as id_tipo_ingreso_salida,
                                                            tipo_ingreso_salidas.tipo as nombre_tipo_ingreso_salida,
                                                            tipo_ingreso_salidas.estado as estado_tipo_ingreso_salida')
                                               ->join('productos','productos.id','inventario_internos.id_producto')
                                               ->join('sucursals','sucursals.id','inventario_internos.id_sucursal')
                                               ->join('users','users.id','inventario_internos.id_usuario')
                                               ->join('tipo_ingreso_salidas','tipo_ingreso_salidas.id','inventario_internos.id_tipo_ingreso_salida')
                                               ->where('sucursals.id',$request->id_sucursal)
                                               ->where('sucursals.activo',1) 
                                               ->get();

        session(['sucursalSeleccionadoParaVenta' => $request->id_sucursal]);

        return view('Venta.venta',[
            'sucursal'=>$sucursal,
            'productos' => $productosSucursal,
            'tipoPagos' => $tipoPagos,
        ]);
    }

    public function numeroALetras(Request $request)
    {
        $formatter = new NumeroALetras();
        if (isset($request->efectivo)) 
        {
            return $formatter->toMoney(floatval($request->efectivo), 2, 'BOLIVIANOS', 'CENTAVOS');  
        }else{
            return $formatter->toMoney(0.00, 2, 'BOLIVIANOS', 'CENTAVOS');  
        } 
    }

    public function realizarVenta(Request $request)
    {
        // try {
            foreach ($request->productos as $key => $value) {
                $reducirInventario = InventarioInterno::where('id_producto',$value["id_producto"])
                                                      ->where('id_sucursal',session('sucursalSeleccionadoParaVenta'))
                                                      ->where('estado',1)
                                                      ->first();
                if ($value['cantidad'] <= $reducirInventario->stock) 
                {
                    $reducirInventario->stock = $reducirInventario->stock - $value['cantidad'];
                    $reducirInventario->save();
                }else{
                    return false;
                }
                
                $newVenta = new DetalleVenta();
                $newVenta->id_producto = $value["id_producto"]; 
                $newVenta->id_sucursal = session('sucursalSeleccionadoParaVenta');
                $newVenta->id_usuario = auth()->user()->id;
                $newVenta->id_tipo_pago = $request->idTipoPago;
                $newVenta->cantidad = $value["cantidad"];
                $newVenta->save();
            }
            return true;

        // } catch (\Throwable $th) {
        //     report($th);
 
        //     return false;
        // }
    }

    public function exportVentaPdf()
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('hello world');
        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
        return ('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veritatis id maiores officiis soluta unde exercitationem quo dolor repellendus beatae vero. Qui veritatis inventore omnis, laudantium dolor cupiditate saepe pariatur error?') ;
    }
}
