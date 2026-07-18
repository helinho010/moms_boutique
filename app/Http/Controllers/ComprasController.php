<?php

namespace App\Http\Controllers;

use App\Models\Compras;
use App\Models\InventarioInterno;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\UserSucursal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\InventarioSucursal;
use Barryvdh\DomPDF\Facade\Pdf;

class ComprasController extends Controller
{
    // public function index(Request $request)
    // {
    //     $validacion = $request->validate([
    //         "buscar" => "string|nullable|max:50",
    //         "id_sucursal" => "nullable|exists:sucursals,id",
    //     ]);

    //     if($request->has('id_sucursal') && $request->input('id_sucursal') != null){
            
    //         $idSucursal = $request->input('id_sucursal');

    //         if ($request->has('buscar') && $request->input('buscar') != null) {

    //             $buscar = $request->input('buscar');
    //             $compras = Compras::compras(null, $buscar, $idSucursal)->paginate(10)->appends(['buscar' => $buscar]);
    //             session()->flash('mensaje-errores', "Buscar verdad");
    //         }else{

    //             $compras = Compras::compras(null, null, $idSucursal)->paginate(10)->appends(['id_sucursal' => $idSucursal]);
    //             session()->flash('mensaje-errores', "Buscar falso");
    //         }

    //     }else{
    //         $compras = Compras::whereNull('id')->paginate(10);
    //         session()->flash('mensaje-errores', "id_sucursal no existe");
    //     }

    //     return view('compras.index', [
    //         'compras' => $compras,
    //         'sucursales' => UserSucursal::sucursalesHabilitadasUsuario(auth()->id()),
    //         'id_sucursal' => $request->input('id_sucursal') ? $request->input('id_sucursal') : null,
    //         'buscar' => $request->input('buscar') ? $request->input('buscar') : null,
    //     ]);
    // }

    public function index(Request $request)
    {
        $validacion = $request->validate([
            "buscar" => "string|nullable|max:50",
            "id_sucursal" => "nullable|exists:sucursals,id",
        ]);

        $idSucursal = $request->input('id_sucursal');
        $buscar = $request->input('buscar');

        if ($idSucursal) {
            // Si hay sucursal, obtener compras filtradas
            if ($buscar) {
                $compras = Compras::compras(null, $buscar, $idSucursal)->paginate(10)->withQueryString(); //appends(['buscar' => $buscar, 'id_sucursal' => $idSucursal]);
            } else {
                $compras = Compras::compras(null, null, $idSucursal)->paginate(10)->withQueryString();//appends(['id_sucursal' => $idSucursal]);
            }
        } else {
            // Si no hay sucursal, mostrar todas las compras o vacío según tu lógica
            // En lugar de whereNull('id'), podrías mostrar todas las compras
            $compras = Compras::wherenull('id')->paginate(10);
        }

        return view('compras.index', [
            'compras' => $compras,
            'sucursales' => UserSucursal::sucursalesHabilitadasUsuario(auth()->id()),
            'id_sucursal' => $idSucursal,
            'buscar' => $buscar,
        ]);
    }

    public function create()
    {
        $productos = Producto::where("estado", 1)->get();

        return view('compras.create', [
            'productos' => $productos,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo_compra' => 'required|string|max:20',
            'id_sucursal' => 'required|exists:sucursals,id',
            'total_compra' => 'required|numeric',
            'presupuesto' => 'nullable|numeric',
            'sobrante' => 'nullable|numeric',
            'observacion' => 'nullable|string',
            'id_usuario_creador' => 'required|exists:users,id',
        ]);

        // Aquí se guardaría la compra en la base de datos

        return redirect()->route('home_compras')->with('mensaje-exito', 'Compra guardada exitosamente.');
    }

    public function editar($id)
    {
        $compra = Compras::compras($id)->get();
        $detalleCompra = Compras::find($id)->detalleproductos;
        $productos = Producto::all();

        return view('compras.edit', [
            'compra' => $compra,
            'detalleCompra' => $detalleCompra,
            'totalCompra' => $detalleCompra->sum('sub_total'),
            'productos' => $productos,
        ]);
    }

    public function aprobar($id)
    {

        $compra = Compras::compras($id)->get();
        $detalleCompra = Compras::find($id)->detalleproductos;

        return view('compras.aprobar', [
            'compra' => $compra,
            'detalleCompra' => $detalleCompra,
            'totalCompra' => $detalleCompra->sum('sub_total'),
        ]);
    }

    public function aprobarCompraPost(Request $request)
    {
        $idCompra = $request->input('id_compra');
        $compra = Compras::findOrFail($idCompra);
        $compra->estado_aprobacion = 1; // 1: Aprobada
        $compra->id_usuario_aprobador = auth()->id();
        $compra->fecha_aprobacion = now();
        $compra->save();

        return redirect()->route('home_compras')->with('mensaje-exito', 'Compra aprobada exitosamente.');
    }


    public function rechazarCompra(Request $request)
    {
        // $idCompra = $request->input('id_compra');
        // $compra = Compras::findOrFail($idCompra);
        // $compra->estado_aprobacion = 2; // 2: Rechazada
        // $compra->id_usuario_aprobador = auth()->id();
        // $compra->fecha_aprobacion = now();
        // $compra->save();

        // return redirect()->route('home_compras')->with('mensaje-exito', 'Compra rechazada exitosamente.');
    }

    public function enviarCompraBodega(Request $request)
    {
        $idCompra = $request->input('id_compra');
        $compra = Compras::findOrFail($idCompra);
        $compra->estado_aprobacion = 3; // 3: Enviada a Bodega
        $compra->id_usuario_envio_bd = auth()->id();
        $compra->fecha_envio_productos_bd = now();
        $compra->save();

        $detalleCompra = $compra->detalleproductos;

        foreach ($detalleCompra as $detalle) {
            $productoInventarioSucursal = InventarioInterno::where('id_sucursal', $compra->id_sucursal_destino)
                ->where('id_producto', $detalle->id_producto)
                ->first();

            if ($productoInventarioSucursal) {
                $productoInventarioSucursal->cantidad_ingreso = $detalle->cantidad;
                $productoInventarioSucursal->stock += $detalle->cantidad;
                $productoInventarioSucursal->save();
            } else {
                InventarioInterno::create([
                    'id_producto' => $detalle->id_producto,
                    'id_sucursal' => $compra->id_sucursal_destino,
                    'id_usuario' => auth()->user()->id,
                    'id_tipo_ingreso_salida' => 4, // 4: Ingreso por compra
                    'cantidad_ingreso' => $detalle->cantidad,
                    'stock' => $detalle->cantidad,
                ]);
            }    
        }
    
        return redirect()->route('home_compras')->with('mensaje-exito', 'Productos comprados enviados al Inventario exitosamente.');
    }

    public function destroy($id)
    {
        if ($id >= 0) {
           $compra = Compras::findOrFail($id);
           $compra->delete();
           return redirect()->route('home_compras')->with('mensaje-exito', 'Compra eliminada exitosamente ' . $compra->codigo_compra . ".");
        }
    }


    public function detalleCompra($id_compra)
    {
        $compra = Compras::compras($id_compra)->get();
        $detalleCompra = Compras::find($id_compra)->detalleproductos;

        return json_encode([
            'compra' => $compra,
            'detalleCompra' => $detalleCompra,
            'totalCompra' => $detalleCompra->sum('sub_total'),
        ]);
    }

    public function exportarCompraPdf(Request $request)
    {
    
        $idCompra = $request->input('id_compra');
        $compra = Compras::compras($idCompra)->get();
        $detalleCompra = Compras::find($idCompra)->detalleproductos;

        $pdf = Pdf::loadView('pdf.compra', [
            'compra' => $compra[0],
            'detalleCompra' => $detalleCompra,
            'totalCompra' => $detalleCompra->sum('sub_total'),
            'tituloPdf' => 'Compra - ' . $compra[0]->codigo_compra,
            'fechaCompra' => $compra[0]->fecha_compra->format('d/m/Y'),
        ]);

        return $pdf->download($compra[0]->codigo_compra.'.pdf');

    }

    public function exportarCompraExcel(Request $request)
    {
        $idCompra = $request->input('id_compra');
        $compra = Compras::compras($idCompra)->get();
        $detalleCompra = Compras::find($idCompra)->detalleproductos;

        // Aquí puedes implementar la lógica para exportar a Excel
        // Por ejemplo, usando Laravel Excel o cualquier otra librería que prefieras

        return response()->json([
            'message' => 'Exportación a Excel no implementada aún.',
            'compra' => $compra,
            'detalleCompra' => $detalleCompra,
        ]);
    }


}
