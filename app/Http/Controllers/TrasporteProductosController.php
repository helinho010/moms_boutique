<?php

namespace App\Http\Controllers;

use App\Models\InventarioInterno;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\TipoIngresoSalida;
use App\Models\TrasporteProductos;
use App\Models\UserSucursal;
use App\View\Components\Formulario\input;
use DragonCode\Contracts\Cashier\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;

class TrasporteProductosController extends Controller
{

    public function index(Request $request)
    {
        $validate = $request->validate([
            'buscar' => 'string|nullable|max:100',
        ]);

        if (isset($request->buscar)) {
            $traspasos = TrasporteProductos::buscar($request->buscar)
                                           ->paginate(10)->withQueryString();
        }else{
            $traspasos = TrasporteProductos::todosTraspaso()
                                           ->paginate(10);
        }
        
        $sucursales = UserSucursal::sucursalesHabilitadasUsuario(auth()->user()->id);

        $sucursalesDestino = Sucursal::where('activo',1)
                                     ->get();

        $tipoIngresoSalida = TipoIngresoSalida::where('estado',1)->get();


        return view('traspasoProductos.index',[
            'traspasos' => $traspasos,
            'sucursales' => $sucursales,
            'sucursalesDestino' => $sucursalesDestino,
            'tipoSalida' => $tipoIngresoSalida,
        ]);
    }


    public function store(Request $request)
    {
        $validate = $request->validate([                              
            'id_sucursal' => 'required|integer|exists:sucursals,id',
            'id_producto' => 'required|integer|exists:productos,id',
            'id_sucursal_destino' => 'required|integer|exists:sucursals,id',
            'id_tipo_salida' => 'required|integer|exists:tipo_ingreso_salidas,id',
            'cantidad' => 'required|integer',
        ]);
        
        try {
            $registroInvetarioSucursalOrigen = InventarioInterno::where('id_sucursal',$request->id_sucursal)
                                                      ->where('id_producto',$request->id_producto)
                                                      ->first();
        
        if ($registroInvetarioSucursalOrigen->stock >= $request->cantidad) 
        {
            $nuevoTraspasoProductos = new TrasporteProductos();
            $nuevoTraspasoProductos->id_sucursal_origen = $request->id_sucursal;
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

            $registroInvetarioSucursalDestino = InventarioInterno::where('id_sucursal',$request->id_sucursal_destino)
                                                                ->where('id_producto',$request->id_producto);
                                                                
            
            if ($registroInvetarioSucursalDestino->get()->count() == 1) 
            {
                $registroInvetarioSucursalDestinoRegistro = $registroInvetarioSucursalDestino->first();
                $registroInvetarioSucursalDestinoRegistro->stock =  $registroInvetarioSucursalDestinoRegistro->stock + intval($request->cantidad);
                $registroInvetarioSucursalDestinoRegistro->id_usuario = auth()->user()->id;
                $registroInvetarioSucursalDestinoRegistro->id_tipo_ingreso_salida = $request->id_tipo_salida;
                $registroInvetarioSucursalDestinoRegistro->cantidad_ingreso = $request->cantidad;
                $registroInvetarioSucursalDestinoRegistro->save();
            }else{
                $registroInvetarioSucursalDestinoRegistro =new InventarioInterno();
                $registroInvetarioSucursalDestinoRegistro->id_producto = $request->id_producto;
                $registroInvetarioSucursalDestinoRegistro->id_sucursal = $request->id_sucursal_destino;
                $registroInvetarioSucursalDestinoRegistro->id_usuario = auth()->user()->id;
                $registroInvetarioSucursalDestinoRegistro->id_tipo_ingreso_salida = $request->id_tipo_salida;
                $registroInvetarioSucursalDestinoRegistro->cantidad_ingreso = $request->cantidad;
                $registroInvetarioSucursalDestinoRegistro->stock = $request->cantidad;
                $registroInvetarioSucursalDestinoRegistro->save();
            }
            
        }
        return redirect()->route('home_traspaso_productos')->with('exito', 'Traspaso de productos registrado correctamente.');

        } catch (\Throwable $th) {
            return redirect()->route('home_traspaso_productos')->with('error', 'Error al registrar el traspaso de productos: ' . $th->getMessage());
        }        
    } 

    public function traspasoProductosFormularioPdf(Request $request)
    {
        $validate = $request->validate([                              
            'origen_sucursal_traspaso_productos' => 'required|integer|exists:sucursals,id',
            'destino_sucursal_traspaso_productos' => 'required|integer|exists:sucursals,id',
            'fecha_form_traspaso_productos_pdf' => 'required|date'
        ]);

        $sucursalOrigen = Sucursal::findOrFail($request->origen_sucursal_traspaso_productos);
        
        $sucursalDestino = Sucursal::findOrFail($request->destino_sucursal_traspaso_productos);
        
        $traspasos = TrasporteProductos::traspasoXSucursal($request->origen_sucursal_traspaso_productos)->get();
         
        $pdf = Pdf::loadView('pdf.traspasoProductos', [
               'sucursalOrigen' => $sucursalOrigen,
               'sucursalDestino' => $sucursalDestino,
               'titulo' => 'Traspaso de Productos',
               'fecha' => $request->fecha_form_traspaso_productos_pdf,
               'traspasos' => $traspasos,     
         ]);

         return $pdf->download("traspasoProductos_$request->fecha_form_traspaso_productos_pdf.pdf");
    }
}
