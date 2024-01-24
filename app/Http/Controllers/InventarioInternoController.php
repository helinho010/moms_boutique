<?php

namespace App\Http\Controllers;

use App\Models\InventarioInterno;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\TipoIngresoSalida;
use App\Models\UserSucursal;
use Doctrine\DBAL\Schema\View;

class InventarioInternoController extends Controller
{
    public function index()
    {
        $inventariosInternos = InventarioInterno::where('updated_at','0000-00-00')->paginate(10);
    
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
        $tiposIngresosSalidas = TipoIngresoSalida::all();
        return view('inventarioInterno',[
            'inventariosInternos' => $inventariosInternos,
            'sucursales'=>$sucursales,
            'productos' => $productos,
            'tiposIngresosSalidas' => $tiposIngresosSalidas,
            ]);
    }

    public function listarInventraio(Request $request)
    {
        if (isset($request->id_sucursal)) 
        {
            $inventariosInternos = InventarioInterno::selectRaw('inventario_internos.id as id_inventario_interno,
                                                    inventario_internos.stock,
                                                    inventario_internos.cantidad_ingreso,
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
                               ->orderBy('updated_at_inventario_interno','desc')
                               ->paginate(10);    
        } else {
            $inventariosInternos = InventarioInterno::where('updated_at','0000-00-00')->paginate(10);
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

        $productos = Producto::all();
        $tiposIngresosSalidas = TipoIngresoSalida::all();
        
        return view('inventarioInterno',[
            'inventariosInternos' => $inventariosInternos,
            'sucursales'=>$sucursales,
            'productos' => $productos,
            'tiposIngresosSalidas' => $tiposIngresosSalidas,
            'id_sucursal'=>$request->id_sucursal]);
    }
    
    /**
     * Enviar datos para la funcion buscar y id_sucursal 
     */
    public function buscar(Request $request)
    {
        if ($request->buscar != '') 
        {
            $inventariosInternos = InventarioInterno::selectRaw('inventario_internos.id as id_inventario_interno,
                                                    inventario_internos.stock,
                                                    inventario_internos.cantidad_ingreso,
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
                               ->whereRaw("productos.nombre like '%".$request->buscar."%' or productos.precio like '%".$request->buscar."%' or productos.talla like '%".$request->buscar."%' or tipo_ingreso_salidas.tipo like '%".$request->buscar."%' or users.name like '%".$request->buscar."%'")
                               ->orderBy('updated_at_inventario_interno','desc')
                               ->paginate(10);
        }else {
            $inventariosInternos = InventarioInterno::selectRaw('inventario_internos.id as id_inventario_interno,
                                                    inventario_internos.stock,
                                                    inventario_internos.cantidad_ingreso,
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
                               ->orderBy('updated_at_inventario_interno','desc')
                               ->paginate(10);
        }
        // dd($inventariosInternos);
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
        $tiposIngresosSalidas = TipoIngresoSalida::all();
        
        return view('inventarioInterno',[
                    'inventariosInternos' => $inventariosInternos, 
                    'sucursales'=>$sucursales, 
                    'productos' => $productos,
                    'tiposIngresosSalidas' => $tiposIngresosSalidas,
                    'id_sucursal'=>$request->id_sucursal
                ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sucursal' => 'required|numeric',
            'id_producto' => 'required|numeric',
            'id_tipo_ingreso_salida' => 'required|numeric',
            'cantidad_ingreso' => 'required|numeric',
        ]);

        $buscarRegistroProducto = InventarioInterno::where('id_producto',$request->id_producto)
                                            ->where('id_sucursal',$request->id_sucursal)
                                            //->where('id_tipo_ingreso_salida',$request->id_tipo_ingreso_salida)
                                            ->get();
        
        $estado = 0;
        if(count($buscarRegistroProducto)>=1){
            $buscarRegistroProducto = InventarioInterno::where('id_producto',$request->id_producto)
                                            ->where('id_sucursal',$request->id_sucursal)
                                            //->where('id_tipo_ingreso_salida',$request->id_tipo_ingreso_salida)
                                            ->first();
            $buscarRegistroProducto->cantidad_ingreso = $request->cantidad_ingreso;
            $buscarRegistroProducto->stock = $buscarRegistroProducto->stock + $request->cantidad_ingreso;
            $buscarRegistroProducto->id_tipo_ingreso_salida = $request->id_tipo_ingreso_salida;
            $buscarRegistroProducto->id_usuario = auth()->user()->id;
            if ($buscarRegistroProducto->save()) {
                $estado = 1;
            }
        }else{
            $nuevoItemInventarioInterno = new InventarioInterno();
            $nuevoItemInventarioInterno->id_producto = $request->id_producto;
            $nuevoItemInventarioInterno->id_sucursal = $request->id_sucursal;
            $nuevoItemInventarioInterno->id_usuario = auth()->user()->id;
            $nuevoItemInventarioInterno->id_tipo_ingreso_salida = $request->id_tipo_ingreso_salida;
            $nuevoItemInventarioInterno->cantidad_ingreso = $request->cantidad_ingreso;
            $nuevoItemInventarioInterno->stock = $request->cantidad_ingreso;
            if ($nuevoItemInventarioInterno->save()) {
                $estado = 1;
            }
        }
        return redirect()->route('home_inventario_interno',['exito'=>$estado]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required',
            'talla' => 'required',
        ]);

        $actualizarProducto = Producto::where("id",$request->id)->first();
        $actualizarProducto->nombre = $request->nombre;
        $actualizarProducto->precio = $request->precio;
        $actualizarProducto->talla = $request->talla;
        if (isset($request->id_categoria)) 
        {
            $actualizarProducto->id_categoria = $request->id_categoria;
        }
        
        $estado = 0;
        if ($actualizarProducto->save()) {
            $estado = 1;
        }

        return redirect()->route('home_producto',['actualizado'=>$estado]);
    }

    public function update_estado(Request $request)
    {
        switch ($request->estado) 
        {
            case 0:
                $itemInventarioInterno = InventarioInterno::where("id",$request->id)->first();
                $itemInventarioInterno->estado = 1;
            break;

            case 1:
                $itemInventarioInterno = InventarioInterno::where("id",$request->id)->first();
                $itemInventarioInterno->estado = 0;
            break;
            
            default:
                
            break;
        }
        $itemInventarioInterno->save();
    }
}
