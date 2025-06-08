<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\InventarioExterno;
use App\Models\Producto;
use App\Models\InventarioInterno;
use App\Models\Sucursal;
use App\Models\TipoIngresoSalida;
use App\Models\TipoPago;
use App\Models\UserSucursal;
use App\Models\UsuarioEvento;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Log;
use Svg\Tag\Rect;
use Barryvdh\DomPDF\Facade\Pdf;

use function Laravel\Prompts\error;

class InventarioExternoController extends Controller
{
    public $nombre_archivo = '';

    public function index(Request $request)
    {
        $validar = $request->validate([
            'id_evento' => 'integer',
            'buscar' => 'nullable|string|max:50',
        ]);

        if (isset($request->buscar)) 
        {
            $inventario = inventarioExterno::buscar($request->buscar, $request->id_evento)
                                           ->paginate(10)
                                           ->withQueryString();   
        }else{
            $inventario = inventarioExterno::inventarioXEvento($request->id_evento)
                                           ->paginate(10)
                                           ->withQueryString();
        }
        
        $eventos = UsuarioEvento::eventosHabilitadosUsuario(auth()->user()->id);

        $sucursales = UserSucursal::sucursalesHabilitadasUsuario(auth()->user()->id);

        $productos = Producto::where('estado',1)->get();
        
        $tiposIngresosSalidas = TipoIngresoSalida::where('estado',1)->get();

        return view('inventarioExterno.inventarioExterno',[
            'inventario' => $inventario,
            'sucursales'=>$sucursales,
            'productos' => $productos,
            'tiposIngresosSalidas' => $tiposIngresosSalidas,
            'eventos' => $eventos,
            'id_evento' => isset($request->id_evento) ? $request->id_evento : null,
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

            $eventos = Evento::where('estado',1)
                             ->orderBy('fecha_evento','desc')
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
            $eventos = UsuarioEvento::selectRaw('
                                                 eventos.*
                                                ')
                                    ->join('eventos', 'eventos.id', 'user_evento.id_evento')
                                    ->where('eventos.estado',1)
                                    ->where('user_evento.id_usuario', auth()->user()->id)
                                    ->orderBy('fecha_evento','desc')
                                    ->get();
        }

        $productos = Producto::all();
        
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
            $eventos = Evento::where('estado',1)
                             ->orderBy('fecha_evento','desc')
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
            
            $eventos = UsuarioEvento::selectRaw('
                                                 eventos.*
                                                ')
                                    ->join('eventos', 'eventos.id', 'user_evento.id_evento')
                                    ->where('eventos.estado',1)
                                    ->where('user_evento.id_usuario', auth()->user()->id)
                                    ->orderBy('fecha_evento','desc')
                                    ->get();
        }

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
            'id_evento' => 'required|integer|exists:eventos,id',
            'id_sucursal' => 'required|integer|exists:sucursals,id',
            'id_producto' => 'required|integer|exists:productos,id',
            'id_tipo_ingreso_salida' => 'required|integer|exists:tipo_ingreso_salidas,id',
            'cantidad_salida' => 'required|integer|min:1',
        ]);

        $itemInventarioInterno = InventarioInterno::where('id_producto',$request->id_producto)
                                                    ->where('id_sucursal',$request->id_sucursal)
                                                    ->first();
        if ($itemInventarioInterno) {

            if($itemInventarioInterno->stock - $request->cantidad_salida < 0 ){

                return redirect()->route('home_inventario_externo', ['id_evento'=>$request->id_evento])->with('error', 'No hay suficiente stock en la sucursal seleccionada');
            }

        }else{

            return redirect()->route('home_inventario_externo', ['id_evento'=>$request->id_evento])->with('error', 'Error en la seleccionar el item de la sucursal seleccionada');           
        }

        try {
            
            DB::transaction(function() use ($request, $itemInventarioInterno){

                $itemInventarioInterno->stock -= $request->cantidad_salida;
                $itemInventarioInterno->save();

                $registroExterno = InventarioExterno::firstOrNew([
                    'id_sucursal' => $request->id_sucursal,
                    'id_producto' => $request->id_producto,
                    'id_evento' => $request->id_evento,
                ]);
                
                $cantidadActual = $registroExterno->exists ? $registroExterno->cantidad : 0;
                
                $registroExterno->fill([
                    'id_usuario' => auth()->user()->id,
                    'id_tipo_ingreso_salida' => $request->id_tipo_ingreso_salida,
                    'cantidad' => $cantidadActual + $request->cantidad_salida
                ])->save();

            });

            return redirect()->route('home_inventario_externo', ['id_evento' => $request->id_evento])->with('exito', 'El item fue agregado correctamente');

        } catch (\Throwable $th) {

            Log::error('Error en transacción de inventario: '.$th->getMessage().' - Linea: '.$th->getLine().' - Archivo: '.$th->getFile());
            return redirect()->route('home_inventario_externo', ['id_evento' => $request->id_evento])->with('error', 'Error al agregar el item');
        }

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

    public function elimiarItemInventarioInterno(Request $request)
    {
        
        $validar = $request->validate([
            'id_inventario_externo' => 'required|integer|exists:inventario_externos,id',
        ]);
        
        try {

            $item = InventarioExterno::where('id', $request->id_inventario_externo)->first();
            
            DB::transaction(function() use ($item){
                
                $registroInventarioInterno = InventarioInterno::where('id_sucursal',$item->id_sucursal)
                                                    ->where('id_producto',$item->id_producto) 
                                                     ->first();

                $registroInventarioInterno->stock += $item->cantidad;
                $registroInventarioInterno->save();

                $item->delete();
            });
            
            return redirect()->route('home_inventario_externo',['id_evento'=>$request->id_evento])->with('itemEliminadoInventarioExternoCorrectamente', "El item del inventario externo fue eliminado correctamente");

        } catch (\Throwable $th) {
            return redirect()->route('home_inventario_externo',['id_evento'=>$request->id_evento])->with('itemEliminadoInventarioExternoError', "Error al eliminar el item del inventario externo");
        }
    }

    public function exportarInventarioExternoPdf(Request $request)
    {

        $validar = $request->validate([
            'id_evento' => 'required|integer|exists:eventos,id',
        ]);

        $evento = Evento::findOrFail($request->id_evento);

        $inventario = InventarioExterno::inventarioXEvento($request->id_evento)->get();

        $pdf = Pdf::loadView('pdf.inventarioExterno', [
            'tituloPdf' => 'Inventario Externo', 
            'fechaCorte' => date('d-m-Y'),
            'evento' => $evento,
            'inventario' => $inventario,
        ]);

        return $pdf->download('inventario_externo'.'.pdf');
    }


    public function retornarProductos(Request $request)
    {

        $validar = $request->validate([
            'id_evento' => 'required|integer|exists:eventos,id',
        ]);
        

        $almacenCentral = Sucursal::almacenCentral();

        if(!isset($almacenCentral)){
            return redirect()->route('home_inventario_externo',['id_evento'=>$request->id_evento])->with('error', 'Aun no se tiene un almacen central registrado');
        }

        $inventario = InventarioExterno::where('id_evento', $request->id_evento)
                                                ->where('activo', 1)
                                                ->get();
        
        $tipoIngresoSalida = TipoIngresoSalida::where('tipo', 'Traspaso')->first();

        try {

            foreach ($inventario as $item) {
                if ($item->activo == 1) 
                {
                    $registroInventarioInterno = InventarioInterno::firstOrNew([
                        'id_sucursal' => $item->id_sucursal,
                        'id_producto' => $item->id_producto,
                        'estado' => 1,
                    ]);

                    $stockActual = $registroInventarioInterno->exists ? $registroInventarioInterno->stock : 0;

                    $registroInventarioInterno->fill([
                        'id_usuario' => auth()->user()->id,
                        'id_tipo_ingreso_salida' => $tipoIngresoSalida->id,
                        'cantidad_ingreso' => $item->cantidad,
                        'stock' => $stockActual + $item->cantidad,
                    ])->save();
    
                    $item->update(['activo' => 3]);
                }
            }

            return redirect()->route('home_inventario_externo',['id_evento'=>$request->id_evento])->with('exito', 'Los productos fueron retornados correctamente');

        } catch (\Throwable $th) {
            
            return redirect()->route('home_inventario_externo',['id_evento'=>$request->id_evento])->with('error', 'Error al retornar los productos: '. $th->getMessage() . $th->getLine() . $th->getFile());
        }

    }


    public function seleccionEventoVenta(Request $request)
    {

        $evento = Evento::where('id',$request->id_evento)->get();

        $tipoPagos = TipoPago::where('estado',1)->get();

        // $productosEvento = InventarioExterno::selectRaw(' inventario_externos.id as                 id_inventario_externos,
        //                                                     inventario_externos.cantidad as cantidad_inventario_externos,
        //                                                     inventario_externos.activo as estado_inventario_externos,
        //                                                     inventario_externos.created_at as created_at_inventario_externos,
        //                                                     inventario_externos.updated_at as updated_at_inventario_externos,
        //                                                     productos.id as id_productos,
        //                                                     productos.nombre as nombre_productos,
        //                                                     productos.costo as costo_productos,
        //                                                     productos.talla as talla_productos,
        //                                                     productos.estado as estado_productos,
        //                                                     sucursals.id as id_sucursals,
        //                                                     sucursals.razon_social as razon_social_sucursals,
        //                                                     sucursals.direccion as direccion_sucursals,
        //                                                     sucursals.ciudad as ciudad_sucursals,
        //                                                     sucursals.activo as estado_sucursals,
        //                                                     users.id as id_users,
        //                                                     users.name as name_users,
        //                                                     users.estado as estado_users,
        //                                                     eventos.id as id_eventos,
        //                                                     eventos.nombre as nombre_eventos,
        //                                                     eventos.estado as estado_eventos,
        //                                                     tipo_ingreso_salidas.id as id_tipo_ingreso_salidas,
        //                                                     tipo_ingreso_salidas.tipo as tipo_tipo_ingreso_salidas,
        //                                                     tipo_ingreso_salidas.estado as estado_tipo_ingreso_salidas')
        //                                         ->join('productos', 'productos.id','inventario_externos.id_producto')
        //                                         ->join('sucursals', 'sucursals.id', 'inventario_externos.id_sucursal')
        //                                         ->join('users', 'users.id', 'inventario_externos.id_usuario')
        //                                         ->join('eventos', 'eventos.id', 'inventario_externos.id_evento')
        //                                         ->join('tipo_ingreso_salidas', 'tipo_ingreso_salidas.id', 'inventario_externos.id_tipo_ingreso_salida')
        //                                         ->where('eventos.id', $request->id_evento)
        //                                         ->get();

        $productos = InventarioExterno::inventarioXEvento($request->id_evento)->get();
        
        session(['eventoSeleccionadoParaVenta' => $request->id_evento]); 

        return view('Venta.ventaEvento',[
            'evento'=>$evento,
            'productos' => $productos,
            'tipoPagos' => $tipoPagos,
        ]);   
    }


}
