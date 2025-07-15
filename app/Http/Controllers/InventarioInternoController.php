<?php

namespace App\Http\Controllers;

use App\Exports\InventarioInternoExport;
use App\Models\InventarioInterno;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\TipoIngresoSalida;
use App\Models\UserSucursal;
use Doctrine\DBAL\Schema\View;
// use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LaravelLang\Publisher\Console\Reset;
use Maatwebsite\Excel\Facades\Excel;
use Svg\Tag\Rect;
use Barryvdh\DomPDF\Facade\Pdf;

use function PHPSTORM_META\type;

class InventarioInternoController extends Controller
{
    public function index(Request $request)
    {
        $validar = $request->validate([
            'buscar' => 'nullable|string',
            'id_sucursal' => 'numeric',
        ]);

        if (isset($request->buscar)) 
        {
            $inventario = InventarioInterno::buscar($request->id_sucursal, $request->buscar)->withQueryString();
        }else{
            $inventario = InventarioInterno::inventarioXSucurusal($request->id_sucursal)
                                           ->paginate(10)
                                           ->withQueryString();
        }
    
        $sucursales = UserSucursal::sucursalesHabilitadasUsuario(auth()->user()->id);

        $productos = Producto::all();

        $tiposIngresosSalidas = TipoIngresoSalida::all();

        return view('inventarioInterno.inventarioInterno',[
            'inventario' => $inventario,
            'sucursales'=>$sucursales,
            'productos' => $productos,
            'tipoIngresoSalidas' => $tiposIngresosSalidas,
            'id_sucursal'=> isset($request->id_sucursal) ? $request->id_sucursal : null,
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sucursal' => 'required|integer',
            'id_producto' => 'required|integer',
            'id_tipo_ingreso_salida' => 'required|integer',
            'cantidad_ingreso' => 'required|integer',
        ]);

        try {
            
            $registroBuscado = InventarioInterno::where(
                [
                    'id_producto' => $request->id_producto, 
                    'id_sucursal' => $request->id_sucursal
                ])->first();
            
            if ($registroBuscado) {
                $registroBuscado->update([
                    'id_tipo_ingreso_salida' => $request->id_tipo_ingreso_salida, 
                    'cantidad_ingreso' => $request->cantidad_ingreso, 
                    'stock' =>  $registroBuscado->stock + $request->cantidad_ingreso, 
                    'id_usuario' => auth()->user()->id,
                ]);
            }else{
                InventarioInterno::create([
                    'id_producto' => $request->id_producto, 
                    'id_sucursal' => $request->id_sucursal,
                    'id_usuario' => auth()->user()->id,
                    'id_tipo_ingreso_salida' => $request->id_tipo_ingreso_salida, 
                    'cantidad_ingreso' => $request->cantidad_ingreso, 
                    'stock' => $request->cantidad_ingreso, 
                ]);
            }

            return redirect()->route('home_inventario_interno', ["id_sucursal"=>$request->id_sucursal])->with('itemCreado', 'El item fue creado correctamente!');

        } catch (\Throwable $th) {
            return redirect()->route('home_inventario_interno', ["id_sucursal"=>$request->id_sucursal])->with('errorItemCreado', "Error al crear el item! ".$th->getMessage()." ".$th->getLine()." ".$th->getFile());
        }
    }

    public function update(Request $request)
    {
        
        $request->validate([
            'id_inventario_interno' => 'required|integer|exists:inventario_internos,id',
            'id_sucursal' => 'required|integer|exists:sucursals,id',
            'id_producto' => 'required|integer|exists:productos,id',
            'id_tipo_ingreso_salida' => 'required|integer|exists:tipo_ingreso_salidas,id',
            'cantidad_ingreso' => 'required|integer',
        ]);

        $registroInventarioInterno = InventarioInterno::findOrFail($request->id_inventario_interno);
        $registroInventarioInterno->id_producto = $request->id_producto;
        $registroInventarioInterno->id_sucursal = $request->id_sucursal;
        $registroInventarioInterno->id_usuario = auth()->user()->id;
        $registroInventarioInterno->id_tipo_ingreso_salida = $request->id_tipo_ingreso_salida;
        $registroInventarioInterno->stock = intval($registroInventarioInterno->stock) - intval($registroInventarioInterno->cantidad_ingreso) + intval($request->cantidad_ingreso);
        $registroInventarioInterno->cantidad_ingreso = $request->cantidad_ingreso;
        
        $estado = 0;
        if ($registroInventarioInterno->save()) {
            $estado = 1;
        }
    
        return redirect()->route('home_inventario_interno',["id_sucursal"=>$request->id_sucursal])->with('correcto', 'El item fue actualizado correctamente!');
    }

    public function editar_inventario_interno(int $id_sucursal, int $id_producto)
    {
        
        $registroBuscado = InventarioInterno::where(
            [
                'id_producto' => $id_producto, 
                'id_sucursal' => $id_sucursal
            ])->first();
        
        if ($registroBuscado) 
        {
            $sucursales = UserSucursal::sucursalesHabilitadasUsuario(auth()->user()->id);
            $productos = Producto::all();
            $tiposIngresosSalidas = TipoIngresoSalida::all();

            return view('inventarioInterno.edit',[
                'item' => $registroBuscado,
                'sucursales'=>$sucursales,
                'productos' => $productos,
                'tipoIngresoSalidas' => $tiposIngresosSalidas,
                'id_sucursal'=> $id_sucursal,
                'id_producto'=> $id_producto,
            ]);

        }else{
            return redirect()->route('home_inventario_interno', ['id_sucursal' => $id_sucursal])->with('errorItemDatosProporcionados', "Item no encontrado!");
        }
    }

    public function update_estado(Request $request)
    {
        $validar = $request->validate([
            'id_inventario_interno' => 'required|integer',
            'id_sucursal' => 'integer',
            'estado_inventario_interno' => 'required|integer',
        ]);

        try {
            
                InventarioInterno::where([
                    'id' => $request->id_inventario_interno,
                ])->update([
                    'estado' => $request->estado_inventario_interno == 0 ? 1 : 0,
                ]);

                return redirect()->route('home_inventario_interno', ['id_sucursal' => $request->id_sucursal])->with('itemActualizado', 'El estado fue actualizado correctamente!');

        } catch (\Throwable $th) {
            return redirect()->route('home_inventario_interno', ['id_sucursal' => $request->id_sucursal])->with('errorItemActualizado', "Error al actualizar el estado!");

        }
    }

    public function exportPdf(Request $request)
    {
        $validar = $request->validate([
            'id_sucursal' => 'required|integer',
        ]);

        $sucursal = Sucursal::find($request->id_sucursal);

        $pdf = Pdf::loadView('pdf.inventario', [
            'tituloPdf' => 'Inventario Interno',
            'fechaCorte' => date('d/m/Y'),
            'sucursal' => $sucursal,
            'inventario' => InventarioInterno::inventarioXSucurusal($request->id_sucursal)->get(),
        ]);

        $nombre_archivo = 'InventarioInterno_'.date('dmY_His').'.pdf';
        return $pdf->download($nombre_archivo);

    }

    public function exportExcel (Request $request)
    {

        $validar = $request->validate([
            'id_sucursal' => 'required|integer',
        ]);
        $sucursal = Sucursal::find($request->id_sucursal);
        $inventarioInternoSucursal = InventarioInterno::inventarioXSucurusal($request->id_sucursal)->get();
        
        $inventarioInternoSucursal = $inventarioInternoSucursal->map(function($item){
            $nombreCategoriaSegunProducto = Producto::selectRaw('
                                                            categorias.nombre as nombre_categoria
                                                           ')
                                                    ->join('categorias', 'categorias.id', 'productos.id_categoria')
                                                    ->where('productos.id',$item->id_productos)
                                                    ->get();

            $item->categoria = $nombreCategoriaSegunProducto ? $nombreCategoriaSegunProducto[0]->nombre_categoria : 'Sin categoría';
            
            return $item;
        });

        $nombre_archivo = 'InventarioInterno_'.date('dmY_His').'.xlsx';

        // Exportar los datos a CSV
        return Excel::download(new InventarioInternoExport($inventarioInternoSucursal), $nombre_archivo, \Maatwebsite\Excel\Excel::XLSX);
    }
}
