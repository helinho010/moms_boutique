<?php

namespace App\Http\Controllers;

use App\Models\InventarioInterno;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\TipoIngresoSalida;
use App\Models\TrasporteProductos;
use App\Models\UserSucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrasporteProductosController extends Controller
{
    public $productosXSucursal;

    public function productos_x_Sucursal($id_sucursal)
    {
        $this->productosXSucursal = InventarioInterno::selectRaw('
                                                                    inventario_internos.id as id_inventario_interno,
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
                                                                    tipo_ingreso_salidas.estado as estado_tipo_ingreso_salida
                                                                ')
                                                     ->join('productos', 'productos.id', 'inventario_internos.id_producto')
                                                     ->join('sucursals', 'sucursals.id', 'inventario_internos.id_sucursal')
                                                     ->join('users', 'users.id', 'inventario_internos.id_usuario')
                                                     ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_internos.id_tipo_ingreso_salida')
                                                     ->where('sucursals.id',$id_sucursal)
                                                     ->where('sucursals.activo',1)
                                                     ->get();
    }

    public function index(Request $request)
    {
        if (auth()->user()->id == 1) {
            $detalleTraspasoProductos = TrasporteProductos::selectRaw('
                                                                        trasporte_productos.id as id_trasporte_productos,
                                                                        trasporte_productos.id_sucursal_origen as id_sucursal_origen,
                                                                        trasporte_productos.id_sucursal_destino  as id_sucursal_destino,
                                                                        trasporte_productos.cantidad as cantidad_trasporte_productos,
                                                                        trasporte_productos.observaciones as observaciones_trasporte_productos,
                                                                        trasporte_productos.estado as estado_trasporte_productos,
                                                                        trasporte_productos.created_at as created_at_trasporte_productos,
                                                                        trasporte_productos.updated_at as updated_at_trasporte_productos,
                                                                        productos.id as id_productos,
                                                                        productos.nombre as nombre_productos,
                                                                        productos.costo as costo_productos,
                                                                        productos.precio as precio_productos,
                                                                        productos.talla as talla_productos,
                                                                        productos.descripcion as descripcion_productos,
                                                                        productos.estado as estado_productos,
                                                                        productos.created_at as created_at_productos,
                                                                        productos.updated_at as updated_at_productos,
                                                                        tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                                                        tipo_ingreso_salidas.tipo as nombre_tipo_ingreso_salidas,
                                                                        tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas,
                                                                        users.id as id_usuario,
                                                                        users.name as name_usuario,
                                                                        users.estado as estado_usuario
                                                                    ')
                                                            ->join('productos', 'productos.id', 'trasporte_productos.id_producto')
                                                            ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'trasporte_productos.id_tipo_ingreso_salida')
                                                            ->join('users', 'users.id', 'trasporte_productos.id_usuario')
                                                            ->orderBy('trasporte_productos.updated_at','desc')
                                                            ->paginate(10);
            $sucursales = Sucursal::selectRaw('
                                                sucursals.id as id_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal,
                                                sucursals.direccion as direccion_sucursal
                                             ')
                                    ->where('sucursals.activo',1)
                                    ->get();
                                                            
        }else {
            $detalleTraspasoProductos = TrasporteProductos::selectRaw('
                                                                        trasporte_productos.id as id_trasporte_productos,
                                                                        trasporte_productos.id_sucursal_origen as id_sucursal_origen,
                                                                        trasporte_productos.id_sucursal_destino  as id_sucursal_destino,
                                                                        trasporte_productos.cantidad as cantidad_trasporte_productos,
                                                                        trasporte_productos.observaciones as observaciones_trasporte_productos,
                                                                        trasporte_productos.estado as estado_trasporte_productos,
                                                                        trasporte_productos.created_at as created_at_trasporte_productos,
                                                                        trasporte_productos.updated_at as updated_at_trasporte_productos,
                                                                        productos.id as id_productos,
                                                                        productos.nombre as nombre_productos,
                                                                        productos.costo as costo_productos,
                                                                        productos.precio as precio_productos,
                                                                        productos.talla as talla_productos,
                                                                        productos.descripcion as descripcion_productos,
                                                                        productos.estado as estado_productos,
                                                                        productos.created_at as created_at_productos,
                                                                        productos.updated_at as updated_at_productos,
                                                                        tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                                                        tipo_ingreso_salidas.tipo as nombre_tipo_ingreso_salidas,
                                                                        tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas,
                                                                        users.id as id_usuario,
                                                                        users.name as name_usuario,
                                                                        users.estado as estado_usuario
                                                                    ')
                                                            ->join('productos', 'productos.id', 'trasporte_productos.id_producto')
                                                            ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'trasporte_productos.id_tipo_ingreso_salida')
                                                            ->join('users', 'users.id', 'trasporte_productos.id_usuario')
                                                            ->where('users.id',auth()->user()->id)
                                                            ->orderBy('trasporte_productos.updated_at','desc')
                                                            ->paginate(10);

            $sucursales = UserSucursal::selectRaw('
                                            user_sucursals.id as id_user_sucursals,
                                            user_sucursals.estado as estado_user_sucursals,
                                            user_sucursals.updated_at as updated_at_user_sucursals,
                                            sucursals.id as id_sucursal,
                                            sucursals.razon_social as razon_social_sucursal,
                                            sucursals.ciudad as ciudad_sucursal,
                                            sucursals.activo as estado_sucursal,
                                            sucursals.direccion as direccion_sucursal,
                                            users.id as id_usuario,
                                            users.name as nombre_usuario,
                                            users.username as usuario,
                                            users.estado as estado_usuario
                                          ')
                                     ->join('sucursals', 'sucursals.id', 'user_sucursals.id_sucursal')
                                     ->join('users', 'users.id', 'user_sucursals.id_usuario')
                                     ->where('sucursals.activo',1)
                                     ->where('users.id',auth()->user()->id)
                                     ->get();
        }

        if (isset($request->id_sucursal)) 
        {
            $this->productos_x_Sucursal($request->id_sucursal);
        }else{
            $this->productosXSucursal = InventarioInterno::selectRaw('
                                                                    inventario_internos.id as id_inventario_interno,
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
                                                                    tipo_ingreso_salidas.estado as estado_tipo_ingreso_salida
                                                                ')
                                                     ->join('productos', 'productos.id', 'inventario_internos.id_producto')
                                                     ->join('sucursals', 'sucursals.id', 'inventario_internos.id_sucursal')
                                                     ->join('users', 'users.id', 'inventario_internos.id_usuario')
                                                     ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_internos.id_tipo_ingreso_salida')
                                                     ->where('sucursals.activo',1)
                                                     ->get();
        }

        $tipoIngresoSalida = TipoIngresoSalida::where('estado',1)->get();



        return view('traspasoProductos.index',[
            'traspasos' => $detalleTraspasoProductos,
            'sucursales' => $sucursales,
            'productos' => $this->productosXSucursal,
            'tipoSalida' => $tipoIngresoSalida,
        ]);
    }


    public function store(Request $request)
    {
        //dd($request);
        $registroInvetarioSucursalOrigen = InventarioInterno::where('id_sucursal',$request->id_sucursal_origen)
                                                      ->where('id_producto',$request->id_producto)
                                                      ->first();
        
        if ($registroInvetarioSucursalOrigen->stock >= $request->cantidad) 
        {
            $nuevoTraspasoProductos = new TrasporteProductos();
            $nuevoTraspasoProductos->id_sucursal_origen = $request->id_sucursal_origen;
            $nuevoTraspasoProductos->id_sucursal_destino = $request->id_sucursal_destino;
            $nuevoTraspasoProductos->id_producto = $request->id_producto;
            $nuevoTraspasoProductos->id_tipo_ingreso_salida = $request->id_tipo_salida;
            $nuevoTraspasoProductos->id_usuario = auth()->user()->id;
            $nuevoTraspasoProductos->cantidad = $request->cantidad;
            $nuevoTraspasoProductos->observaciones = $request->observaciones;
            $nuevoTraspasoProductos->save();
            
            $registroInvetarioSucursalOrigen->stock = $registroInvetarioSucursalOrigen->stock - $request->cantidad;
            $registroInvetarioSucursalOrigen->id_usuario = auth()->user()->id;
            $registroInvetarioSucursalOrigen->save();

            // $registroInvetarioSucursalDestino = InventarioInterno::where('id_sucursal',$request->id_sucursal_destino)
            //                                                     ->where('id_producto',$request->id_producto)
            //                                                     ->first();
            // $registroInvetarioSucursalDestino->stock = $registroInvetarioSucursalOrigen->stock + $request->cantidad;
            // $registroInvetarioSucursalDestino->id_usuario = auth()->user()->id;

            $registroInvetarioSucursalDestino = InventarioInterno::updateOrCreate(['id_sucursal'=>$request->id_sucursal_destino,'id_producto'=>$request->id_producto],
                                                                                  ['id_producto'=>$request->id_producto, 
                                                                                   'id_sucursal'=>$request->id_sucursal_destino, 
                                                                                   'id_usuario'=>auth()->user()->id,
                                                                                   'id_tipo_ingreso_salida' => $request->id_tipo_salida,
                                                                                   'cantidad_ingreso' => $request->cantidad,
                                                                                   'stock'=>DB::raw("IFNULL(stock, 0) + $request->cantidad"),
                                                                                   ]);
            $registroInvetarioSucursalDestino->save();
        }
        return redirect()->route('home_traspaso_productos');        
    } 
}
