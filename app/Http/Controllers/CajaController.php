<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index(){
        
        
        $registros = Caja::selectRaw('
                                        cajas.fecha_cierre as fecha_cierre_caja,
                                        cajas.efectivo as efectivo_caja,
                                        cajas.transferencia as transferencia_caja, 
                                        cajas.qr as qr_caja, 
                                        cajas.observacion as observacion_caja,
                                        users.name as name_usuario,
                                        users.username as nombre_usuario,
                                        users.id as id_usuario
                                    ')
                          ->join("users", "users.id", "cajas.id_usuario");

        if ( auth()->user()->id != 1 ) {
            $registros = $registros->where('users.id', auth()->user()->id);
        }

        $registros = $registros->paginate(10);

        return view("caja.index", [
            "cierres_caja" => $registros,
        ]);
    }

    public function nuevoCierreCaja(Request $request){

        $validated = $request->validate([
            'fecha' => "required|string",
            'efectivo' => "required|numeric",
            'transferencia' => 'required|numeric',
            'qr' => 'required|numeric',
            'observacion' => 'string|nullable'
        ]);

        $existeCierreCajaEnFecha = Caja::select('fecha_cierre')
                                       ->where('fecha_cierre', date('Y-m-d'))
                                       ->where('id_usuario', auth()->user()->id)
                                       ->first();

        if ( !isset($existeCierreCajaEnFecha->fecha_cierre) ) {
            $addCierreCaja = Caja::create([
                "fecha_cierre" => $request->fecha,
                "efectivo" => $request->efectivo,
                "transferencia" => $request->transferencia,
                "qr" => $request->qr,
                "observacion" => isset($request->observacion) ? $request->observacion : "",
                "id_usuario" => auth()->user()->id,
               ]);    

            return redirect()->route('cierre_caja');
        } else{
            return redirect()->route('cierre_caja')->with("error", "Ya existe un cierre de caja de este dia y usuario");
        }

        
    }
}
