<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\InventarioExterno;
use App\Models\Producto;
use App\Models\InventarioInterno;
use App\Models\Sucursal;
use App\Models\TipoIngresoSalida;
use App\Models\UserSucursal;
use Illuminate\Support\Facades\DB;


class InventarioExternoController extends Controller
{
    public function index()
    {
        $inventariosExternos = InventarioExterno::where('updated_at','0000-00-00')->paginate(10);
    
        if (auth()->user()->usertype_id == 1) {
            $sucursales = Sucursal::selectRaw('sucursals.id as id_sucursal_user_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.direccion as direccion_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal')
                                    // ->where('sucursals.activo',1)
                                    ->get();
        } else {
            $sucursales = UserSucursal::selectRaw('user_sucursals.id as id_user_sucursal,
                                                user_sucursals.id_usuario as id_usuario_user_sucursal,
                                                user_sucursals.id_sucursal as id_sucursal_user_sucursal,
                                                user_sucursals.estado as estado_user_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.direccion as direccion_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal,
                                                users.name as nombre_usuario,
                                                users.usertype_id as tipo_usuario')
                                       ->join('sucursals','sucursals.id','user_sucursals.id_sucursal')
                                       ->join('users', 'users.id','user_sucursals.id_usuario')
                                       ->where('user_sucursals.id_usuario',auth()->user()->id)
                                       ->get();
        }

        $eventos = Evento::orderBy('fecha_evento','desc')->get();
        $productos = Producto::all();
        $tiposIngresosSalidas = TipoIngresoSalida::all();

        return view('inventarioExterno',[
            'inventariosExternos' => $inventariosExternos,
            'sucursales'=>$sucursales,
            'productos' => $productos,
            'tiposIngresosSalidas' => $tiposIngresosSalidas,
            'eventos' => $eventos,
            ]);
    }

    public function listarInventraio(Request $request)
    {
        $inventariosExternos = InventarioExterno::selectRaw('inventario_externos.id as id_inventario_externos,
                                                            inventario_externos.cantidad as cantidad_inventario_externos,
                                                            inventario_externos.activo as estado_inventario_externos,
                                                            inventario_externos.created_at as created_at_inventario_externos,
                                                            inventario_externos.updated_at as updated_at_inventario_externos,
                                                            productos.id as id_productos,
                                                            productos.nombre as nombre_productos,
                                                            productos.precio as precio_productos,
                                                            productos.talla as talla_productos,
                                                            productos.estado as estado_productos,
                                                            sucursals.id as id_sucursals,
                                                            sucursals.razon_social as razon_social_sucursals,
                                                            sucursals.direccion as direccion_sucursals,
                                                            sucursals.ciudad as ciudad_sucursals,
                                                            sucursals.activo as estado_sucursals,
                                                            users.id as id_users,
                                                            users.name as nombre_users,
                                                            eventos.id as id_eventos,
                                                            eventos.nombre as nombre_eventos,
                                                            eventos.fecha_evento,
                                                            eventos.estado as estado_eventos,
                                                            tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                                            tipo_ingreso_salidas.tipo as tipo_tipo_ingreso_salidas,
                                                            tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas')
                                                    ->join('productos','productos.id','inventario_externos.id_producto')
                                                    ->join('sucursals','sucursals.id','inventario_externos.id_sucursal')
                                                    ->join('users','users.id','inventario_externos.id_usuario')
                                                    ->join('eventos','eventos.id','inventario_externos.id_evento')
                                                    ->join('tipo_ingreso_salidas','tipo_ingreso_salidas.id','inventario_externos.id_tipo_ingreso_salida')
                                                    ->where('eventos.id',$request->id_evento)
                                                    ->orderBy('inventario_externos.updated_at','desc')
                                                    ->paginate(10);
        
        if (auth()->user()->usertype_id == 1) {
            $sucursales = Sucursal::selectRaw('sucursals.id as id_sucursal_user_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.direccion as direccion_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal')
                                    // ->where('sucursals.activo',1)
                                    ->get();
        } else {
            $sucursales = UserSucursal::selectRaw('user_sucursals.id as id_user_sucursal,
                                                user_sucursals.id_usuario as id_usuario_user_sucursal,
                                                user_sucursals.id_sucursal as id_sucursal_user_sucursal,
                                                user_sucursals.estado as estado_user_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.direccion as direccion_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal,
                                                users.name as nombre_usuario,
                                                users.usertype_id as tipo_usuario')
                                       ->join('sucursals','sucursals.id','user_sucursals.id_sucursal')
                                       ->join('users', 'users.id','user_sucursals.id_usuario')
                                       ->where('user_sucursals.id_usuario',auth()->user()->id)
                                       ->get();
        }

        $productos = Producto::all();
        $eventos = Evento::orderBy('fecha_evento','desc')->get();
        $tiposIngresosSalidas = TipoIngresoSalida::all();
        
        return view('inventarioExterno',[
            'inventariosExternos' => $inventariosExternos,
            'sucursales'=>$sucursales,
            'productos' => $productos,
            'tiposIngresosSalidas' => $tiposIngresosSalidas,
            'eventos' => $eventos,
            'id_evento'=>$request->id_evento]);
    }
    
    /**
     * Enviar datos para la funcion buscar y id_sucursal 
     */
    public function buscar(Request $request)
    {
        if ($request->buscar != '') 
        {
            $inventariosExternos = InventarioExterno::selectRaw('inventario_externos.id as id_inventario_externos,
                                                                inventario_externos.cantidad as cantidad_inventario_externos,
                                                                inventario_externos.activo as estado_inventario_externos,
                                                                inventario_externos.created_at as created_at_inventario_externos,
                                                                inventario_externos.updated_at as updated_at_inventario_externos,
                                                                productos.id as id_productos,
                                                                productos.nombre as nombre_productos,
                                                                productos.precio as precio_productos,
                                                                productos.talla as talla_productos,
                                                                productos.estado as estado_productos,
                                                                sucursals.id as id_sucursals,
                                                                sucursals.razon_social as razon_social_sucursals,
                                                                sucursals.direccion as direccion_sucursals,
                                                                sucursals.ciudad as ciudad_sucursals,
                                                                sucursals.activo as estado_sucursals,
                                                                users.id as id_users,
                                                                users.name as nombre_users,
                                                                eventos.id as id_eventos,
                                                                eventos.nombre as nombre_eventos,
                                                                eventos.fecha_evento,
                                                                eventos.estado as estado_eventos,
                                                                tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                                                tipo_ingreso_salidas.tipo as tipo_tipo_ingreso_salidas,
                                                                tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas')
                               ->join('productos','productos.id','inventario_externos.id_producto')
                               ->join('sucursals','sucursals.id','inventario_externos.id_sucursal')
                               ->join('users','users.id','inventario_externos.id_usuario')
                               ->join('eventos','eventos.id','inventario_externos.id_evento')
                               ->join('tipo_ingreso_salidas','tipo_ingreso_salidas.id','inventario_externos.id_tipo_ingreso_salida')
                               ->where('eventos.id',$request->id_evento)
                               ->whereRaw("productos.nombre like '%".$request->buscar."%' or productos.precio like '%".$request->buscar."%' or productos.talla like '%".$request->buscar."%' or tipo_ingreso_salidas.tipo like '%".$request->buscar."%' or users.name like '%".$request->buscar."%'")
                               ->orderBy('inventario_externos.updated_at','desc')
                               ->paginate(10);
        }else {
            $inventariosExternos = InventarioExterno::selectRaw('inventario_externos.id as id_inventario_externos,
                                                                inventario_externos.cantidad as cantidad_inventario_externos,
                                                                inventario_externos.activo as estado_inventario_externos,
                                                                inventario_externos.created_at as created_at_inventario_externos,
                                                                inventario_externos.updated_at as updated_at_inventario_externos,
                                                                productos.id as id_productos,
                                                                productos.nombre as nombre_productos,
                                                                productos.precio as precio_productos,
                                                                productos.talla as talla_productos,
                                                                productos.estado as estado_productos,
                                                                sucursals.id as id_sucursals,
                                                                sucursals.razon_social as razon_social_sucursals,
                                                                sucursals.direccion as direccion_sucursals,
                                                                sucursals.ciudad as ciudad_sucursals,
                                                                sucursals.activo as estado_sucursals,
                                                                users.id as id_users,
                                                                users.name as nombre_users,
                                                                eventos.id as id_eventos,
                                                                eventos.nombre as nombre_eventos,
                                                                eventos.fecha_evento,
                                                                eventos.estado as estado_eventos,
                                                                tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
                                                                tipo_ingreso_salidas.tipo as tipo_tipo_ingreso_salidas,
                                                                tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas')
                               ->join('productos','productos.id','inventario_externos.id_producto')
                               ->join('sucursals','sucursals.id','inventario_externos.id_sucursal')
                               ->join('users','users.id','inventario_externos.id_usuario')
                               ->join('eventos','eventos.id','inventario_externos.id_evento')
                               ->join('tipo_ingreso_salidas','tipo_ingreso_salidas.id','inventario_externos.id_tipo_ingreso_salida')
                               ->where('eventos.id',$request->id_evento)
                               ->orderBy('inventario_externos.updated_at','desc')
                               ->paginate(10);
        }
        
        if (auth()->user()->usertype_id == 1) {
            $sucursales = Sucursal::selectRaw('sucursals.id as id_sucursal_user_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.direccion as direccion_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal')
                                    // ->where('sucursals.activo',1)
                                    ->get();
        } else {
            $sucursales = UserSucursal::selectRaw('user_sucursals.id as id_user_sucursal,
                                                user_sucursals.id_usuario as id_usuario_user_sucursal,
                                                user_sucursals.id_sucursal as id_sucursal_user_sucursal,
                                                user_sucursals.estado as estado_user_sucursal,
                                                sucursals.razon_social as razon_social_sucursal,
                                                sucursals.direccion as direccion_sucursal,
                                                sucursals.ciudad as ciudad_sucursal,
                                                sucursals.activo as estado_sucursal,
                                                users.name as nombre_usuario,
                                                users.usertype_id as tipo_usuario')
                                       ->join('sucursals','sucursals.id','user_sucursals.id_sucursal')
                                       ->join('users', 'users.id','user_sucursals.id_usuario')
                                       ->where('user_sucursals.id_usuario',auth()->user()->id)
                                       ->get();
        }

        $eventos = Evento::orderBy('fecha_evento','desc')->get();
        $productos = Producto::all();
        $tiposIngresosSalidas = TipoIngresoSalida::all();

        return view('inventarioExterno',
            ['inventariosExternos' => $inventariosExternos, 
             'sucursales'=>$sucursales,
             'productos' => $productos,
             'tiposIngresosSalidas' => $tiposIngresosSalidas,
             'eventos' => $eventos, 
             'id_evento'=>$request->id_evento,]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_evento' => 'required|numeric',
            'id_sucursal' => 'required|numeric',
            'id_producto' => 'required|numeric',
            'id_tipo_ingreso_salida' => 'required|numeric',
            'cantidad_salida' => 'required|numeric',
        ]);

        $registroProductoSucursal = InventarioInterno::where('id_producto',$request->id_producto)
                                                     ->where('id_sucursal',$request->id_sucursal)
                                                     ->get();
        
        $estado = 0;
        $mensaje = '';

        if(count($registroProductoSucursal)>=1)
        {
            $registroProductoSucursal = InventarioInterno::where('id_producto',$request->id_producto)
                                                         ->where('id_sucursal',$request->id_sucursal)
                                                         ->first();

            if ($registroProductoSucursal->stock >= $request->cantidad_salida) 
            {
                // Consultamos si este producto ya se encuentra en la tabla Inventario Externo
                $registroProductoEvento = InventarioExterno::where('id_producto',$request->id_producto)
                                            ->where('id_sucursal',$request->id_sucursal)
                                            ->where('id_evento',$request->id_evento)
                                            ->get();
                if (count($registroProductoEvento) >= 1) 
                {
                    // Si el caso es afirmativo, solo modificamos datos en vez de generar un nuevo registro
                    $registroProductoEvento = InventarioExterno::where('id_producto',$request->id_producto)
                                                                ->where('id_sucursal',$request->id_sucursal)
                                                                ->where('id_evento',$request->id_evento)
                                                                ->first();
                    
                    // Se esta restando del stock de la tabla de inventario interno
                    $registroProductoSucursal->stock = $registroProductoSucursal->stock - $request->cantidad_salida;

                    $registroProductoEvento->id_tipo_ingreso_salida = $request->id_tipo_ingreso_salida;
                    $registroProductoEvento->id_usuario = auth()->user()->id;
                    $registroProductoEvento->cantidad = $registroProductoEvento->cantidad + $request->cantidad_salida;
                    if ($registroProductoSucursal->save() && $registroProductoEvento->save()) {
                        $estado = 1;
                    }
                } else {
                    // Si el caso de que sea negativo, creamos un nuevo item para la tabla Inventario Externo
                    $nuevoProductoEvento = new InventarioExterno();
                    $nuevoProductoEvento->id_producto = $request->id_producto;
                    $nuevoProductoEvento->id_sucursal = $request->id_sucursal;
                    $nuevoProductoEvento->id_evento = $request->id_evento;
                    $nuevoProductoEvento->id_usuario = auth()->user()->id;
                    $nuevoProductoEvento->id_tipo_ingreso_salida = $request->id_tipo_ingreso_salida;
                    $nuevoProductoEvento->cantidad = $request->cantidad_salida;

                    // Se esta restando del stock de la tabla de inventario interno
                    $registroProductoSucursal->stock = $registroProductoSucursal->stock - $request->cantidad_salida;

                    if ($registroProductoSucursal->save() && $nuevoProductoEvento->save()) {
                        $estado = 1;
                    }
                }
            }else{
                $producto = Producto::where('id',$request->id_producto)->first();
                $mensaje = "El stock del producto $producto->nombre - $producto->talla es menor a la cantidad que desea sacar";    
            }
            
        }else{
            $producto = Producto::where('id',$request->id_producto)->first();
            $mensaje = "El producto $producto->nombre - $producto->talla no se encuentra en la sucursal que selecciono";
        }
        return redirect()->route('home_inventario_externo',[
            'exito'=>$estado,
            'mensaje'=>$mensaje,
            'id_evento'=>$request->id_evento,
        ]);
    }

    public function update(Request $request)
    {
        // $request->validate([
        //     'nombre' => 'required',
        //     'precio' => 'required',
        //     'talla' => 'required',
        // ]);

        // $actualizarProducto = Producto::where("id",$request->id)->first();
        // $actualizarProducto->nombre = $request->nombre;
        // $actualizarProducto->precio = $request->precio;
        // $actualizarProducto->talla = $request->talla;
        // if (isset($request->id_categoria)) 
        // {
        //     $actualizarProducto->id_categoria = $request->id_categoria;
        // }
        
        // $estado = 0;
        // if ($actualizarProducto->save()) {
        //     $estado = 1;
        // }

        // return redirect()->route('home_producto',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        
        switch ($request->estado) 
        {
            case 0:
                $itemInventarioExterno = InventarioExterno::where("id",$request->id)->first();
                $itemInventarioExterno->activo = 1;
            break;

            case 1:
                $itemInventarioExterno = InventarioExterno::where("id",$request->id)->first();
                $itemInventarioExterno->activo = 0;
            break;
            
            default:
                
            break;
        }
        $itemInventarioExterno->save();
    }

    public function exportPdfLista(Request $request)
    {
        dd($request);
    }
}
