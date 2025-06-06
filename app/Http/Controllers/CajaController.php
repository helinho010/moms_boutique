<?php

namespace App\Http\Controllers;

use App\Exports\CierreCajaExport;
use App\Models\Caja;
use App\Models\Sucursal;
use App\Models\UserSucursal;
use App\Models\Venta;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class CajaController extends Controller
{
    public function index(Request $request)
    {

        $validacion = $request->validate([
            "buscar" => "string|nullable|max:50",
            "id_sucursal" => [ 'integer', Rule::when($request->id_sucursal != 999, 'exists:sucursals,id') ], 
        ]);

        $cierresCaja = Caja::query()->whereRaw('1=0')->paginate(10);

        if(isset($request->id_sucursal))
        {
            if (isset($request->buscar)) {
                $cierresCaja = Caja::buscar($request->buscar, $request->id_sucursal)
                                    ->paginate(10)
                                    ->withQueryString();     
            }else{
                $cierresCaja = Caja::cierresCajaXSucursal($request->id_sucursal)
                                   ->paginate(10)
                                   ->withQueryString(); 
            }
        }

        $sucursales = UserSucursal::sucursalesHabilitadasUsuario(auth()->user()->id);

        return view("caja.index", [
            "cierres_caja" => $cierresCaja,
            "sucursales" => $sucursales,
            "id_sucursal" => isset($request->id_sucursal) ? $request->id_sucursal : null,
        ]);
    }

    public function nuevoCierreCaja(Request $request){
        
        $validated = $request->validate([
            'fecha' => "required|string",
            'sucursal' => 'required|integer|exists:sucursals,id',
            'efectivo' => "required|numeric",
            'tarjeta' => "required|numeric",
            'transferencia' => 'required|numeric',
            'qr' => 'required|numeric',
            'venta_sistema' => 'required|numeric',
            'total_declarado' => 'required|numeric',
            'observacion' => 'string|nullable'
        ]);

        $existeCierreCajaEnFecha = Caja::selectRaw('
                                            cajas.fecha_cierre as fecha_cierre_caja,
                                            users.name as nombre_usuario,
                                            sucursals.direccion as direccion_sucursal
                                        ')
                                        ->join("users", "users.id", "cajas.id_usuario")
                                        ->join("sucursals", "sucursals.id", "cajas.id_sucursal")
                                        ->where('id_usuario', auth()->user()->id)
                                        ->where('id_sucursal', $request->sucursal)
                                        ->where('fecha_cierre', $request->fecha)
                                        ->first();
        
        if ( !isset($existeCierreCajaEnFecha) ) {
            $addCierreCaja = Caja::create([
                "fecha_cierre" => $request->fecha,
                "efectivo" => $request->efectivo,
                "tarjeta" => $request->tarjeta,
                "transferencia" => $request->transferencia,
                "qr" => $request->qr,
                "venta_sistema" => $request->venta_sistema,
                "total_declarado" => $request->total_declarado,
                "observacion" => isset($request->observacion) ? $request->observacion : "",
                "id_usuario" => auth()->user()->id,
                "id_sucursal" => $request->sucursal,
               ]);    

            return redirect()->route('home_caja')->with('exito', "Cierre alamcenado correctamente");
        } else{
            return redirect()->route('home_caja')->with("error", "Ya existe un cierre de caja Dia: $existeCierreCajaEnFecha->fecha_cierre_caja , Sucursal: $existeCierreCajaEnFecha->direccion_sucursal y Usuario: $existeCierreCajaEnFecha->nombre_usuario ");
        }        
    }

    public function ventaSucursalDia(Request $request){
        $validated = $request->validate([
            "id_sucursal" => "required|integer",
            "fecha" => "required|date",
        ]);

        $ventaDia = Venta::selectRaw('
                            sum(total_venta) as total_vendido
                         ')
                         ->where('id_sucursal',$request->id_sucursal)
                         ->where('estado',1)
                         ->where('created_at', '>=', $request->fecha." 00:00")
                         ->where('created_at', '<=', $request->fecha." 23:59")
                         ->first();

        return ["venta" => $ventaDia && $ventaDia->total_vendido !== null ? $ventaDia->total_vendido : 0];
    }

    public function editarCierre($id_cierre){
        
        $cierre = Caja::findOrFail($id_cierre);

        if ($cierre->id_usuario != auth()->user()->id) {
            return redirect()->route('home_caja');            
        }

        $sucursales = auth()->user()->usertype_id != 1 ? UserSucursal::sucursalesHabilitadasUsuario(auth()->user()->id) : Sucursal::where('activo',1)->get();
        
        return view('caja.edit',[
            "sucursales" => $sucursales,
            "cierre" => $cierre,
        ]);
    }

    public function guardarEditadoCierre(Request $request, $id_cierre){
        
        $validated = $request->validate([
            'fecha_cierre' => "required|string",
            'id_sucursal' => 'required|integer',
            'efectivo' => "required|numeric",
            'tarjeta' => "required|numeric",
            'transferencia' => 'required|numeric',
            'qr' => 'required|numeric',
            'venta_sistema' => 'required|numeric',
            'total_declarado' => 'required|numeric',
            'observacion' => 'string|nullable'
        ]);

        $cierre = Caja::findOrFail($id_cierre);
        
        $actualizado = $cierre->update($request->only(["fecha_cierre","id_sucursal","efectivo","tarjeta","transferencia", "qr", "venta_sistema", "total_declarado", "observacion"]));
        
        if ($actualizado) {
            return redirect()->route('home_caja')->with("exito", "¡Actualización exitosa!");
        } else {
            return redirect()->route('home_caja')->with("error", "Hubo un error al actualizar los datos.");
        }
    }

    public function verificarCierre(Request $request){
        // dd($request->request);
        $validacion = $request->validate([
            "id_cierre" => "required|integer",
        ]);

        $actualizado = false;
        if (isset($request->verificado_cierre)) {
            $cierre = Caja::findOrFail($request->id_cierre);
            $actualizado = $cierre->update(["verificado" => true]);
        }

        if ($actualizado) {
            return redirect()->route('home_caja')->with("exito", "¡Verificacion de cierre exitosa!");
        } else {
            return redirect()->route('home_caja')->with("error", "No se hizo ningun cambio al cierre seleccionado");
        }
    }

    public function exportarCierrePdf(Request $request){

        $validacion = $request->validate([
            "id_sucursal" => "required|integer|exists:sucursals,id",
            "fecha_inicio" => "required|date",
            "fecha_final" => "required|date",
        ]);

        if ($request->fecha_inicio <= $request->fecha_final) 
        {
            
            $cierresCaja = Caja::cierresCajaXSucursal($request->id_sucursal)
                                ->whereBetween('fecha_cierre', [$request->fecha_inicio, $request->fecha_final]) 
                                ->get();

            $sucursal = Sucursal::findOrFail($request->id_sucursal);

            $pdf = Pdf::loadView('pdf.cierreCaja', [
                'tituloPdf' => 'Cierre de Caja', 
                'fechaCorte' => date('d-m-Y'),
                'sucursal' => $sucursal,
                'cierres' => $cierresCaja,
            ]);

            return $pdf->download('CierreCaja_'. date('Ymd_His') .'.pdf');

        } else {
            return redirect()->route('home_caja')->with("error", "La fecha de inicio no puede ser mayor a la fecha de final");
        }
    }

    public function exportarCierreExcel(Request $request){
        $cierres = Caja::selectRaw('
                                    cajas.id as id_caja,
                                    cajas.fecha_cierre as fecha_cierre_caja,
                                    cajas.efectivo as efectivo_caja,
                                    cajas.tarjeta as tarjeta_caja,
                                    cajas.transferencia as transferencia_caja, 
                                    cajas.qr as qr_caja,
                                    cajas.venta_sistema as venta_sistema_caja,
                                    cajas.total_declarado as total_declarado_caja,
                                    cajas.observacion as observacion_caja,
                                    cajas.verificado as verificado_caja,
                                    sucursals.id as id_sucursal,
                                    sucursals.razon_social as razon_social_sucursal,
                                    sucursals.direccion as direccion_sucursal,
                                    users.name as name_usuario,
                                    users.username as nombre_usuario,
                                    users.id as id_usuario
                                  ')
                        ->join("users", "users.id", "cajas.id_usuario")
                        ->join("sucursals", "sucursals.id", "cajas.id_sucursal")
                        ->orderBy('fecha_cierre','asc')
                        ->where('fecha_cierre', '>=', $request->fecha_inicio)
                        ->where('fecha_cierre', '<=', $request->fecha_final);

        if (auth()->user()->usertype_id == 1) {
            $cierres = $cierres->get();
        } else {
            $cierres = $cierres->where('id_usuario',auth()->user()->id)
                            ->get();
        }
        
        $numeroRegistro = 1;
        $exportData = [];

        foreach ($cierres as $cierre) {
            $exportData[] = [
                'Nro' => $numeroRegistro,
                'Sucursal' => $cierre->direccion_sucursal,
                'Fecha' => $cierre->fecha_cierre_caja,
                'Efectivo' => $cierre->efectivo_caja,
                'Tarjeta' => $cierre->tarjeta_caja,
                'Transferencia' => $cierre->transferencia_caja,
                'QR' => $cierre->qr_caja,
                'Venta Sistema' => $cierre->venta_sistema_caja,
                'Total Declarado' => $cierre->total_declarado_caja,
                'Observacion' => $cierre->observacion_caja,
                'Usuario' => $cierre->name_usuario,
                'Verificado' => $cierre->verificado_caja ? 'Sí' : 'No',
            ];
            $numeroRegistro++;
        }

        $filename = 'CierreCaja_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new CierreCajaExport(collect($exportData)), $filename);
    }

}
